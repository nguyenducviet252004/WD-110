@extends('Layout.Layout')

@section('title')
    Thêm mới phiếu giảm giá
@endsection

@section('content_admin')
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-center mt-5">Thêm mới voucher</h1>

    <form method="POST" action="{{ route('vouchers.store') }}" enctype="multipart/form-data" class="container" novalidate>
        @csrf

        <div class="mb-3">
            <label for="code" class="col-2 col-form-label">Mã giảm giá</label>

            <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}" required>
            <button type="button" class="btn btn-secondary mt-2" style="width: 300px" id="generateCodeBtn">Tạo mã ngẫu
                nhiên</button>
            @error('code')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="discount_value" class="col-2 col-form-label">Giá trị giảm giá</label>

            <input type="text" placeholder="exam: 100.000 VND" class="form-control" name="discount_value"
                id="discount_value" value="{{ old('discount_value') }}" required />
            @error('discount_value')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="total_min" class="col-2 col-form-label">Giá trị đơn hàng tối thiểu</label>

            <input type="text" placeholder="exam: 100.000 VND" class="form-control" name="total_min" id="discount_value"
                value="{{ old('total_min') }}" required />
            @error('total_min')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="total_max" class="col-2 col-form-label">Giá trị đơn hàng đạt tối đa</label>

            <input type="text" placeholder="exam: 100.000 VND" class="form-control" name="total_max" id="discount_value"
                value="{{ old('total_max') }}" required />
            @error('total_max')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="col-2 col-form-label">Mô tả</label>

            <textarea class="form-control" name="description" id="description"s="5">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="quantity" class="col-2 col-form-label">Số lượng</label>

            <input type="number" class="form-control" name="quantity" id="quantity" value="{{ old('quantity', 1) }}"
                required>
            @error('quantity')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="used_times" class="col-2 col-form-label">Số lần sử dụng:</label>

            <input type="number" class="form-control" name="used_times" id="used_times" value="0" disabled>
            <input type="number" class="form-control" name="used_times" id="used_times" value="0" hidden>

        </div>

        <div class="mb-3">
            <label for="start_day" class="col-2 col-form-label">Ngày bắt đầu</label>

            <input type="date" class="form-control" name="start_day" id="start_day" value="{{ old('start_day') }}">
            @error('start_day')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_day" class="col-2 col-form-label">Ngày kết thúc</label>

            <input type="date" class="form-control" name="end_day" id="end_day" value="{{ old('end_day') }}">
            @error('end_day')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="is_active" class="col-2 col-form-label">Trạng thái:</label>

            <select name="is_active" class="form-control mt-2" id="is_active" required>
                <option selected value="1">Đang hoạt động</option>
                <option value="0" >Không hoạt động</option>
            </select>
            @error('is_active')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-3 mb-3 text-center">

            <button type="submit" class="btn btn-outline-success">
                Tạo Voucher
            </button>
            <a href="{{ route('vouchers.index') }}" class="btn btn-outline-secondary">Quay lại</a>

        </div>
    </form>


    <script>
        // Hàm tạo mã ngẫu nhiên
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
