@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <h3 class="mb-4">Login</h3>

        {{-- ✅ اضيف الـ Errors هنا --}}
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email">
                {{-- ✅ أو كده تحت كل Field --}}
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control" name="password">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button class="btn btn-primary w-100">Login</button>
        </form>

    </div>
</div>
@endsection