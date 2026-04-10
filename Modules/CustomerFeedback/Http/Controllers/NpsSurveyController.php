<?php

namespace Modules\CustomerFeedback\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\AccountBaseController;
use Modules\CustomerFeedback\Entities\NpsSurvey;
use Modules\CustomerFeedback\Services\NpsSurveyService;

class NpsSurveyController extends AccountBaseController
{
    private NpsSurveyService $surveyService;

    public function __construct(NpsSurveyService $surveyService)
    {
        parent::__construct();
        $this->surveyService = $surveyService;
        $this->pageTitle     = 'customer-feedback::modules.nps';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('customer-feedback', $this->user->modules));
            return $next($request);
        });
    }

    /**
     * List all NPS surveys (authenticated admin/agent view).
     */
    public function index()
    {
        abort_403(user()->permission('view_feedback') === 'none');

        $this->surveys = NpsSurvey::orderByDesc('sent_at')->paginate(20);

        return view('customer-feedback::surveys.nps.index', $this->data);
    }

    /**
     * Delete a survey record.
     */
    public function destroy(NpsSurvey $survey)
    {
        abort_403(user()->permission('manage_surveys') !== 'all');

        $survey->delete();

        return Reply::success(__('messages.recordDeleted'));
    }

    /**
     * Toggle the `is_public` (testimonial) flag.
     */
    public function togglePublic(NpsSurvey $survey)
    {
        abort_403(user()->permission('publish_testimonials') !== 'all');

        $survey->update(['is_public' => !$survey->is_public]);

        return Reply::success(__('messages.updateSuccess'));
    }
}
