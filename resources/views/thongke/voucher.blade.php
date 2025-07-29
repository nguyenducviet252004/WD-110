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

<style>
    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: #fff;
        border-collapse: collapse;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table-bordered {
        border: 1px solid #ddd;
    }

    .table th, .table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .table td {
        color: #495057;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    h3, h4 {
        color: #007bff;
        margin-bottom: 15px;
    }

    h3 {
        font-size: 1.5rem;
        font-weight: bold;
    }

    h4 {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .voucher-statistics, .voucher-system-summary {
        padding: 20px;
        border-radius: 10px;
        background-color: #f9f9f9;
        margin-bottom: 30px;
    }

    .text-primary {
        color: #007bff;
    }

    .mt-5, .mb-5, .mt-4, .mb-4, .mt-3, .mb-3 {
        margin-top: 1.25rem !important;
        margin-bottom: 1.25rem !important;
    }
</style>
