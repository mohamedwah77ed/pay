@extends('layouts.main')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">سلة المشتريات</h2>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($products) && $products->count() > 0)

        <div class="row g-4">

            {{-- جدول السلة --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <table class="table table-borderless mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>السعر</th>
                                    <th>الكمية</th>
                                    <th>الإجمالي</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $item)
                                <tr class="align-middle">
                                    <td>{{ $item->product->title }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>
                                        {{-- تقليل --}}
                                        <form action="{{ route('cart.decrease') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                            <button type="submit" class="btn btn-warning btn-sm">-</button>
                                        </form>

                                        <span class="mx-2">{{ $item->quantity }}</span>

                                        {{-- زيادة --}}
                                        <form action="{{ route('cart.increase') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                            <button type="submit" class="btn btn-success btn-sm">+</button>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item->amount, 2) }}</td>
                                    <td>
                                        {{-- حذف --}}
                                        <form action="{{ route('cart-delete') }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ملخص الطلب --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">ملخص الطلب</h5>
                    </div>
                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">عدد المنتجات</span>
                            <span>{{ $products->sum('quantity') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">الإجمالي</span>
                            <span class="fw-bold text-primary">
                                ${{ number_format($products->sum('amount'), 2) }}
                            </span>
                        </div>

                        <hr>

                        {{-- زرار الـ Checkout --}}
                        <div class="d-grid">
                            <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">
                                إتمام عملية الشراء
                            </a>
                        </div>

                        <div class="text-center mt-2">
                            <a href="{{ route('products.index') }}" class="text-muted small">
                                متابعة التسوق
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    @else
        <div class="text-center py-5">
            <p class="text-muted fs-5">السلة فاضية</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                تسوق الآن
            </a>
        </div>
    @endif

</div>

@endsection