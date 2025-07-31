@extends('Layout.Layout')

@section('title')
    Danh sách đơn hàng
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
    <h1 class="text-center mt-5 mb-3">Danh sách đơn hàng</h1>
    <div class="d-flex justify-content-between px-3">

        <form action="{{ route('orders.index') }}" method="GET" class="mb-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                <option value="0" {{ request('status') == 0 ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Đã xử lý</option>
                <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Đang vận chuyển</option>
                <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Giao hàng thành công</option>
                <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>Đã hủy</option>
                <option value="5" {{ request('status') == 5 ? 'selected' : '' }}>Đã trả lại</option>
            </select>
        </form>

    </div>
    <div class="container mt-2">

        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Địa chỉ giao hàng</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->email }}</td>
                            <td>
                                @if ($order->shipAddress && $order->shipAddress->ship_address)
                                    {{ $order->shipAddress->ship_address }}
                                @else
                                    Không rõ  
                                @endif
                            </td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ number_format($order->total_amount, 2) }} VNĐ</td>
                            <td>
                                <form action="{{ route('orders.index') }}" method="GET" style="width: 200px;"
                                    id="orderStatusForm-{{ $order->id }}">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <select name="status" class="form-select" onchange="confirmAndSubmit(this)">
                                        <option value="0" {{ $order->status == 0 ? 'selected' : '' }}
                                            {{ $order->status != 0 ? 'disabled' : '' }}>
                                            Chờ xử lý
                                        </option>
                                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}
                                            {{ $order->status >= 1 ? 'disabled' : '' }}>
                                            Đã xử lý
                                        </option>
                                        <option value="2" {{ $order->status == 2 ? 'selected' : '' }}
                                            {{ $order->status >= 2 ? 'disabled' : '' }}>
                                            Đang vận chuyển
                                        </option>
                                        <option value="3" {{ $order->status == 3 ? 'selected' : '' }}
                                            {{ $order->status >= 3 ? 'disabled' : '' }}>
                                            Giao hàng thành công
                                        </option>
                                        <option value="4" {{ $order->status == 4 ? 'selected' : '' }}
                                            {{ $order->status == 4 ? 'disabled' : '' }}>
                                            Đã hủy
                                        </option>
                                        <option value="5" {{ $order->status == 5 ? 'selected' : '' }}
                                            {{ $order->status == 5 ? 'disabled' : '' }}>
                                            Đã trả lại
                                        </option>
                                    </select>
                                </form>
                                <script>
                                    function confirmAndSubmit(selectElement) {
                                        const currentStatus = {{ $order->status }}; // Lấy trạng thái hiện tại từ backend
                                        const selectedStatus = selectElement.value;

                                        // Nếu chọn trạng thái mới và muốn quay lại trạng thái cũ, hiển thị cảnh báo
                                        if ((currentStatus >= 1 && selectedStatus <= currentStatus) || (currentStatus >= 3 && selectedStatus <=
                                                currentStatus)) {
                                            alert('Bạn không thể quay lại trạng thái cũ.');
                                            selectElement.value = currentStatus; // Đặt lại giá trị trạng thái hiện tại
                                            return;
                                        }

                                        selectElement.form.submit(); // Nếu không có vấn đề, submit form
                                    }
                                </script>

                            </td>

                            <td>{{ $order->message }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">Chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>

    <style>
        /* Chỉnh sửa màu sắc các option để chúng luôn dễ nhìn */
        select.form-select {
            font-weight: bold;
            color: #7339b6;
            /* Màu chữ mặc định */
            background-color: #f8f9fa;
            /* Màu nền sáng cho select */
        }

        /* Các option bên trong select */
        select.form-select option {
            color: #000;
            /* Màu chữ đen cho tất cả các option */
            background-color: #fff;
            /* Màu nền trắng */
        }

        select.form-select option[value="0"] {

            color: #d3d3d3;
            /* Màu chữ đen */
        }

        select.form-select option[value="1"] {

            color: #4e73df;
            /* Màu chữ trắng */
        }

        select.form-select option[value="2"] {
            /* Màu nền cam cho trạng thái 'Đang vận chuyển' */
            color: #f39c12;
            /* Màu chữ trắng */
        }

        select.form-select option[value="3"] {
            /* Màu nền xanh lá cho trạng thái 'Giao hàng thành công' */
            color: #28a745;
            /* Màu chữ trắng */
        }

        select.form-select option[value="4"] {
            /* Màu nền đỏ cho trạng thái 'Đã hủy' */
            color: #dc3545;
            /* Màu chữ trắng */
        }

        select.form-select option[value="5"] {
            /* Màu nền tím cho trạng thái 'Đã trả lại' */
            color: #6f42c1;
            /* Màu chữ trắng */
        }

        /* Chỉnh sửa màu sắc khi select được focus */
        select.form-select:focus {
            border-color: #4e73df;
            outline: none;
        }
    </style>
<script>
        function confirmAndSubmit(selectElement) {
            const form = selectElement.closest('form');
            const selectedStatus = selectElement.value;

            if (confirm('Có chắc muốn chỉnh sửa trạng thái đơn hàng này?')) {
                form.submit();
            } else {
                selectElement.value = '';
            }
        }
    </script>
@endsection
