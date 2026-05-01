@extends('layouts.main')

@section('content')

<h3>Add Category</h3>

<form method="POST" action="{{ route('categories.store') }}">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control">
    </div>

   

    <button class="btn btn-success">Save</button>
</form>

@endsection