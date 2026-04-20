<h1>Edit FSM Territory</h1>
<form method="POST" action="{{ route('fsmterritory.territories.update', $territory->id) }}">
    @csrf
    <input name="name" value="{{ $territory->name ?? '' }}" />
    <textarea name="description">{{ $territory->description ?? '' }}</textarea>
    <button type="submit">Update</button>
</form>
