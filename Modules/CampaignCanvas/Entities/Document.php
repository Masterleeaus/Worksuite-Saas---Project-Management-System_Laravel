<?php
namespace Modules\CampaignCanvas\Entities;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasCompany;

    protected $table = 'ext_creative_suite_documents';

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Document $doc) {
            if (empty($doc->uuid)) {
                $doc->uuid = (string) Str::uuid();
            }
        });
    }
}
