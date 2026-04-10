<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="filter" class="form-control">
            <option value="">{{ __('All') }}</option>
            <option value="unassigned" {{ request('filter')==='unassigned'?'selected':'' }}>{{ __('bookingmodule::assignment.labels.unassigned') }}</option>
            <option value="mine" {{ request('filter')==='mine'?'selected':'' }}>{{ __('My Appointments') }}</option>
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-primary">{{ __('Filter') }}</button>
    </div>
</form>
