<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CustomerConnect\Traits\CompanyScoped;

class NewsletterModule extends Model
{
    use CompanyScoped;
    use HasFactory;
    protected $table = 'newsletter_module';
    protected $fillable = [
        'module',
        'submodule',
        'field_json'
    ];


    protected static function newFactory()
    {
        return \Modules\CustomerConnect\Database\factories\NewsletterModuleFactory::new();
    }

}