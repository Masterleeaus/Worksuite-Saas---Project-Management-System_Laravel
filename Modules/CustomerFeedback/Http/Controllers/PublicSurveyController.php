<?php

namespace Modules\CustomerFeedback\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CustomerFeedback\Entities\NpsSurvey;
use Modules\CustomerFeedback\Services\NpsSurveyService;

/**
 * Handles the public (unauthenticated) survey link.
 * Access via: GET /survey/{token}  and  POST /survey/{token}
 */
class PublicSurveyController extends Controller
{
    public function __construct(private NpsSurveyService $surveyService) {}

    /**
     * Display the survey form for a given token.
     */
    public function show(string $token)
    {
        $survey = NpsSurvey::where('survey_token', $token)->firstOrFail();

        if ($survey->isCompleted()) {
            return view('customer-feedback::surveys.public.already-completed');
        }

        if ($survey->isExpired()) {
            return view('customer-feedback::surveys.public.expired');
        }

        return view('customer-feedback::surveys.public.form', compact('survey'));
    }

    /**
     * Accept and persist a survey submission.
     */
    public function submit(Request $request, string $token)
    {
        $survey = NpsSurvey::where('survey_token', $token)->firstOrFail();

        $request->validate([
            'nps_score'          => 'required|integer|min:0|max:10',
            'service_rating'     => 'nullable|integer|min:1|max:5',
            'cleaner_rating'     => 'nullable|integer|min:1|max:5',
            'punctuality_rating' => 'nullable|integer|min:1|max:5',
            'comments'           => 'nullable|string|max:2000',
        ]);

        try {
            $this->surveyService->submitResponse($survey, $request->only([
                'nps_score',
                'service_rating',
                'cleaner_rating',
                'punctuality_rating',
                'comments',
            ]));
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'survey_already_completed') {
                return view('customer-feedback::surveys.public.already-completed');
            }

            if ($e->getMessage() === 'survey_expired') {
                return view('customer-feedback::surveys.public.expired');
            }

            abort(422, $e->getMessage());
        }

        return view('customer-feedback::surveys.public.thank-you', compact('survey'));
    }
}
