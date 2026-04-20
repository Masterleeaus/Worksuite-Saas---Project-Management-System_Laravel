@php($rows = $sizes ?? collect())
<h1>FSM Sizes</h1>
<a href="{{ route('fsmsize.create') }}">Create Size</a>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Unit</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>{{ $row->name }}</td>
            <td>{{ $row->unit_of_measure ?? '-' }}</td>
            <td>
                <a href="{{ route('fsmsize.edit', $row->id) }}">Edit</a>
                <form method="POST" action="{{ route('fsmsize.destroy', $row->id) }}">
                    @csrf
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
