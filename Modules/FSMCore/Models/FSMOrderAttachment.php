<?php

namespace Modules\FSMCore\Models;

use Illuminate\Database\Eloquent\Model;

class FSMOrderAttachment extends Model
{
    protected $table = 'fsm_order_attachments';

    protected $fillable = [
        'fsm_order_id',
        'uploaded_by',
        'filename',
        'path',
        'mime_type',
        'size',
        'description',
    ];

    protected $casts = [
        'fsm_order_id' => 'integer',
        'uploaded_by'  => 'integer',
        'size'         => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(FSMOrder::class, 'fsm_order_id');
    }

    public function uploader()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }

    /**
     * Returns the public URL for downloading this attachment.
     */
    public function url(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Human-readable file size (e.g. "1.4 MB").
     */
    public function humanSize(): string
    {
        $bytes = (int) $this->size;

        if ($bytes < 1024) {
            return "{$bytes} B";
        }

        if ($bytes < 1_048_576) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return round($bytes / 1_048_576, 1) . ' MB';
    }
}
