@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Unit of Measures</h1>
    <a href="{{ route('uoms.create') }}" class="btn btn-primary mb-3">Add UOM</a>

    <ul class="list-group">
        @foreach ($uoms as $uom)
            <li class="list-group-item">{{ $uom['name'] }}</li>
        @endforeach
    </ul>
</div>
@endsection
