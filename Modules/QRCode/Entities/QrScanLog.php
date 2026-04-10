<?php

namespace Modules\QRCode\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use App\Models\User;

class QrScanLog extends BaseModel
{

    use HasCompany;

    protected $table = 'qr_code_scan_logs';

    protected $guarded = ['id'];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function qrCodeData()
    {
        return $this->belongsTo(QrCodeData::class, 'qr_code_data_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
