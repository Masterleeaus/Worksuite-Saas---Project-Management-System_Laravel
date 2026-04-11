<?php
namespace Modules\CampaignCanvas\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Modules\CampaignCanvas\Entities\Document;

class EditorController extends AccountBaseController
{
    public function create()
    {
        $this->pageTitle = __('campaigncanvas::campaigncanvas.editor_new');
        return view('campaigncanvas::editor.canvas', ['document' => null]);
    }

    public function edit(string $uuid)
    {
        $document = Document::where('uuid', $uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->pageTitle = $document->name;
        return view('campaigncanvas::editor.canvas', compact('document'));
    }
}
