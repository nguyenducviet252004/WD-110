@extends('Layout.Layout')

@section('title')
    Danh sách đơn hàng
@endsection

@section('content_admin')
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Include realtime CSS and JavaScript -->
    <link rel="stylesheet" href="{{ asset('css/realtime-orders.css') }}">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('js/realtime-orders.js') }}"></script>
    @if (session('success'))
        <div class="alert alert-modern alert-modern-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-modern alert-modern-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-center">
                <i class="fas fa-shopping-cart me-3"></i>
                Danh sách đơn hàng
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Instructions Card -->
        <div class="modern-card hover-lift mb-4">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-info-circle me-2"></i>
                    Hướng dẫn cập nhật trạng thái đơn hàng
                </h3>
            </div>
            <div class="modern-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="instruction-item">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            <strong>Quy tắc:</strong> Chỉ có thể cập nhật từng bước một, không được nhảy cóc
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="instruction-item">
                            <i class="fas fa-route text-info me-2"></i>
                            <strong>Quy trình:</strong> Chờ xử lý → Đã xử lý → Đang vận chuyển → Giao hàng thành công
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="instruction-item">
                            <i class="fas fa-ban text-danger me-2"></i>
                            <strong>Hủy đơn:</strong> Có thể hủy đơn ở bất kỳ bước nào trước khi giao hàng thành công
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="instruction-item">
                            <i class="fas fa-undo text-secondary me-2"></i>
                            <strong>Trả lại:</strong> Chỉ có thể trả lại sau khi giao hàng thành công
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section fade-in-up mb-4">
            <div class="filter-row">
                <div class="filter-group stagger-item">
                    <form action="{{ route('orders.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                        <input type="text" name="keyword" class="form-control-modern" placeholder="Mã đơn / Tên / Email / SĐT" value="{{ request('keyword') }}">

                        <select name="status" class="form-control-modern">
                            <option value="">Tất cả trạng thái đơn</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đã xử lý</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Đang vận chuyển</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Giao hàng thành công</option>
                            <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>Đã trả lại</option>
                        </select>

                        <select name="payment_status" class="form-control-modern">
                            <option value="">Tất cả thanh toán</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                        </select>

                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-filter"></i> Lọc kết quả
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Orders Table -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-shopping-bag me-2"></i>
                    Danh sách đơn hàng
                </h3>
                <span class="badge badge-modern badge-modern-info bounce-in">
                    {{ $orders->total() }} đơn hàng
                </span>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Người dùng</th>
                                <th>Địa chỉ giao hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="stagger-item" data-order-id="{{ $order->id }}">
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $order->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-dark">{{ $order->user->email }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            @if ($order->shipAddress && $order->shipAddress->ship_address)
                                                {{ $order->shipAddress->ship_address }}
                                            @else
                                                Không rõ
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern badge-modern-warning">
                                            <i class="fas fa-money-bill me-1"></i>
                                            {{ number_format($order->total_amount, 0) }} VNĐ
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('orders.index') }}" method="GET" id="orderStatusForm-{{ $order->id }}">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <select name="status" class="form-control-modern" data-current-status="{{ $order->status }}" onchange="confirmAndSubmit(this, {{ $order->status }})">
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
                                            </select>
                                        </form>
                                    </td>

                                <script>
                                    function confirmAndSubmit(selectElement, currentStatus) {
                                        const selectedStatus = parseInt(selectElement.value);

                                        // Định nghĩa quy tắc chuyển đổi trạng thái
                                        const allowedTransitions = {
                                            0: [1, 4], // Chờ xử lý -> Đã xử lý hoặc Hủy
                                            1: [2, 4], // Đã xử lý -> Đang vận chuyển hoặc Hủy
                                            2: [3], // Đang vận chuyển -> Giao hàng thành công hoặc Hủy
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

                                    <td>
                                        <div class="text-muted">
                                            {{ $order->message ?? 'Không có ghi chú' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('orders.show', $order->id) }}"
                                               class="action-btn action-btn-info hover-glow">
                                                <i class="fas fa-eye"></i>
                                                <span class="d-none d-md-inline">Chi tiết</span>
                                            </a>
                                        </div>
                                    </td>
                               {{-- <td>
                                   <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                               </td> --}}
                        </tr>
                    @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-modern">
            {{ $orders->links() }}
        </div>
    </div>

    <style>
            /* Đảm bảo bảng luôn hiển thị đầy đủ các cột, không bị ẩn */
            .table-responsive {
                overflow-x: auto;
            }
            table.table {
                min-width: 1200px;
            }
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
