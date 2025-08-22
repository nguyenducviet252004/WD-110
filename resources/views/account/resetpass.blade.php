@extends('account.master')

@section('title')
    Khôi phục tài khoản
@endsection

@section('content')
    <form action="{{ route('password.update') }}" method="POST" class="container bg-light mt-5"
        style="width: 500px; height: 350px;">
        <h1 class="text-center mb-5 mt-5">Cập nhật mật khẩu mới</h1>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="email" name="email" class="form-control mt-3 mb-3" value="{{ old('email', $email) }}" hidden>

        <label for="password">Mật khẩu mới</label>
        <input type="password" name="password" class="form-control mt-3 mb-3">

        <label for="password_confirmation">xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control mt-3 mb-3">
        
        <div class="text-center">
            <button type="submit" class="btn btn-success">Cập nhật mật khẩu</button>
        </div>
    </form>
@endsection
