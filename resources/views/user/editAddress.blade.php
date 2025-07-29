@extends('user.master')

@section('title')
    Sửa địa chỉ
@endsection

@section('content')
    <h1 class="text-center">Sửa địa chỉ</h1>


    <form action="{{ route('address.update', $address->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="recipient_name" class="form-label">Tên người nhận</label>
            <input type="text" name="recipient_name" id="recipient_name" class="form-control mb-3"
                value="{{ old('recipient_name', $address->recipient_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="ship_address" class="form-label">Địa chỉ</label>
            <input type="text" name="ship_address" id="ship_address" class="form-control mb-3"
                value="{{ old('ship_address', $address->ship_address) }}" required>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Số điện thoại</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control mb-3"
                value="{{ old('phone_number', $address->phone_number) }}" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_default" id="is_default" class="form-check-input mb-3"
                {{ $address->is_default ? 'checked' : '' }}>
            <label for="is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-warning">Cập nhật</button>
            <a href="{{ route('address.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
