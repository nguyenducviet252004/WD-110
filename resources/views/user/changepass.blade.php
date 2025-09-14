@extends('user.master')

@section('title')
    Đổi mật khẩu
@endsection

@section('content')
    <h2 class="text-center">Đổi mật khẩu</h2>

    <form action="{{ route('user.password.change') }}" method="POST" novalidate>
        @csrf

        <div>
            <label for="current_password">Mật khẩu hiện tại</label>
            <input type="password" class="form-control mb-3" name="current_password" id="current_password" required value="{{old('current_password')}}">
            @error('current_password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="new_password">Mật khẩu mới</label>
            <input type="password" class="form-control mb-3" name="new_password" id="new_password" required>
            @error('new_password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control mb-5" name="new_password_confirmation" id="new_password_confirmation" required>
            @error('new_password_confirmation')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="text-center"> 
            <button type="submit" class="btn btn-success text-center">Đổi mật khẩu</button>
            <a href="{{route('user.dashboard')}}" class="btn btn-secondary">Quay lai</a>
        </div>
    </form>
@endsection
