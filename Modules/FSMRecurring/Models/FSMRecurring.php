<?php

namespace Modules\FSMRecurring\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMTeam;
use Modules\FSMCore\Models\FSMTemplate;

/**
 * FSMRecurring – the recurring work order schedule.
 *
 * Ported from Odoo fsm.recurring.
 *
 * State machine:
 *   draft  → progress (action_start)
 *   progress → suspend (action_suspend) or close (auto via cron / action_close)
 *   suspend → progress (action_resume) or close
 */
class FSMRecurring extends Model
{
    protected $table = 'fsm_recurrings';

    protected $fillable = [
        'company_id', 'name', 'state',
        'recurring_template_id',
        'location_id', 'description',
        'frequency_set_id', 'scheduled_duration',
        'start_date', 'end_date', 'max_orders',
        'fsm_template_id', 'team_id', 'person_id',
    ];

    protected $casts = [
        'start_date'         => 'datetime',
        'end_date'           => 'datetime',
        'max_orders'         => 'integer',
        'scheduled_duration' => 'decimal:2',
    ];

    // State constants
    const STATE_DRAFT    = 'draft';
    const STATE_PROGRESS = 'progress';
    const STATE_SUSPEND  = 'suspend';
    const STATE_CLOSE    = 'close';

    public static array $states = [
        self::STATE_DRAFT    => 'Draft',
        self::STATE_PROGRESS => 'In Progress',
        self::STATE_SUSPEND  => 'Suspended',
        self::STATE_CLOSE    => 'Closed',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function recurringTemplate()
    {
        return $this->belongsTo(FSMRecurringTemplate::class, 'recurring_template_id');
    }

    public function location()
    {
        return $this->belongsTo(FSMLocation::class, 'location_id');
    }

    public function frequencySet()
    {
        return $this->belongsTo(FSMFrequencySet::class, 'frequency_set_id');
    }

    public function fsmTemplate()
    {
        return $this->belongsTo(FSMTemplate::class, 'fsm_template_id');
    }

    public function team()
    {
        return $this->belongsTo(FSMTeam::class, 'team_id');
    }

    public function person()
    {
        return $this->belongsTo(\App\Models\User::class, 'person_id');
    }

    public function equipment()
    {
        return $this->belongsToMany(
            \Modules\FSMCore\Models\FSMEquipment::class,
            'fsm_recurring_equipment',
            'fsm_recurring_id',
            'fsm_equipment_id'
        );
    }

    public function orders()
    {
        return $this->hasMany(FSMOrder::class, 'fsm_recurring_id');
    }

    // ─── Computed helpers ────────────────────────────────────────────────────

    public function getOrderCountAttribute(): int
    {
        return $this->orders()->count();
    }

    public function getStateLabelAttribute(): string
    {
        return self::$states[$this->state] ?? $this->state;
    }

    // ─── State transitions ───────────────────────────────────────────────────

    public function actionStart(): void
    {
        if (!$this->start_date) {
            $this->start_date = now();
        }
        $this->state = self::STATE_PROGRESS;
        $this->save();
        $this->generateOrders();
    }

    public function actionSuspend(): void
    {
        // Cancel open orders tied to this recurring schedule
        if (class_exists(\Modules\FSMCore\Models\FSMStage::class)) {
            $closedStageIds = \Modules\FSMCore\Models\FSMStage::where('is_closed', true)
                ->pluck('id')->toArray();
            $this->orders()
                ->whereNotIn('stage_id', $closedStageIds)
                ->get()
                ->each(fn($o) => $o->update(['stage_id' => null]));
        }
        $this->update(['state' => self::STATE_SUSPEND]);
    }

    public function actionResume(): void
    {
        $this->update(['state' => self::STATE_PROGRESS]);
        $this->generateOrders();
    }

    public function actionClose(): void
    {
        $this->update(['state' => self::STATE_CLOSE]);
    }

    // ─── Order generation ─────────────────────────────────────────────────────

    /**
     * Determine the "next start date" for generating orders.
     * Uses the last scheduled_date_start among existing orders, or start_date.
     */
    private function getNextDate(): Carbon
    {
        $last = $this->orders()
            ->whereNotNull('scheduled_date_start')
            ->orderByDesc('scheduled_date_start')
            ->value('scheduled_date_start');

        return $last ? Carbon::parse($last) : Carbon::parse($this->start_date ?? now());
    }

    /**
     * Determine the upper bound date for generating orders.
     */
    private function getThruDate(): Carbon
    {
        $scheduleDays = $this->frequencySet?->schedule_days
            ?? config('fsmrecurring.schedule_days_ahead', 30);

        $thruDate = Carbon::now()->addDays(max(1, (int) $scheduleDays));

        if ($this->end_date && Carbon::parse($this->end_date)->lessThan($thruDate)) {
            $thruDate = Carbon::parse($this->end_date);
        }

        return $thruDate;
    }

    /**
     * Prepare values for a new FSM Order generated from this recurring schedule.
     */
    private function prepareOrderValues(Carbon $date): array
    {
        $prefix = config('fsmcore.order_reference_prefix', 'ORD');
        $last   = FSMOrder::max('id') ?? 0;
        $name   = $prefix . '-' . str_pad((int) $last + 1, 5, '0', STR_PAD_LEFT);

        return [
            'name'                 => $name,
            'fsm_recurring_id'     => $this->id,
            'location_id'          => $this->location_id,
            'team_id'              => $this->team_id,
            'person_id'            => $this->person_id,
            'template_id'          => $this->fsm_template_id,
            'scheduled_date_start' => $date->toDateTimeString(),
            'description'          => $this->description,
            'company_id'           => $this->company_id,
        ];
    }

    /**
     * Generate FSM orders up to the schedule window.
     * Skips dates where an order already exists.
     * Respects max_orders limit.
     *
     * @return FSMOrder[]
     */
    public function generateOrders(): array
    {
        if ($this->state !== self::STATE_PROGRESS) {
            return [];
        }

        if (!$this->frequencySet) {
            return [];
        }

        // Collect already-scheduled dates
        $existingDates = $this->orders()
            ->whereNotNull('scheduled_date_start')
            ->pluck('scheduled_date_start')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        $nextDate  = $this->getNextDate();
        $thruDate  = $this->getThruDate();
        $maxOrders = (int) $this->max_orders;
        $orderCount = $this->order_count;

        $occurrences = $this->frequencySet->getOccurrences($nextDate, $thruDate);

        $created = [];
        foreach ($occurrences as $date) {
            // Skip if already have an order for this date
            if (in_array($date->toDateString(), $existingDates, true)) {
                continue;
            }

            // Respect max_orders limit (0 = unlimited)
            if ($maxOrders > 0 && $orderCount >= $maxOrders) {
                break;
            }

            $order = FSMOrder::create($this->prepareOrderValues($date));

            if ($this->equipment()->exists()) {
                $order->equipment()->sync($this->equipment()->pluck('fsm_equipment.id')->toArray());
            }

            $created[] = $order;
            $existingDates[] = $date->toDateString();
            $orderCount++;
        }

        return $created;
    }

    // ─── Cron helpers ────────────────────────────────────────────────────────

    /**
     * Called by the scheduled command to generate orders for all active recurring schedules.
     */
    public static function cronGenerateOrders(): int
    {
        $count = 0;
        static::where('state', self::STATE_PROGRESS)->each(function (self $rec) use (&$count) {
            $count += count($rec->generateOrders());
        });
        return $count;
    }

    /**
     * Called by the scheduled command to auto-close expired recurring schedules.
     */
    public static function cronManageExpiration(): int
    {
        $toClose = static::where('state', self::STATE_PROGRESS)
            ->get()
            ->filter(function (self $rec) {
                if ($rec->end_date && Carbon::parse($rec->end_date)->lessThanOrEqualTo(now())) {
                    return true;
                }
                if ($rec->max_orders > 0 && $rec->order_count >= $rec->max_orders) {
                    return true;
                }
                return false;
            });

        foreach ($toClose as $rec) {
            $rec->update(['state' => self::STATE_CLOSE]);
        }

        return $toClose->count();
    }
}
