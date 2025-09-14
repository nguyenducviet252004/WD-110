<div class="container">
    <!-- Tổng Quan -->
    <div class="dashboard-summary mb-4">
        <h3 class="mb-3">Tổng Quan Đơn Hàng</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tổng Số Đơn Hàng</th>
                    <th>Đơn Hàng Hủy</th>
                    <th>Đơn Hàng Hoàn Thành</th>
                    <th>Tỷ Lệ Hoàn Thành</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data['total_orders'] }}</td>
                    <td>{{ $data['canceled_orders'] }}</td>
                    <td>{{ $data['completed_orders'] }}</td>
                    <td>{{ $data['completion_rate'] }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Đơn Hàng Trong Tháng -->
    <div class="monthly-orders mb-4">
        <h3 class="mb-3">Đơn Hàng Tháng Này</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tổng Số Đơn Hàng</th>
                    <th>Đơn Hàng Hủy</th>
                    <th>Đơn Hàng Hoàn Thành</th>
                    <th>Tỷ Lệ Hoàn Thành Tháng Này</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data['total_orders_this_month'] }}</td>
                    <td>{{ $data['canceled_orders_this_month'] }}</td>
                    <td>{{ $data['completed_orders_this_month'] }}</td>
                    <td>{{ $data['completion_rate_this_month'] }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Thông Tin Hủy Đơn -->
    <div class="cancel-reasons mb-4">
        <h3 class="mb-3">Lý Do Hủy Đơn</h3>
        @if (count($data['cancel_reasons']) > 0)
            <ul class="list-group">
                @foreach ($data['cancel_reasons'] as $reason)
                    <li class="list-group-item">
                        <strong>{{ $reason['message'] }}</strong> - Số lần hủy: {{ $reason['count'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>Không có lý do hủy đơn nào.</p>
        @endif
    </div>

    <!-- Lý Do Hủy Phổ Biến Nhất -->
    <div class="most-common-reason mb-4">
        <h3 class="mb-3">Lý Do Hủy Phổ Biến Nhất</h3>
        <p>{{ $data['most_common_cancel_reason'] }}</p>
    </div>

    <!-- Thông Tin Hệ Thống -->
    <div class="system-summary mb-4">
        <h3 class="mb-3">Thông Tin Hệ Thống</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tổng Số Đơn Hàng Hệ Thống</th>
                    <th>Các Đơn Chưa Xử Lí - Đang Vận Chuyển</th>
                    <th>Đơn Hàng Hủy Hệ Thống</th>
                    <th>Đơn Hàng Hoàn Thành Hệ Thống</th>
                    <th>Tỷ Lệ Hoàn Thành Hệ Thống</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data['total_orders_system'] }}</td>
                    <td>{{ $data['other_status_orders_system'] }}</td>
                    <td>{{ $data['canceled_orders_system'] }}</td>
                    <td>{{ $data['completed_orders_system'] }}</td>
                    <td>{{ $data['completion_rate_system'] }}%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
