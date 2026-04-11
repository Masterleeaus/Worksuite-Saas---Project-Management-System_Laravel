<?php

namespace Modules\StaffCompliance\Entities;

use Illuminate\Database\Eloquent\Model;

class ComplianceDocumentType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'vertical',
        'is_mandatory',
        'renewal_period_months',
        'description',
    ];

    protected $casts = [
        'vertical'     => 'array',
        'is_mandatory' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(WorkerComplianceDocument::class, 'document_type_id');
    }
}
