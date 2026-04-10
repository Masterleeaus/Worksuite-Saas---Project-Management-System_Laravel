<?php

namespace Modules\TitanDocs\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'document_templates';

    protected $fillable = [
        'name',
        'template_type',
        'document_type',
        'html_content',
        'required_fields',
        'is_active',
        'is_global',
        'company_id',
        'created_by',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'required_fields' => 'array',
        'is_active'        => 'boolean',
        'is_global'        => 'boolean',
        'is_approved'      => 'boolean',
        'approved_at'      => 'datetime',
    ];

    /**
     * Scope to templates available for the given company (global or own).
     */
    public function scopeAvailableFor($query, ?int $companyId)
    {
        return $query->where('is_active', true)
                     ->where('is_approved', true)
                     ->where(function ($q) use ($companyId) {
                         $q->where('is_global', true)
                           ->orWhere('company_id', $companyId);
                     });
    }

    /**
     * Render the template by replacing {{field}} placeholders with values.
     * Sanitises field values to prevent XSS injection.
     *
     * @param  array<string,string>  $values
     * @return string
     */
    public function render(array $values): string
    {
        $html = $this->html_content;
        foreach ($values as $key => $value) {
            // Strip HTML from merge field values to prevent template injection
            $safeValue = htmlspecialchars(strip_tags((string) $value), ENT_QUOTES, 'UTF-8');
            $html = str_replace('{{' . $key . '}}', $safeValue, $html);
        }
        return $html;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
}
