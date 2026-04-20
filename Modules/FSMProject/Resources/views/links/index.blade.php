@php($rows = $orders ?? collect())
@php($filter = $filter ?? [])
<h1>FSM Project Links</h1>
<a href="{{ route('fsmproject.create') }}">Create Link</a>

<form method="GET" action="{{ route('fsmproject.index') }}">
    <input type="text" name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Search order name">
    <button type="submit">Filter</button>
</form>

<table>
    <thead>
    <tr>
        <th>Order</th>
        <th>Project</th>
        <th>Task</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>{{ $row->name ?? ('Order #' . $row->id) }}</td>
            <td>{{ $row->project_id ?? '-' }}</td>
            <td>{{ $row->task_id ?? '-' }}</td>
            <td>
                <a href="{{ route('fsmproject.edit', $row->id) }}">Edit</a>
                <form method="POST" action="{{ route('fsmproject.destroy', $row->id) }}">
                    @csrf
                    <button type="submit">Unlink</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
