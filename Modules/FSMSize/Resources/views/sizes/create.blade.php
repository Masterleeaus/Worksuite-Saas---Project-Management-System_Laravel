@php($parents = $parents ?? collect())
<h1>Create FSM Size</h1>
<form method="POST" action="{{ route('fsmsize.store') }}">
    @csrf
    <label>Name</label>
    <input name="name" required>

    <label>Unit of Measure</label>
    <input name="unit_of_measure">

    <label>Parent</label>
    <select name="parent_id">
        <option value="">None</option>
        @foreach($parents as $parent)
            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
        @endforeach
    </select>

    <label>Order Size</label>
    <input type="checkbox" name="is_order_size" value="1">

    <button type="submit">Save</button>
</form>
