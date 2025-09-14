@extends('Layout.Layout')

@section('tiltle')
    Đổi mật khẩu
@endsection

@section('content_admin')


<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline mt-4">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a href="{{route('admin.edit')}}" class="nav-link">Hồ sơ của tôi</a></li>
                    <li class="nav-item"><a href="{{route('admin.changepass.form')}}" class="nav-link active">Cập nhật mật khẩu</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h3 class="text-center mb-4">Đổi mật khẩu</h3>
                <form action="{{ route('admin.password.change') }}" method="POST" novalidate>
                    @csrf
                    <div class="form-group mb-3">
                        <label for="current_password">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" name="current_password" id="current_password" required value="{{old('current_password')}}">
                        @error('current_password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="new_password">Mật khẩu mới</label>
                        <input type="password" class="form-control" name="new_password" id="new_password" required>
                        @error('new_password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-4">
                        <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
                        @error('new_password_confirmation')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><i class="fas fa-key"></i> Đổi mật khẩu</button>
                        <a href="{{route('admin.dashboard')}}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
