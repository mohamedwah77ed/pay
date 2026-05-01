@extends('layouts.main')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Products</h3>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>${{ $product->price }}</td>
            <td>
                @if($product->image)
                    <img src="{{ asset('uploads/'.$product->image) }}" width="70">
                @endif
            </td>
            <td>{{ $product->category->name }}</td>
            <td>

                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                    <a href="{{ route('cart.add', $product->id) }}" class="btn btn-success">
    Add to Cart
</a>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection