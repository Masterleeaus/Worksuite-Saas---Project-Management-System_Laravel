<h1>Edit FSM District</h1>
<form method="POST" action="{{ route('fsmterritory.districts.update', $district->id) }}">
    @csrf
    <input name="name" value="{{ $district->name ?? '' }}" />
    <textarea name="description">{{ $district->description ?? '' }}</textarea>
    <button type="submit">Update</button>
</form>
