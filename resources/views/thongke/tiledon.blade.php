<div class="container">
    {{-- Tổng Quan --}}
    <div class="dashboard-summary mb-4">
        <h3 class="mb-3">Tổng Quan Đơn Hàng</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tổng số đơn hàng</th>
                    <th>Đơn hàng hủy</th>
                    <th>Đơn hàng hoàn thành</th>
                    <th>Tỷ lệ hoàn thành</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data['total_orders']}}</td>
                    <td>{{ $data['canceled_orders']}}</td>
                    <td>{{ $data['completed_orders']}}</td>
                    <td>{{ $data['completion_rate']}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Đơn hàng trong tháng --}}
    <div class="monthly-orders mb-4">
        <h3 class="mb-3">Đơn Hàng Tháng Này</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tổng số đơn hàng</th>
                    <th>Đơn hàng hủy</th>
                    <th>Đơn hàng hoàn thành</th>
                    <th>Tỷ lệ hoàn thành tháng này</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data['total_order_this_month']}}</td>
                    <td>{{ $data['canceled_orders_this_month']}}</td>
                    <td>{{ $data['completed_orders_this_month']}}</td>
                    <td>{{ $data['completion_rate_this_month']}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Thông tin hủy đơn --}}
    <div class="cancel-reasons mb-4">
        <3 class="mb-3">Lý Do Hủy Đơn</h3>
        @if (count($data['cancel_reasons']) > 0)
            <ul class="list-group">
                @foreach ($data['cancel_reasons'] as $reason)
                    <li class="list-group-item">
                        <strong>{{ $reason['message'] }}</strong> - Số lần hủy: {{ $reason['count'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>Không có lý do hủy đơn nào được ghi nhận.</p>
        @endif
    </div>

    {{-- Lý do hủy đơn phổ biến nhất --}}
    <div class="most-common-reason mb-4">
        <h3 class="mb-3">Lý do hủy đơn phổ biến nhất</h3>
        <p>{{ $data['most_common_cancel_reason'] }}</p>
    </div>

    {{-- Thông tin hệ thống --}}
    <div class="system-summary mb-4">
        <h3 class="mb-3">Thông tin hệ thống</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tổng số đơn hàng hệ thống</th>
                    <th>Các đơn Chưa xử lý - Đang vận chuyển</th>
                    <th>Đơn hàng hủy hệ thống</th>
                    <th>Đơn hàng hoàn thành hệ thống</th>
                    <th>Tỷ lệ hoàn thành hệ thống</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data['total_order_system']}}</td>
                    <td>{{ $data['other_status_orders_system']}}</td>
                    <td>{{ $data['canceled_orders_system']}}</td>
                    <td>{{ $data['completed_orders_system']}}</td>
                    <td>{{ $data['completion_rate_system']}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>