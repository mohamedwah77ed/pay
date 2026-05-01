@extends('layouts.main')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Categories</h3>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Add Category</a>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
          
            <td>

                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection