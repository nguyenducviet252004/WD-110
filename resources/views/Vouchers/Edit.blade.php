@extends('Layout.Layout')

@section('title')
    Cập nhật phiếu giảm giá
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

    <h1 class="text-center mt-5">Cập nhật voucher</h1>

    <form method="POST" action="{{ route('vouchers.update', $voucher->id) }}" enctype="multipart/form-data" class="container">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="code" class="col-2 col-form-label">Mã giảm giá</label>
            <input type="text" class="form-control" name="code" id="code"
                value="{{ old('code', $voucher->code) }}" required>
            <button type="button" class="btn btn-secondary mt-2" style="width: 300px" id="generateCodeBtn">Tạo mã ngẫu
                nhiên</button>
        </div>

        <div class="mb-3">
            <label for="discount_value" class="col-2 col-form-label">Giá trị giảm giá</label>
            <input type="text" placeholder="exam: 100.000 VND" class="form-control" name="discount_value"
                id="discount_value" value="{{ old('discount_value', $voucher->discount_value) }}" required />
        </div>

        <div class="mb-3">
            <label for="total_min" class="col-2 col-form-label">Giá trị đơn hàng đạt tối thiểu</label>
            <input type="text" placeholder="exam: 100.000 VND" class="form-control" name="total_min" id="total_min"
                value="{{ old('total_min', $voucher->total_min) }}" required />
        </div>

        <div class="mb-3">
            <label for="total_max" class="col-2 col-form-label">Giá trị đơn hàng đạt tối đa</label>
            <input type="text" placeholder="exam: 100.000 VND" class="form-control" name="total_max" id="total_max"
                value="{{ old('total_max', $voucher->total_max) }}" required />
        </div>

        <div class="mb-3">
            <label for="description" class="col-2 col-form-label">Mô tả</label>
            <textarea class="form-control" name="description" id="description">{{ old('description', $voucher->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="quantity" class="col-2 col-form-label">Số lượng</label>
            <input type="number" class="form-control" name="quantity" id="quantity"
                value="{{ old('quantity', $voucher->quantity) }}" required>
        </div>

        <div class="mb-3">
            <input type="date" class="form-control" name="start_day" id="start_day" hidden
                value="{{ old('start_day', \Carbon\Carbon::parse($voucher->start_day)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label for="end_day" class="col-2 col-form-label">Ngày kết thúc</label>
            <input type="date" class="form-control" name="end_day" id="end_day"
                value="{{ old('end_day', \Carbon\Carbon::parse($voucher->end_day)->format('Y-m-d')) }}">
        </div>

        @if (old('end_day') &&
                old('start_day') &&
                \Carbon\Carbon::parse(old('end_day'))->lt(\Carbon\Carbon::parse(old('start_day'))))
            <div class="alert alert-danger">
                Ngày kết thúc không được nhỏ hơn ngày bắt đầu.
            </div>
        @endif



        <div class="mb-3">
            <label for="is_active" class="col-2 col-form-label">Trạng thái:</label>
            <select name="is_active" class="form-control mt-2" id="is_active" required>
                <option value="1" {{ old('is_active', $voucher->is_active) == 1 ? 'selected' : '' }}>Đang hoạt động
                </option>
                <option value="0" {{ old('is_active', $voucher->is_active) == 0 ? 'selected' : '' }}>Không hoạt động
                </option>
            </select>
        </div>

        <div class="mt-3 mb-3 text-center">
            <button type="submit" class="btn btn-outline-success">
                Cập nhật Voucher
            </button>
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
