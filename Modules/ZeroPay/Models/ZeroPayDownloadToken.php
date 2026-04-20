<?php

namespace Modules\ZeroPay\Models;

use App\Traits\HasCompany;

class ZeroPayDownloadToken extends \App\Models\BaseModel
{
    use HasCompany;

    protected $table = 'zeropay_download_tokens';

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'meta' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
