@php($orders = $orders ?? collect())
<h1>Create FSM Project Link</h1>
<form method="POST" action="{{ route('fsmproject.store') }}">
    @csrf
    <label>Order</label>
    <select name="order_id" required>
        <option value="">Select order</option>
        @foreach($orders as $item)
            <option value="{{ $item->id }}">{{ $item->name ?? ('Order #' . $item->id) }}</option>
        @endforeach
    </select>

    <label>Project ID</label>
    <input name="project_id" type="number" required>

    <label>Task ID</label>
    <input name="task_id" type="number">

    <button type="submit">Save</button>
</form>
