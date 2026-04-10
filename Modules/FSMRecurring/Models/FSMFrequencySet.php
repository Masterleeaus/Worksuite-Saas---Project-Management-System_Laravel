<?php

namespace Modules\FSMRecurring\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * FSMFrequencySet – a named collection of FSMFrequency rules.
 *
 * Ported from Odoo fsm.frequency.set.
 * Inclusive rules contribute dates; exclusive rules remove dates.
 */
class FSMFrequencySet extends Model
{
    protected $table = 'fsm_frequency_sets';

    protected $fillable = [
        'company_id', 'name', 'active',
        'schedule_days', 'buffer_early', 'buffer_late',
    ];

    protected $casts = [
        'active'        => 'boolean',
        'schedule_days' => 'integer',
        'buffer_early'  => 'integer',
        'buffer_late'   => 'integer',
    ];

    public function frequencies()
    {
        return $this->belongsToMany(
            FSMFrequency::class,
            'fsm_frequency_set_rule',
            'frequency_set_id',
            'frequency_id'
        );
    }

    /**
     * Return all occurrence dates from $start up to $until (or schedule_days ahead),
     * applying inclusive and exclusive rules.
     *
     * @return Carbon[]
     */
    public function getOccurrences(Carbon $start, ?Carbon $until = null): array
    {
        if ($until === null) {
            $until = Carbon::now()->addDays(max(1, (int) $this->schedule_days));
        }

        $inclusive = [];
        $exclusive = [];

        foreach ($this->frequencies as $freq) {
            $dates = $freq->getOccurrences($start, $until);
            if ($freq->is_exclusive) {
                foreach ($dates as $d) {
                    $exclusive[$d->toDateString()] = true;
                }
            } else {
                foreach ($dates as $d) {
                    $inclusive[$d->toDateString()] = $d;
                }
            }
        }

        // Remove excluded dates and sort
        foreach (array_keys($exclusive) as $key) {
            unset($inclusive[$key]);
        }

        $result = array_values($inclusive);
        usort($result, fn(Carbon $a, Carbon $b) => $a->timestamp <=> $b->timestamp);

        return $result;
    }
}
