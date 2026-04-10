<?php

namespace Modules\QRCode\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Modules\QRCode\Entities\QRCodeSetting;
use Modules\QRCode\Entities\QrCodeData;
use Modules\QRCode\Entities\QrScanLog;

class QrScanLogController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'qrcode::app.scanLogs';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array(QRCodeSetting::MODULE_NAME, $this->user->modules));

            return $next($request);
        });
    }

    /**
     * Display scan logs for a specific QR code.
     */
    public function index(int $id)
    {
        $viewPermission = user()->permission('view_qrcode');
        abort_403($viewPermission == 'none');

        $this->qrCodeData = QrCodeData::findOrFail($id);
        $this->scanLogs   = QrScanLog::where('qr_code_data_id', $id)
            ->with('user')
            ->orderByDesc('scanned_at')
            ->paginate(20);

        if (request()->ajax()) {
            $html = view('qrcode::qrcode.scan-logs', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('qrcode::qrcode.scan-logs', $this->data);
    }

}
