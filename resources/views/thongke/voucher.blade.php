{{-- Hiển thị kết quả lọc --}}
<div class="voucher-statistics mt-5 mb-3">
    <h3 class="text-primary">Thống kê Voucher Sử Dụng</h3>
    <table class="table table-bordered mt-4">
        <tr>
            <th>Tổng số Voucher đã sử dụng</th>
            <td>{{ $data['voucher_used_count'] }}</td>
        </tr>
        <tr>
            <th>Tổng giá trị giảm đã áp dụng</th>
            <td>{{ number_format($data['total_discount_value'], 2) }} VND</td>
        </tr>
    </table>

    <h4 class="text-primary mt-4 mb-3">Top 5 Voucher Được Sử Dụng Nhiều Nhất</h4>
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>Voucher Code</th>
                <th>Số lần sử dụng</th>
                <th>Giá trị giảm giá</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['top_5_vouchers'] as $voucher)
                <tr>
                    <td>{{ $voucher->code }}</td>
                    <td>{{ $voucher->usage_count }}</td>
                    <td>{{ number_format($voucher->discount_value, 2) }} VND</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Hiển thị dữ liệu hệ thống voucher --}}
<div class="voucher-system-summary mt-5 mb-5">
    <h3 class="text-primary">Tổng Quan Hệ Thống Voucher</h3>
    <table class="table table-bordered mt-4">
        <tr>
            <th>Tổng số Voucher đang hoạt động</th>
            <td>{{ $data['total_vouchers'] }}</td>
        </tr>
        <tr>
            <th>Tổng số Voucher đã sử dụng</th>
            <td>{{ $data['total_used_vouchers'] }}</td>
        </tr>
        <tr>
            <th>Tổng giá trị giảm giá đã áp dụng</th>
            <td>{{ number_format($data['total_discount_value'], 2) }} VND</td>
        </tr>
    </table>

    <h4 class="text-primary mt-4 mb-3">Voucher Còn Hạn</h4>
        <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>Voucher Code</th>
                <th>Giá trị</th>
                <th>Áp dụng từ</th>
                <th>Số lượng</th>
                <th>Hết hạn vào</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['valid_vouchers'] as $voucher)
                <tr>
                    <td>{{ $voucher->code }}</td>
                    <td>{{ number_format($voucher->discount_value, 2) }} VND</td>
                    <td>{{ number_format($voucher->total_min, 2) }} VND</td>
                    <td>{{ $voucher->quantity }}</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->end_day)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
