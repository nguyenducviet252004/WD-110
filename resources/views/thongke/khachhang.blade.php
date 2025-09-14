<div class="container">

    <!-- Thống kê khách hàng theo bộ lọc ngày -->
    @if ($data['filtered_customers']->isNotEmpty())
        <h4>Khách hàng mua nhiều nhất theo bộ lọc ngày</h4>
        <table class="table table-striped table-bordered mb-5">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Số đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['filtered_customers'] as $customer)
                    <tr>
                        <td>{{ $customer->email }}</td>
                        <td><img src="{{ asset('storage/' . $customer->avatar) }}" alt="Avatar" width="50"
                                height="50"></td>
                        <td>{{ $customer->order_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Không có dữ liệu khách hàng trong khoảng thời gian lọc.</p>
    @endif

    <!-- Thống kê khách hàng mua nhiều nhất tháng này -->
    @if ($data['top_customers_this_month']->isNotEmpty())
        <h4>Khách hàng mua nhiều nhất tháng này</h4>
        <table class="table table-striped table-bordered mb-5">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Số đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['top_customers_this_month'] as $customer)
                    <tr>
                        <td>{{ $customer->email }}</td>
                        <td><img src="{{ asset('storage/' . $customer->avatar) }}" alt="Avatar" width="50"
                                height="50"></td>
                        <td>{{ $customer->order_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Không có dữ liệu khách hàng mua hàng trong tháng này.</p>
    @endif

    <!-- Thống kê khách hàng mua nhiều nhất toàn hệ thống -->
    @if ($data['top_customers_all_time']->isNotEmpty())
        <h4>Khách hàng mua nhiều nhất toàn hệ thống</h4>
        <table class="table table-striped table-bordered mb-5">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Số đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['top_customers_all_time'] as $customer)
                    <tr>
                        <td>{{ $customer->email }}</td>
                        <td><img src="{{ asset('storage/' . $customer->avatar) }}" alt="Avatar" width="50"
                                height="50"></td>
                        <td>{{ $customer->order_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Không có dữ liệu khách hàng mua hàng toàn hệ thống.</p>
    @endif

</div>
