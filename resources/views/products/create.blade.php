@extends('layouts.main')

@section('content')

{{-- عرض الأخطاء --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h3>Add Product</h3>

<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Title -->
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title"
               class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title') }}" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Price -->
    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="price"
               class="form-control @error('price') is-invalid @enderror"
               value="{{ old('price') }}" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
    <label>Summary</label>
    <textarea name="summary" class="form-control" required>{{ old('summary') }}</textarea>
</div>


    <!-- Description -->
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Category -->
    <div class="mb-3">
        <label>Category</label>
        <select name="cat_id" class="form-control @error('cat_id') is-invalid @enderror" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('cat_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->title }}
                </option>
            @endforeach
        </select>
        @error('cat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Image -->
    <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image"
               class="form-control @error('image') is-invalid @enderror"
               accept="image/jpg, image/jpeg, image/png">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-success">Save</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@endsection