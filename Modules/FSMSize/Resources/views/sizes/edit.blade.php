@php($parents = $parents ?? collect())
<h1>Edit FSM Size</h1>
<form method="POST" action="{{ route('fsmsize.update', $size->id) }}">
    @csrf
    <label>Name</label>
    <input name="name" value="{{ $size->name ?? '' }}" required>

    <label>Unit of Measure</label>
    <input name="unit_of_measure" value="{{ $size->unit_of_measure ?? '' }}">

    <label>Parent</label>
    <select name="parent_id">
        <option value="">None</option>
        @foreach($parents as $parent)
            <option value="{{ $parent->id }}" @selected((int) ($size->parent_id ?? 0) === (int) $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>

    <label>Order Size</label>
    <input type="checkbox" name="is_order_size" value="1" @checked((bool) ($size->is_order_size ?? false))>

    <button type="submit">Update</button>
</form>
