@php($rows = $regions ?? collect())
<h1>FSM Regions</h1>
<a href="{{ route('fsmterritory.regions.create') }}">Create Region</a>
<ul>
    @foreach($rows as $row)
        <li>{{ $row->name }}</li>
    @endforeach
</ul>
