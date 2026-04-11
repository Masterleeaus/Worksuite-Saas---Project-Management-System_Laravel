{{-- CampaignCanvas: Properties Panel --}}
<div id="cc-propsbar" class="cc-propsbar p-3 bg-white border-left" style="width:220px;min-height:100vh;">
    <h6 class="text-uppercase text-muted small mb-3">Properties</h6>

    <div id="cc-props-empty" class="text-muted small">Select an element to edit its properties.</div>

    <div id="cc-props-panel" style="display:none;">
        {{-- Position --}}
        <label class="small font-weight-bold">Position</label>
        <div class="input-group input-group-sm mb-2">
            <div class="input-group-prepend"><span class="input-group-text">X</span></div>
            <input type="number" id="cc-prop-x" class="form-control" onchange="ccApplyProp('x', this.value)">
        </div>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend"><span class="input-group-text">Y</span></div>
            <input type="number" id="cc-prop-y" class="form-control" onchange="ccApplyProp('y', this.value)">
        </div>

        {{-- Size --}}
        <label class="small font-weight-bold">Size</label>
        <div class="input-group input-group-sm mb-2">
            <div class="input-group-prepend"><span class="input-group-text">W</span></div>
            <input type="number" id="cc-prop-w" class="form-control" onchange="ccApplyProp('w', this.value)">
        </div>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend"><span class="input-group-text">H</span></div>
            <input type="number" id="cc-prop-h" class="form-control" onchange="ccApplyProp('h', this.value)">
        </div>

        {{-- Colour --}}
        <label class="small font-weight-bold">Fill Colour</label>
        <input type="color" id="cc-prop-color" class="form-control form-control-sm mb-3"
               onchange="ccApplyProp('color', this.value)">

        {{-- Font (text only) --}}
        <div id="cc-props-text">
            <label class="small font-weight-bold">Font Size</label>
            <input type="number" id="cc-prop-fontsize" class="form-control form-control-sm mb-2"
                   onchange="ccApplyProp('fontSize', this.value)">

            <label class="small font-weight-bold">Text Content</label>
            <textarea id="cc-prop-text" class="form-control form-control-sm mb-3" rows="3"
                      onchange="ccApplyProp('text', this.value)"></textarea>
        </div>

        {{-- Opacity --}}
        <label class="small font-weight-bold">Opacity <span id="cc-prop-opacity-val">100</span>%</label>
        <input type="range" id="cc-prop-opacity" class="custom-range mb-3" min="0" max="100" value="100"
               oninput="document.getElementById('cc-prop-opacity-val').textContent=this.value; ccApplyProp('opacity', this.value/100)">

        {{-- Delete element --}}
        <button class="btn btn-sm btn-outline-danger btn-block mt-2" onclick="ccDeleteSelected()">
            <i class="fa fa-trash"></i> Delete Element
        </button>
    </div>
</div>
