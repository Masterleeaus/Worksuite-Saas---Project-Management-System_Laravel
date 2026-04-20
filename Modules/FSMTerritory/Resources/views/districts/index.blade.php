@php($rows = $districts ?? collect())
<h1>FSM Districts</h1>
<a href="{{ route('fsmterritory.districts.create') }}">Create District</a>
<ul>
    @foreach($rows as $row)
        <li>{{ $row->name }}</li>
    @endforeach
</ul>
