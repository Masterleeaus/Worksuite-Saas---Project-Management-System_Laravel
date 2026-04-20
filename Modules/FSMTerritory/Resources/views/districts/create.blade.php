<h1>Create FSM District</h1>
<form method="POST" action="{{ route('fsmterritory.districts.store') }}">
    @csrf
    <input name="name" placeholder="District name" />
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit">Save</button>
</form>
