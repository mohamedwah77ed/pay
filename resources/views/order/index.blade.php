@extends('layouts.main')

@section('content')

<div class="container py-5">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h3 class="mb-4">إتمام الطلب</h3>

    <form action="{{ route('order.store') }}" method="POST">
        @csrf

        <div class="row g-4">

            {{-- بيانات الشحن --}}
            <div class="col-md-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">بيانات الشحن</h5>
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">الاسم الأول</label>
                                <input type="text" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">الاسم الأخير</label>
                                <input type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', auth()->user()->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">العنوان الأول</label>
                                <input type="text" name="address1"
                                    class="form-control @error('address1') is-invalid @enderror"
                                    value="{{ old('address1') }}">
                                @error('address1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">العنوان الثاني <span class="text-muted">(اختياري)</span></label>
                                <input type="text" name="address2"
                                    class="form-control"
                                    value="{{ old('address2') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">الدولة</label>
                                <input type="text" name="country"
                                    class="form-control @error('country') is-invalid @enderror"
                                    value="{{ old('country') }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">الرمز البريدي <span class="text-muted">(اختياري)</span></label>
                                <input type="text" name="post_code"
                                    class="form-control"
                                    value="{{ old('post_code') }}">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ملخص الطلب --}}
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">ملخص الطلب</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">

                            @php $total = 0 @endphp

                            @foreach($cartItems as $item)
                                @php $total += $item->amount @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-semibold">{{ $item->product->title }}</span>
                                        <small class="text-muted d-block">الكمية: {{ $item->quantity }}</small>
                                    </div>
                                    <span>${{ number_format($item->amount, 2) }}</span>
                                </li>
                            @endforeach

                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">المجموع</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </li>

                            @if(session('coupon'))
                                <li class="list-group-item d-flex justify-content-between text-success">
                                    <span>خصم الكوبون</span>
                                    <span>- ${{ number_format(session('coupon')['value'], 2) }}</span>
                                </li>
                            @endif

                            <li class="list-group-item d-flex justify-content-between fw-bold">
                                <span>الإجمالي</span>
                                <span class="text-primary">
                                    ${{ number_format($total - (session('coupon') ? session('coupon')['value'] : 0), 2) }}
                                </span>
                            </li>

                        </ul>
                    </div>
                </div>

                {{-- كوبون الخصم --}}
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <label class="form-label">كوبون الخصم <span class="text-muted">(اختياري)</span></label>
                        <div class="input-group">
                            <input type="text" name="coupon" class="form-control" placeholder="أدخل الكوبون">
                            <button type="button" class="btn btn-outline-secondary">تطبيق</button>
                        </div>
                    </div>
                </div>

                {{-- زرار التأكيد --}}
                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        تأكيد الطلب
                    </button>
                </div>

                <div class="text-center mt-2">
                    <a href="{{ route('cart.index') }}" class="text-muted small">
                        رجوع للسلة
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection