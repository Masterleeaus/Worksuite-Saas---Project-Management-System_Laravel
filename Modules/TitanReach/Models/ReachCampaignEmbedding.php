<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;

class ReachCampaignEmbedding extends Model
{
    protected $table = 'reach_campaign_embeddings';

    protected $fillable = [
        'company_id', 'campaign_id', 'source_type', 'source_url',
        'content', 'embedding', 'meta',
    ];

    protected $casts = [
        'embedding' => 'array',
        'meta'      => 'array',
    ];
}
