<?php
namespace Modules\CampaignCanvas\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Modules\CampaignCanvas\Entities\Document;

class GalleryController extends AccountBaseController
{
    public function index()
    {
        $this->pageTitle = __('campaigncanvas::campaigncanvas.gallery_title');

        $documents = Document::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('campaigncanvas::gallery.home', compact('documents'));
    }
}
