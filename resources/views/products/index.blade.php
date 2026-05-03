@extends('layouts.main')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Products</h3>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Price</th>
                <th>Image</th>
                <th> Category</th>
                
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->title }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>
                    @if($product->image)
                        <img src="{{ asset('uploads/'.$product->image) }}" width="70" alt="{{ $product->title }}">
                    @else
                        <span class="text-muted">No image</span>
                    @endif
                </td>
                <td>{{ $product->cat_info?->title ?? 'Uncategorized' }}</td>
                <td class="text-nowrap">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm me-1">Edit</a>

                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>

                    <a href="{{ route('cart.add', $product->id) }}" class="btn btn-success btn-sm ms-1">Add to Cart</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection