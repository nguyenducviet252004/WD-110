@extends('Layout.Layout')

@section('title')
    Thêm mới phiếu giảm giá
@endsection

@section('content_admin')
    <h1 class="text-center mt-5">Thêm mới voucher</h1>

    <form method="POST" action="{{ route('admin.vouchers.store') }}" class="container">
        @csrf

        <div class="mb-3">
            <label for="code" class="form-label">Mã giảm giá</label>
            <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}">
            <button type="button" class="btn btn-secondary mt-2" id="generateCodeBtn">Tạo mã ngẫu nhiên</button>
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_day" class="form-label">Ngày bắt đầu</label>
                <input type="datetime-local" class="form-control" name="start_day" id="start_day"
                    value="{{ old('start_day') }}">
                @error('start_day')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="end_day" class="form-label">Ngày kết thúc</label>
                <input type="datetime-local" class="form-control" name="end_day" id="end_day"
                    value="{{ old('end_day') }}">
                @error('end_day')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="discount_value" class="form-label">Giá trị giảm giá (đ)</label>
            <input type="number" step="0.01" min="0" class="form-control" name="discount_value" id="discount_value"
                value="{{ old('discount_value') }}">
            @error('discount_value')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="total_min" class="form-label">Giá trị đơn hàng tối thiểu</label>
                <input type="number" step="0.01" min="0" class="form-control" name="total_min" id="total_min"
                    value="{{ old('total_min', 0) }}">
                @error('total_min')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="total_max" class="form-label">Giá trị đơn hàng tối đa (tuỳ chọn)</label>
                <input type="number" step="0.01" min="0" class="form-control" name="total_max" id="total_max"
                    value="{{ old('total_max') }}">
                @error('total_max')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="quantity" class="form-label">Số lượng (lượt sử dụng tối đa)</label>
                <input type="number" class="form-control" name="quantity" id="quantity" value="{{ old('quantity', 1) }}">
                @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="is_active" class="form-label">Trạng thái</label>
                <select name="is_active" class="form-control" id="is_active" style="height: 45px;">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                </select>

                @error('is_active')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-outline-success">Tạo Voucher</button>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        </div>
    </form>

    <script>
        document.getElementById('generateCodeBtn').addEventListener('click', function() {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 6; i++) {
                result += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            document.getElementById('code').value = result;
        });
    </script>
@endsection
