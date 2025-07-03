@extends('Layout.Layout')

@section('title')
    Thêm mới phiếu giảm giá
@endsection

@section('content_admin')
 

    <h1 class="text-center mt-5">Thêm mới voucher</h1>

    <form method="POST" action="{{ route('vouchers.store') }}" enctype="multipart/form-data" class="container">
        @csrf

        <div class="mb-3">
            <label for="code" class="col-2 col-form-label">Mã giảm giá</label>
            <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}">
            <button type="button" class="btn btn-secondary mt-2" style="width: 300px" id="generateCodeBtn">Tạo mã ngẫu nhiên</button>
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="title" class="col-2 col-form-label">Tiêu đề</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="col-2 col-form-label">Mô tả</label>
            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="start_date_time" class="col-2 col-form-label">Ngày bắt đầu</label>
            <input type="datetime-local" class="form-control" name="start_date_time" id="start_date_time" value="{{ old('start_date_time') }}">
            @error('start_date_time')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date_time" class="col-2 col-form-label">Ngày kết thúc</label>
            <input type="datetime-local" class="form-control" name="end_date_time" id="end_date_time" value="{{ old('end_date_time') }}">
            @error('end_date_time')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="discount" class="col-2 col-form-label">Giá trị giảm giá</label>
            <input type="number" step="0.01" class="form-control" name="discount" id="discount" value="{{ old('discount') }}">
            @error('discount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="min_order_amount" class="col-2 col-form-label">Giá trị đơn hàng tối thiểu</label>
            <input type="number" step="0.01" class="form-control" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount') }}">
            @error('min_order_amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="max_usage" class="col-2 col-form-label">Số lần sử dụng tối đa</label>
            <input type="number" class="form-control" name="max_usage" id="max_usage" value="{{ old('max_usage') }}">
            @error('max_usage')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="is_active" class="col-2 col-form-label">Trạng thái:</label>
            <select name="is_active" class="form-control mt-2" id="is_active">
                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Không hoạt động</option>
            </select>
            @error('is_active')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-3 mb-3 text-center">
            <button type="submit" class="btn btn-outline-success">Tạo Voucher</button>
            <a href="{{ route('vouchers.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        </div>
    </form>

    <script>
        document.getElementById('generateCodeBtn').addEventListener('click', function() {
            const codeLength = 5;
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let randomCode = '';
            for (let i = 0; i < codeLength; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                randomCode += characters.charAt(randomIndex);
            }
            document.getElementById('code').value = randomCode;
        });
    </script>
@endsection
