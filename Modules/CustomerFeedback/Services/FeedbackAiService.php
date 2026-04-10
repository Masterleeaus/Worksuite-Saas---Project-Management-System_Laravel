<?php

namespace Modules\CustomerFeedback\Services;

use Modules\CustomerFeedback\Entities\FeedbackTicket;

/**
 * Stub AI service for feedback analysis.
 * Extend this class to integrate with an actual AI provider (OpenAI, etc.)
 */
class FeedbackAiService
{
    /**
     * Analyze a ticket and return basic sentiment/category suggestions.
     * @return array{sentiment: string|null, category: string|null, priority: string|null, response: string|null}
     */
    public function analyze(FeedbackTicket $ticket): array
    {
        return [
            'sentiment' => null,
            'category'  => null,
            'priority'  => null,
            'response'  => null,
        ];
    }
}
