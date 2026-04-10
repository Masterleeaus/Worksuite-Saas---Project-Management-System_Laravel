<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BookingModule\Traits\CompanyScoped;

class Question extends Model
{
    use CompanyScoped;
    use HasFactory;

    protected $fillable = [
        'company_id',
        'question',
        'question_type',
        'available_answer',
        'is_required',
        'is_enabled',
        'workspace',
        'created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\QuestionFactory::new();
    }

    public static $question_type = [
        'radio' => 'Radio',
        'dropdown' => 'Dropdown',
        'text' => 'Text',
        'checkbox' => 'Checkbox',
    ];

    protected static function booted(): void
    {
        // Tenant safety: scope queries to current company when available.
        static::addGlobalScope('company', function ($query) {
            $companyId = null;

            try {
                if (function_exists('company') && company()) {
                    $companyId = company()->id;
                } elseif (auth()->check()) {
                    $companyId = auth()->user()->company_id ?? (auth()->user()->company->id ?? null);
                }
            } catch (\Throwable $e) {
                $companyId = null;
            }

            if ($companyId) {
                $query->where($query->getModel()->getTable() . '.company_id', $companyId);
            }
        });

        // Auto-fill company_id for new rows when possible.
        static::creating(function ($model) {
            if (!isset($model->company_id) || !$model->company_id) {
                try {
                    if (function_exists('company') && company()) {
                        $model->company_id = company()->id;
                    } elseif (auth()->check()) {
                        $model->company_id = auth()->user()->company_id ?? (auth()->user()->company->id ?? null);
                    }
                } catch (\Throwable $e) {
                    // leave null
                }
            }
        });
    }

}