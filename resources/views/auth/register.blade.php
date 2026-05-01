@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <h3 class="mb-4">Login</h3>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control" name="password">
            </div>

            <button class="btn btn-primary w-100">Login</button>
        </form>

    </div>
</div>
@endsection