<h1>Add UOM</h1>
<form method="POST" action="{{ route('uoms.store') }}">
  @csrf
  <label>UOM Name: <input name="uom_name"></label><br>
  <label>Must be whole number:
    <input type="checkbox" name="must_be_whole_number" value="1">
  </label><br>
  <button type="submit">Save</button>
</form>
