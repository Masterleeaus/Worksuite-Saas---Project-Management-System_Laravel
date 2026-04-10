<?php

namespace Modules\Faq\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Faq\Traits\CompanyScoped;

class Faq extends Model
{
    use CompanyScoped;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'company_id',
        'visibility',
        'source_type',
        'tags',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tags' => 'array',
    ];
}
