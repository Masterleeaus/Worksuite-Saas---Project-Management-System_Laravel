@php($orders = $orders ?? collect())
<h1>Edit FSM Project Link</h1>
<form method="POST" action="{{ route('fsmproject.update', $order->id) }}">
    @csrf
    <p>Order: {{ $order->name ?? ('Order #' . $order->id) }}</p>

    <label>Project ID</label>
    <input name="project_id" type="number" value="{{ $order->project_id ?? '' }}" required>

    <label>Task ID</label>
    <input name="task_id" type="number" value="{{ $order->task_id ?? '' }}">

    <button type="submit">Update</button>
</form>
