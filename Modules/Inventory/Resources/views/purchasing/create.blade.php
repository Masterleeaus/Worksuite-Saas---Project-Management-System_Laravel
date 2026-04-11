@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header"><h4 class="mb-0">Create Purchase Order</h4></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventory.purchasing.store') }}" id="po-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Supplier <span class="text-danger">*</span></label>
                                    <select name="supplier_id" class="form-control" required>
                                        <option value="">— Select Supplier —</option>
                                        @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Reference</label>
                                    <input type="text" name="reference" class="form-control" value="{{ old('reference') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Currency</label>
                                    <input type="text" name="currency" class="form-control" value="{{ old('currency', 'AUD') }}" maxlength="3">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Expected Date</label>
                                    <input type="date" name="expected_date" class="form-control" value="{{ old('expected_date') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-3">Items</h5>
                        <div id="po-items">
                            <div class="row po-item-row mb-2">
                                <div class="col-md-5">
                                    <select name="items[0][item_id]" class="form-control" required>
                                        <option value="">— Select Item —</option>
                                        @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[0][qty_ordered]" step="0.0001" min="0.0001"
                                           class="form-control" placeholder="Qty" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[0][unit_cost]" step="0.01" min="0"
                                           class="form-control" placeholder="Unit Cost" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-item" class="btn btn-sm btn-outline-secondary mb-3">
                            <i class="fa fa-plus"></i> Add Item
                        </button>

                        <div class="d-flex justify-content-between mt-2">
                            <a href="{{ route('inventory.purchasing.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Purchase Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var idx = 1;
    var items = @json($items->map(fn($i) => ['id' => $i->id, 'name' => $i->name]));
    document.getElementById('add-item').addEventListener('click', function() {
        var opts = items.map(function(i){ return '<option value="'+i.id+'">'+i.name+'</option>'; }).join('');
        var html = '<div class="row po-item-row mb-2">'
            + '<div class="col-md-5"><select name="items['+idx+'][item_id]" class="form-control" required><option value="">— Item —</option>'+opts+'</select></div>'
            + '<div class="col-md-3"><input type="number" name="items['+idx+'][qty_ordered]" step="0.0001" min="0.0001" class="form-control" placeholder="Qty" required></div>'
            + '<div class="col-md-3"><input type="number" name="items['+idx+'][unit_cost]" step="0.01" min="0" class="form-control" placeholder="Unit Cost" required></div>'
            + '<div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fa fa-times"></i></button></div>'
            + '</div>';
        var container = document.getElementById('po-items');
        container.insertAdjacentHTML('beforeend', html);
        container.lastElementChild.querySelector('.remove-row').addEventListener('click', function(){ this.closest('.po-item-row').remove(); });
        idx++;
    });
})();
</script>
@endsection
