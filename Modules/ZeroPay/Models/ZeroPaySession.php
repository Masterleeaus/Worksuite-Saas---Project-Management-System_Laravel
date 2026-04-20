<?php

namespace Modules\ZeroPay\Models;

use App\Models\Invoice;
use App\Traits\HasCompany;

class ZeroPaySession extends \App\Models\BaseModel
{
    use HasCompany;

    protected $table = 'zeropay_sessions';

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'meta' => 'array',
        'amount' => 'float',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function attempts()
    {
        return $this->hasMany(ZeroPayAttempt::class, 'session_id');
    }

    public function downloadTokens()
    {
        return $this->hasMany(ZeroPayDownloadToken::class, 'session_id');
    }

    public function followups()
    {
        return $this->hasMany(ZeroPayFollowup::class, 'session_id');
    }

    public function channelLogs()
    {
        return $this->hasMany(ZeroPayChannelLog::class, 'session_id');
    }

    public function voiceRuns()
    {
        return $this->hasMany(ZeroPayVoiceRun::class, 'session_id');
    }

    public function aiSuggestions()
    {
        return $this->hasMany(ZeroPayAiSuggestion::class, 'session_id');
    }
}
