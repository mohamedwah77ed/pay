@extends('layouts.main')

@section('content')

{{-- Success / Error Messages --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between mb-3">
    <h3>Categories</h3>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Add Category</a>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Is Parent</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->title }}</td>
            <td>{{ $category->slug }}</td>
            <td>
                <span class="badge {{ $category->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($category->status) }}
                </span>
            </td>
            <td>{{ $category->is_parent ? 'Yes' : 'No' }}</td>
            <td>
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No categories found</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection