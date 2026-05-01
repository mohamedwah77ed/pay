@extends('layouts.main')

@section('content')

<h3>Add Product</h3>

<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control">
    </div>

   

    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="form-control">
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
    </div>

    <button class="btn btn-success">Save</button>
</form>

@endsection