@extends('Layout.Layout')

@section('title')
    Danh sách đơn hàng
@endsection
@section('content_admin')
    {{-- CSRF Token for AJAX requests --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Include realtime CSS and JS --}}
    <link rel="stylesheet" href="{{ asset('css/realtime-orders.css')}}">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('js/realtime-orders.js') }}"></script>

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

    {{-- Thông báo hướng dẫn --}}
    <div class="alert alert-info mx-3 mb-3">
        <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Hướng dẫn cập nhật trạng thái đơn hàng</h6>
        <ul class="mb-0">
            <li><strong>Quy tắc:</strong> Cập nhật trạng thái đơn hàng đúng theo quy trình từng bước</li>
            <li><strong>Quy trình:</strong> Chờ xử lý -> Đã xử lý -> Đang vận chuyển -> Giao hàng thành công</li>
            <li><strong>Hủy đơn:</strong> Chỉ được phép thao tác hủy trước khi vận chuyển đơn hàng</li>
            <li><strong>Trả hàng:</strong> Chỉ được phép thao tác trả hàng sau khi đơn hàng đã giao</li>
        </ul>
    </div>

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
                        <tr data-order-id="{{ $order->id }}">
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
                                    <select name="status" class="form-select" data-current-status="{{ $order->status}}" onchange="confirmAndSubmit(this, {{ $order->status }})">
                                        <option value="0" {{ $order->status == 0 ? 'selected' : '' }}
                                            {{ !in_array(0, \App\Helpers\OrderHelper::getNextAllowedStatuses($order->status)) ? 'disabled' : '' }}>
                                            Chờ xử lý
                                        </option>
                                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}
                                            {{ !in_array(1, \App\Helpers\OrderHelper::getNextAllowedStatuses($order->status)) ? 'disabled' : '' }}>
                                            Đã xử lý
                                        </option>
                                        <option value="2" {{ $order->status == 2 ? 'selected' : '' }}
                                            {{ !in_array(2, \App\Helpers\OrderHelper::getNextAllowedStatuses($order->status)) ? 'disabled' : '' }}>
                                            Đang vận chuyển
                                        </option>
                                        <option value="3" {{ $order->status == 3 ? 'selected' : '' }}
                                            {{ !in_array(3, \App\Helpers\OrderHelper::getNextAllowedStatuses($order->status)) ? 'disabled' : '' }}>
                                            Giao hàng thành công
                                        </option>
                                        <option value="4" {{ $order->status == 4 ? 'selected' : '' }}
                                            {{ !in_array(4, \App\Helpers\OrderHelper::getNextAllowedStatuses($order->status)) ? 'disabled' : '' }}>
                                            Đã hủy
                                        </option>
                                        {{-- <option value="5" {{ $order->status == 5 ? 'selected' : '' }}
                                            {{ !in_array(5, \App\Helpers\OrderHelper::getNextAllowedStatuses($order->status)) ? 'disabled' : '' }}>
                                            Đã trả lại
                                        </option> --}}
                                    </select>
                                </form>
                                <script>
                                    function confirmAndSubmit(selectElement, currentStatus) {
                                        const selectedStatus = parseInt(selectElement.value);

                                        // Định nghĩa quy tắc chuyển đổi trạng thái
                                        const allowedTransitions = {
                                            0: [1, 4], // Chờ xử lý -> Đã xử lý hoặc hủy
                                            1: [2, 4], // Đã xử lý -> Đang vận chuyển hoặc hủy
                                            2: [3], // Đang vẫn chuyển -> Giao hàng thành công
                                            3: [5],    // Giao hàng thành công -> Đã trả lại
                                            4: [],     // Đã hủy -> Không thể chuyển
                                            // 5: []      // Đã trả lại -> Không thể chuyển
                                        };

                                        // Kiểm tra xem có được phép chuyển không
                                        if (!allowedTransitions[currentStatus].includes(selectedStatus)) {
                                            const statusNames = {
                                                0: 'Chờ xử lý',
                                                1: 'Đã xử lý', 
                                                2: 'Đang vận chuyển',
                                                3: 'Giao hàng thành công',
                                                4: 'Đã hủy',
                                                // 5: 'Đã trả lại'
                                            };
                                            
                                            const currentStatusName = statusNames[currentStatus] || 'Không xác định';
                                            const newStatusName = statusNames[selectedStatus] || 'Không xác định';
                                            
                                            alert(`Không thể chuyển từ trạng thái '${currentStatusName}' sang '${newStatusName}'.\n\nQuy tắc cập nhật:\n• Chỉ có thể cập nhật từng bước một\n• Quy trình: Chờ xử lý → Đã xử lý → Đang vận chuyển → Giao hàng thành công\n• Có thể hủy đơn ở bất kỳ bước nào trước khi giao hàng thành công`);
                                            
                                            // Đặt lại giá trị về trạng thái hiện tại
                                            selectElement.value = currentStatus;
                                            return;
                                        }
                                        
                                        // Xác nhận trước khi cập nhật
                                        const statusNames = {
                                            0: 'Chờ xử lý',
                                            1: 'Đã xử lý', 
                                            2: 'Đang vận chuyển',
                                            3: 'Giao hàng thành công',
                                            4: 'Đã hủy',
                                            // 5: 'Đã trả lại'
                                        };
                                        
                                        const currentStatusName = statusNames[currentStatus] || 'Không xác định';
                                        const newStatusName = statusNames[selectedStatus] || 'Không xác định';
                                        
                                        if (confirm(`Xác nhận cập nhật trạng thái đơn hàng từ '${currentStatusName}' sang '${newStatusName}'?`)) {
                                            selectElement.form.submit();
                                        } else {
                                            // Đặt lại giá trị về trạng thái hiện tại nếu không xác nhận
                                            selectElement.value = currentStatus;
                                        }
                                        
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
