<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CustomerConnect\Traits\CompanyScoped;

/**
 * Maps inbound addresses (phone numbers / bot identifiers) to a tenant/company.
 * Used to deterministically resolve tenancy for webhook ingestion.
 */
class ChannelIdentity extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_channel_identities';

    protected $fillable = [
        'company_id',
        'channel',
        'provider',
        'inbound_address',
        'meta_json',
    ];

    protected $casts = [
        'meta_json' => 'array',
    ];
}