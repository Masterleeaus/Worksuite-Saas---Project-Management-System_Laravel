@php($rows = $branches ?? collect())
<h1>FSM Branches</h1>
<a href="{{ route('fsmterritory.branches.create') }}">Create Branch</a>
<ul>
    @foreach($rows as $row)
        <li>{{ $row->name }}</li>
    @endforeach
</ul>
