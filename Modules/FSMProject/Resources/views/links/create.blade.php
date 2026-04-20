@php($orders = $orders ?? collect())
@php($tasksByOrder = $tasksByOrder ?? [])
<h1>Create FSM Project Link</h1>
<form method="POST" action="{{ route('fsmproject.store') }}">
    @csrf
    <label>Order</label>
    <select name="order_id" id="order_id" required>
        <option value="">Select order</option>
        @foreach($orders as $item)
            <option value="{{ $item->id }}">{{ $item->name ?? ('Order #' . $item->id) }}</option>
        @endforeach
    </select>

    <label>Project ID</label>
    <input name="project_id" type="number" required>

    <div id="task_link_section" style="display: none;">
        <label>Task</label>
        <select name="task_id" id="task_id">
            <option value="">Select task</option>
        </select>
    </div>

    <button type="submit">Save</button>
</form>

<script>
    (function () {
        const tasksByOrder = @json($tasksByOrder);
        const orderSelect = document.getElementById('order_id');
        const taskSection = document.getElementById('task_link_section');
        const taskSelect = document.getElementById('task_id');

        function renderTasks() {
            const orderId = orderSelect.value;
            const tasks = tasksByOrder[orderId] || [];

            taskSelect.innerHTML = '<option value="">Select task</option>';

            tasks.forEach((task) => {
                const option = document.createElement('option');
                option.value = task.id;
                option.textContent = task.heading || `Task #${task.id}`;
                taskSelect.appendChild(option);
            });

            taskSection.style.display = tasks.length ? '' : 'none';
        }

        orderSelect.addEventListener('change', renderTasks);
        renderTasks();
    })();
</script>
