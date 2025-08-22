@extends('user.master')

@section('title')
    Thêm mới địa chỉ
@endsection

@section('content')
    <h2 class="text-center">Thêm mới địa chỉ</h2>

    <form action="{{ route('address.store') }}" method="POST">
        @csrf
        <div>
            <label for="recipient_name" class="form-label">Tên người nhận</label>
            <input type="text" class="form-control mb-3" name="recipient_name" id="recipient_name" value="{{old('recipient_name')}}" required>
        </div>
        <div>
            <label for="ship_address">Địa chỉ:</label>
            <input type="text" class="form-control mb-3" id="ship_address" name="ship_address" value="{{old('ship_address')}}" required>
        </div>
        <div>
            <label for="phone_number">Số điện thoại:</label>
            <input type="text" class="form-control mb-3" id="phone_number" name="phone_number" value="{{old('phone_number')}}" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Thêm địa chỉ</button>
            <a href="{{ route('address.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
