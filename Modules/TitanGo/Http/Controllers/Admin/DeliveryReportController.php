<?php

namespace Modules\TitanGo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\FSMCore\Models\FSMOrder;
use Modules\TitanGo\Models\TitanGoChecklistCompletion;
use Modules\TitanGo\Mail\ProofOfDeliveryMail;

/**
 * DeliveryReportController
 *
 * Generates, previews, downloads, and emails the Proof-of-Delivery report
 * for a completed Titan Go visit.
 *
 * The report includes:
 *   • Visit header (name, address, worker, date range, stage)
 *   • Checklist table — each step with completion status, notes,
 *     inline photo (if any), and signature (if any)
 *   • Summary footer (completed / total, generation timestamp)
 *
 * Routes:
 *   GET  /account/titan-go/delivery/{visitId}         — web preview
 *   GET  /account/titan-go/delivery/{visitId}/pdf     — download PDF
 *   POST /account/titan-go/delivery/{visitId}/email   — email PDF to customer
 */
class DeliveryReportController extends Controller
{
    // ─── Preview (HTML in browser) ─────────────────────────────────────────

    public function show(int $visitId)
    {
        [$order, $completions, $steps] = $this->buildReportData($visitId);

        return view('titango::admin.delivery.show', compact('order', 'completions', 'steps'));
    }

    // ─── Download PDF ──────────────────────────────────────────────────────

    public function download(int $visitId)
    {
        [$order, $completions, $steps] = $this->buildReportData($visitId);

        $html = view('titango::admin.delivery.pdf', compact('order', 'completions', 'steps'))->render();

        $pdf = app('dompdf.wrapper');
        $pdf->setOption('enable_php', false);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->loadHTML($html);

        $filename = 'proof-of-delivery-' . $visitId . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    // ─── Email PDF to customer ─────────────────────────────────────────────

    public function email(Request $request, int $visitId)
    {
        $request->validate([
            'recipient_email' => 'required|email|max:255',
        ]);

        [$order, $completions, $steps] = $this->buildReportData($visitId);

        $html     = view('titango::admin.delivery.pdf', compact('order', 'completions', 'steps'))->render();
        $pdf      = app('dompdf.wrapper');
        $pdf->setOption('enable_php', false);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->loadHTML($html);
        $pdfContent  = $pdf->output();
        $filename    = 'proof-of-delivery-' . $visitId . '-' . now()->format('Ymd') . '.pdf';

        Mail::to($request->recipient_email)
            ->send(new ProofOfDeliveryMail($order, $pdfContent, $filename));

        return redirect()
            ->route('titango.admin.delivery.show', $visitId)
            ->with('success', 'Proof-of-delivery emailed to ' . $request->recipient_email . '.');
    }

    // ─── Shared data builder ───────────────────────────────────────────────

    private function buildReportData(int $visitId): array
    {
        $order = FSMOrder::with(['template', 'location', 'person', 'stage'])
            ->findOrFail($visitId);

        // Load all completions for this visit ordered by step index
        $completions = TitanGoChecklistCompletion::where('visit_id', $visitId)
            ->orderBy('step_index')
            ->get()
            ->keyBy('step_index');

        // Build unified steps list from template + completion data
        $rawSteps = $order->template?->checklist ?? [];
        $steps    = collect($rawSteps)->values()->map(function ($rawStep, int $idx) use ($completions) {
            if (is_array($rawStep)) {
                $instruction    = $rawStep['instruction']        ?? '';
                $photoRequired  = (bool) ($rawStep['photo_required']     ?? false);
                $sigRequired    = (bool) ($rawStep['signature_required'] ?? false);
                $isRequired     = (bool) ($rawStep['is_required']        ?? true);
            } else {
                $instruction    = (string) $rawStep;
                $photoRequired  = false;
                $sigRequired    = false;
                $isRequired     = true;
            }

            $completion = $completions->get($idx);

            return [
                'index'              => $idx,
                'instruction'        => $instruction,
                'photo_required'     => $photoRequired,
                'signature_required' => $sigRequired,
                'is_required'        => $isRequired,
                'completed'          => (bool) $completion,
                'completed_at'       => $completion?->completed_at,
                'notes'              => $completion?->notes,
                'photo_data'         => $completion ? $completion->getRawOriginal('photo_data')     : null,
                'signature_data'     => $completion ? $completion->getRawOriginal('signature_data') : null,
            ];
        });

        return [$order, $completions, $steps];
    }
}
