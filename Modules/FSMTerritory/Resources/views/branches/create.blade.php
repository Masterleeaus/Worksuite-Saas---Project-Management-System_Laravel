<h1>Create FSM Branch</h1>
<form method="POST" action="{{ route('fsmterritory.branches.store') }}">
    @csrf
    <input name="name" placeholder="Branch name" />
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit">Save</button>
</form>
