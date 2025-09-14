@extends('Layout.Layout')

@section('tiltle')
    Cập nhật tài khoản
@endsection

@section('content_admin')

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline mt-4">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a href="{{route('admin.edit')}}" class="nav-link">Hồ sơ của tôi</a></li>
                    <li class="nav-item"><a href="{{route('admin.changepass.form')}}" class="nav-link">Cập nhật mật khẩu</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h3 class="text-center mb-4">Cập nhật tài khoản</h3>
                <form action="{{ route('admin.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="form-group mb-3">
                        <label for="fullname">Họ và tên</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" value="{{ old('fullname', Auth::user()->fullname) }}">
                        @error('fullname')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="birth_day">Sinh nhật</label>
                        <input type="date" class="form-control" name="birth_day" id="birth_day" value="{{ old('birth_day', Auth::user()->birth_day) }}">
                        @error('birth_day')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}">
                        @error('phone')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="{{ old('email', Auth::user()->email) }}" disabled>
                        <input type="hidden" name="email" value="{{ old('email', Auth::user()->email) }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="address">Địa chỉ</label>
                        <input type="text" class="form-control" name="address" id="address" value="{{ old('address', Auth::user()->address) }}">
                        @error('address')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="avatar" class="mt-3">Ảnh đại diện</label><br>
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" width="100px" class="mb-2 mt-2 rounded">
                        <input type="file" class="form-control mt-2" name="avatar" id="avatar">
                    </div>
                    <div class="text-center m-3">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="{{route('user.dashboard')}}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
