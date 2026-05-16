@extends('layouts.main')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Brands</h3>
    <a href="{{ route('brand.create') }}" class="btn btn-primary">
        + Add Brand
    </a>
</div>

<div class="card">
    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th width="200">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>

                        <td class="fw-bold">
                            {{ $brand->title }}
                        </td>

                        <td>
                            @if($brand->image)
                                <img src="{{ asset('uploads/' . $brand->image) }}"
                                     width="60"
                                     height="60"
                                     class="rounded"
                                     style="object-fit: cover;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('brand.edit', $brand->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('brand.destroy', $brand->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No brands found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection