<h1>Edit FSM Branch</h1>
<form method="POST" action="{{ route('fsmterritory.branches.update', $branch->id) }}">
    @csrf
    <input name="name" value="{{ $branch->name ?? '' }}" />
    <textarea name="description">{{ $branch->description ?? '' }}</textarea>
    <button type="submit">Update</button>
</form>
