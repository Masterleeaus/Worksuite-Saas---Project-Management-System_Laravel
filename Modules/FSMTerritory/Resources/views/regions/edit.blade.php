<h1>Edit FSM Region</h1>
<form method="POST" action="{{ route('fsmterritory.regions.update', $region->id) }}">
    @csrf
    <input name="name" value="{{ $region->name ?? '' }}" />
    <textarea name="description">{{ $region->description ?? '' }}</textarea>
    <button type="submit">Update</button>
</form>
