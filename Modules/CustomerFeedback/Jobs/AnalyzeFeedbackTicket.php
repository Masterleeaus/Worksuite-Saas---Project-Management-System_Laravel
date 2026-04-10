<?php

namespace Modules\CustomerFeedback\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CustomerFeedback\Entities\FeedbackTicket;

/**
 * Stub job for AI-powered ticket analysis.
 * Extend this class to implement sentiment analysis, category suggestion, etc.
 */
class AnalyzeFeedbackTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly FeedbackTicket $ticket) {}

    public function handle(): void
    {
        // TODO: implement AI analysis using configured provider
    }
}
