<?php

namespace Modules\FSMServiceAgreement\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMTemplate;

class FSMServiceAgreement extends Model
{
    protected $table = 'fsm_service_agreements';

    const STATE_DRAFT     = 'draft';
    const STATE_ACTIVE    = 'active';
    const STATE_EXPIRED   = 'expired';
    const STATE_CANCELLED = 'cancelled';

    protected $fillable = [
        'company_id',
        'name',
        'partner_id',
        'start_date',
        'end_date',
        'state',
        'recurrence_rule',
        'notes',
        'value',
    ];

    protected $casts = [
        'company_id'       => 'integer',
        'partner_id'       => 'integer',
        'start_date'       => 'date',
        'end_date'         => 'date',
        'value'            => 'decimal:2',
        'recurrence_rule'  => 'array',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function locations()
    {
        return $this->belongsToMany(
            FSMLocation::class,
            'fsm_agreement_location',
            'fsm_service_agreement_id',
            'fsm_location_id'
        );
    }

    public function templates()
    {
        return $this->belongsToMany(
            FSMTemplate::class,
            'fsm_agreement_template',
            'fsm_service_agreement_id',
            'fsm_template_id'
        );
    }

    public function lines()
    {
        return $this->hasMany(FSMAgreementLine::class, 'agreement_id')->orderBy('sort_order');
    }

    public function orders()
    {
        return $this->hasMany(FSMOrder::class, 'agreement_id');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\User::class, 'partner_id');
    }

    // ── State helpers ────────────────────────────────────────────────────────

    public function isDraft(): bool
    {
        return $this->state === self::STATE_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->state === self::STATE_ACTIVE;
    }

    public function isExpired(): bool
    {
        return $this->state === self::STATE_EXPIRED;
    }

    public function isCancelled(): bool
    {
        return $this->state === self::STATE_CANCELLED;
    }

    /**
     * Returns true when the agreement expires within the given number of days
     * and is currently active.
     */
    public function isExpiringSoon(int $days = 30): bool
    {
        if (! $this->isActive() || $this->end_date === null) {
            return false;
        }

        return $this->end_date->diffInDays(now(), false) >= -$days
            && $this->end_date->isFuture();
    }

    /**
     * Activate the agreement and generate the first set of FSM Orders.
     * One order is created per location × template combination.
     * Returns the collection of created orders.
     */
    public function activate(): \Illuminate\Support\Collection
    {
        $this->state = self::STATE_ACTIVE;
        $this->save();

        return $this->generateInitialOrders();
    }

    /**
     * Cancel the agreement.
     */
    public function cancel(): void
    {
        $this->state = self::STATE_CANCELLED;
        $this->save();
    }

    /**
     * Mark the agreement as expired.
     */
    public function expire(): void
    {
        $this->state = self::STATE_EXPIRED;
        $this->save();
    }

    /**
     * Generate the first round of FSM Orders for each location × template pair.
     */
    public function generateInitialOrders(): \Illuminate\Support\Collection
    {
        $created = collect();

        $locations = $this->locations;
        $templates = $this->templates;

        // If no locations defined, generate without location specificity
        if ($locations->isEmpty()) {
            $locations = collect([null]);
        }

        // If no templates defined, generate a generic order per location
        if ($templates->isEmpty()) {
            $templates = collect([null]);
        }

        foreach ($locations as $location) {
            foreach ($templates as $template) {
                $order = FSMOrder::create([
                    'company_id'           => $this->company_id,
                    'name'                 => $this->nextOrderReference(),
                    'location_id'          => $location?->id,
                    'template_id'          => $template?->id,
                    'agreement_id'         => $this->id,
                    'scheduled_date_start' => $this->start_date->toDateTimeString(),
                ]);
                $created->push($order);
            }
        }

        return $created;
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Generate the next sequential order reference (ORD-00001 style).
     */
    protected function nextOrderReference(): string
    {
        $last   = FSMOrder::max('id') ?? 0;
        $prefix = config('fsmcore.order_reference_prefix', 'ORD');

        return $prefix . '-' . str_pad((int) $last + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Human-readable state label with Bootstrap colour.
     */
    public function stateLabel(): array
    {
        return match ($this->state) {
            self::STATE_ACTIVE    => ['label' => 'Active',    'class' => 'bg-success'],
            self::STATE_EXPIRED   => ['label' => 'Expired',   'class' => 'bg-danger'],
            self::STATE_CANCELLED => ['label' => 'Cancelled', 'class' => 'bg-secondary'],
            default               => ['label' => 'Draft',     'class' => 'bg-warning text-dark'],
        };
    }
}
