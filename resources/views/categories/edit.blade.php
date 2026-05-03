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

<h3>Edit Category</h3>

<form method="POST" action="{{ route('categories.update', $category->id) }}">
    @csrf
    @method('PUT')

    <!-- Title -->
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title"
               class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $category->title) }}" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Summary -->
    <div class="mb-3">
        <label>Summary</label>
        <textarea name="summary"
                  class="form-control @error('summary') is-invalid @enderror">{{ old('summary', $category->summary) }}</textarea>
        @error('summary') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Photo -->
    <div class="mb-3">
        <label>Photo URL</label>
        <input type="text" name="photo"
               class="form-control @error('photo') is-invalid @enderror"
               value="{{ old('photo', $category->photo) }}">
        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Status -->
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
            <option value="active"   {{ old('status', $category->status) == 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Is Parent -->
    <div class="mb-3 form-check">
        <input type="checkbox" name="is_parent" value="1"
               class="form-check-input" id="is_parent"
               {{ old('is_parent', $category->is_parent) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_parent">Is Parent Category</label>
    </div>

    <button class="btn btn-success">Update</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@endsection