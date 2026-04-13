<?php

namespace Modules\TitanTalk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TitanTalkMessageFile extends Model
{
    protected $table = 'titan_talk_message_files';

    protected $guarded = ['id'];

    public function message(): BelongsTo
    {
        return $this->belongsTo(TitanTalkMessage::class, 'message_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->filename);
    }
}
