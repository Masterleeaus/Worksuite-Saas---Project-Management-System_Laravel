@php($rows = $territories ?? collect())
<h1>FSM Territories</h1>
<a href="{{ route('fsmterritory.territories.create') }}">Create Territory</a>
<ul>
    @foreach($rows as $row)
        <li>{{ $row->name }}</li>
    @endforeach
</ul>
