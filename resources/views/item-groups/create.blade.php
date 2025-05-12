<h1>Add Item Group</h1>
<form action="{{ route('item-groups.store') }}" method="POST">
    @csrf
    <div>
        <label for="item_group_name">Item Group Name</label>
        <input type="text" name="item_group_name" required>
    </div>
    <button type="submit">Create</button>
</form>
