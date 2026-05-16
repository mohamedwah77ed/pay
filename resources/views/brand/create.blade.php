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

<form method="POST" action="{{ route('brand.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Title -->
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title"
               class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title') }}" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Status -->
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
            <option value="active"   {{ old('status') == 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-success">Save</button>
    <a href="{{ route('brand.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@endsection