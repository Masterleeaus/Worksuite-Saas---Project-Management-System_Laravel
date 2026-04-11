{{-- CampaignCanvas: Toolbar --}}
<div id="cc-toolbar" class="cc-toolbar d-flex align-items-center px-2 py-1 bg-dark">
    {{-- Add elements --}}
    <button class="btn btn-sm btn-outline-light mr-1" onclick="ccAddText()" title="Add Text"><i class="fa fa-font"></i></button>
    <button class="btn btn-sm btn-outline-light mr-1" onclick="ccAddRect()" title="Add Rectangle"><i class="fa fa-square-o"></i></button>
    <button class="btn btn-sm btn-outline-light mr-1" onclick="ccAddCircle()" title="Add Circle"><i class="fa fa-circle-o"></i></button>
    <button class="btn btn-sm btn-outline-light mr-1" id="cc-upload-btn" title="Upload Image"><i class="fa fa-upload"></i></button>
    <div class="mx-2 border-left border-secondary" style="height:24px;"></div>
    {{-- Undo / Redo --}}
    <button class="btn btn-sm btn-outline-light mr-1" onclick="ccUndo()" title="{{ __('campaigncanvas::campaigncanvas.undo') }}"><i class="fa fa-undo"></i></button>
    <button class="btn btn-sm btn-outline-light mr-1" onclick="ccRedo()" title="{{ __('campaigncanvas::campaigncanvas.redo') }}"><i class="fa fa-repeat"></i></button>
    <div class="mx-2 border-left border-secondary" style="height:24px;"></div>
    {{-- Layer order --}}
    <button class="btn btn-sm btn-outline-light mr-1" onclick="ccLayerUp()"   title="{{ __('campaigncanvas::campaigncanvas.layer_up') }}"><i class="fa fa-arrow-up"></i></button>
    <button class="btn btn-sm btn-outline-light mr-1" onclick="ccLayerDown()" title="{{ __('campaigncanvas::campaigncanvas.layer_down') }}"><i class="fa fa-arrow-down"></i></button>
    <div class="mx-2 border-left border-secondary" style="height:24px;"></div>
    {{-- Save status --}}
    <span id="cc-save-status" class="text-light small mr-2">{{ __('campaigncanvas::campaigncanvas.saved') }}</span>
    <button class="btn btn-sm btn-success" onclick="ccSave()"><i class="fa fa-save"></i> {{ __('campaigncanvas::campaigncanvas.save') }}</button>
    {{-- hidden file input for uploads --}}
    <input type="file" id="cc-file-input" accept="image/jpeg,image/png,image/webp,image/svg+xml" class="d-none">
</div>
