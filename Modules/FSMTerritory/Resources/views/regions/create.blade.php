<h1>Create FSM Region</h1>
<form method="POST" action="{{ route('fsmterritory.regions.store') }}">
    @csrf
    <input name="name" placeholder="Region name" />
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit">Save</button>
</form>
