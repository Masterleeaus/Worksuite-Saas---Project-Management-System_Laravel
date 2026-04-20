@php($orders = $orders ?? collect())
@php($projectTasks = $projectTasks ?? collect())
<h1>Edit FSM Project Link</h1>
<form method="POST" action="{{ route('fsmproject.update', $order->id) }}">
    @csrf
    <p>Order: {{ $order->name ?? ('Order #' . $order->id) }}</p>

    <label>Project ID</label>
    <input name="project_id" type="number" value="{{ $order->project_id ?? '' }}" required>

    @if(!empty($order->project_id))
        <label>Task</label>
        <select name="task_id">
            <option value="">Select task</option>
            @foreach($projectTasks as $task)
                <option value="{{ $task->id }}" @selected((int) ($order->task_id ?? 0) === (int) $task->id)>
                    {{ $task->heading ?? ('Task #' . $task->id) }}
                </option>
            @endforeach
        </select>
    @else
        <input type="hidden" name="task_id" value="">
    @endif

    <button type="submit">Update</button>
</form>
