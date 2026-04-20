<h1>Create FSM Territory</h1>
<form method="POST" action="{{ route('fsmterritory.territories.store') }}">
    @csrf
    <input name="name" placeholder="Territory name" />
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit">Save</button>
</form>
