@extends('Layout.Layout')

@section('title')
    Trang chủ
@endsection

@section('content_admin')
    <!-- Modern Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="m-0">
                        <i class="fas fa-home me-3"></i>
                        Trang chủ quản trị viên
                    </h1>
                    <nav aria-label="breadcrumb" class="fade-in-up">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Modern Time Filter -->
    <div class="container-fluid">
        <div class="modern-card hover-lift mb-4">
            <div class="modern-card-header">
                <h3 class="modern-card-title">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Chọn khoảng thời gian thống kê
                </h3>
            </div>
            <div class="modern-card-body">
                <form id="time-filter-form" method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="start_date" class="form-label-modern">
                                <i class="fas fa-calendar-day me-2"></i>Từ ngày:
                            </label>
                            <input type="date" id="start_date" name="start_date" class="form-control form-control-modern" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="end_date" class="form-label-modern">
                                <i class="fas fa-calendar-day me-2"></i>Đến ngày:
                            </label>
                            <input type="date" id="end_date" name="end_date" class="form-control form-control-modern" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-modern btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Lọc dữ liệu
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-modern btn-secondary w-100">
                            <i class="fas fa-sync-alt me-2"></i>Làm mới
                        </a>
                    </div>
                </form>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info modern-alert mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Hướng dẫn:</strong> Chọn khoảng thời gian cụ thể để xem thống kê chi tiết. Dữ liệu sẽ được cập nhật tự động khi chuyển tab.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Time Info -->
    <div class="container-fluid mt-3">
        <div class="alert alert-info modern-alert">
            <i class="fas fa-calendar-alt me-2"></i>
            <strong>Khoảng thời gian đã chọn:</strong>
            @if(isset($formattedStartDate) && isset($formattedEndDate))
                Từ {{ $formattedStartDate }} đến {{ $formattedEndDate }}
            @else
                Tháng {{ now()->format('m/Y') }}
            @endif
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="modern-card hover-lift">
                <div class="modern-card-body text-center">
                    <div class="stat-icon-modern bg-primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3 class="stat-value-modern">{{ number_format($totalRevenue, 0, ',', '.') }} VND</h3>
                    <p class="stat-label-modern">Tổng Doanh Thu</p>
                    @if(isset($revenueChange))
                        <div class="stat-change-modern {{ $revenueChange >= 0 ? 'positive' : 'negative' }}">
                            <i class="fas fa-arrow-{{ $revenueChange >= 0 ? 'up' : 'down' }}"></i>
                            {{ number_format(abs($revenueChange), 1) }}%
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Tổng Thành Viên</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <span class="small-box-footer">Tất cả thời gian</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $completedOrders }}</h3>
                    <p>Đã Hoàn Thành</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                @if(isset($ordersChange))
                    <span class="small-box-footer">
                        <i class="fas fa-arrow-{{ $ordersChange >= 0 ? 'up' : 'down' }}"></i>
                        {{ number_format(abs($ordersChange), 1) }}%
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $pendingOrders }}</h3>
                    <p>Chưa Xử Lí</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <a class="btn btn-success mt-2" href="{{ route('orders.index') }}">
                    <i class="fas fa-clipboard-check"></i> Xử lí ngay
                </a>
                <span class="small-box-footer">Tất cả thời gian</span>
            </div>
        </div>
    </div>

    <!-- Menu -->
    <div class="container-fluid mt-4">
        <div class="card card-outline card-primary">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="account-tab" data-bs-toggle="tab" href="#account" role="tab">Tài khoản</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">Doanh thu - Đơn hàng</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="top-products-tab" data-bs-toggle="tab" href="#top-products" role="tab">Sản phẩm bán chạy</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tonkho-tab" data-bs-toggle="tab" href="#tonkho" role="tab">Tồn kho - Sắp hết</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="voucher-tab" data-bs-toggle="tab" href="#voucher" role="tab">Voucher</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tiledon-tab" data-bs-toggle="tab" href="#tiledon" role="tab">Tỉ lệ đơn</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="khachhang-tab" data-bs-toggle="tab" href="#khachhang" role="tab">Khách hàng</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content mt-4">
                    <div class="tab-pane fade show active" id="account" role="tabpanel">
                        <div id="account-content" data-url="{{ route('thongke.account') }}"></div>
                    </div>
                    <div class="tab-pane fade" id="orders" role="tabpanel">
                        <div id="orders-content" data-url="{{ route('thongke.orders') }}"></div>
                    </div>
                    <div class="tab-pane fade" id="top-products" role="tabpanel">
                        <div id="top-products-content" data-url="{{ route('thongke.topproduct') }}"></div>
                    </div>
                    <div class="tab-pane fade" id="tonkho" role="tabpanel">
                        <div id="tonkho-content" data-url="{{ route('thongke.tonkho') }}"></div>
                    </div>
                    <div class="tab-pane fade" id="voucher" role="tabpanel">
                        <div id="voucher-content" data-url="{{ route('thongke.voucher') }}"></div>
                    </div>
                    <div class="tab-pane fade" id="tiledon" role="tabpanel">
                        <div id="tiledon-content" data-url="{{ route('thongke.tiledon') }}"></div>
                    </div>
                    <div class="tab-pane fade" id="khachhang" role="tabpanel">
                        <div id="khachhang-content" data-url="{{ route('thongke.khachhang') }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sử dụng style của AdminLTE, không cần custom style riêng cho dashboard -->

    <script>
        // Hàm tải dữ liệu qua AJAX
        function loadTabContent(tabId, url) {
            const contentDiv = document.getElementById(`${tabId}-content`);
            if (!contentDiv || !url) return;

            // Lấy giá trị từ form chính
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            const params = new URLSearchParams();

            // Chỉ sử dụng ngày cụ thể
            if (startDateInput && startDateInput.value && endDateInput && endDateInput.value) {
                params.append('start_date', startDateInput.value);
                params.append('end_date', endDateInput.value);
            }

            contentDiv.innerHTML = '<div class="text-center">Đang tải dữ liệu...</div>';

            fetch(`${url}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.text())
                .then(data => {
                    contentDiv.innerHTML = data;
                })
                .catch(error => {
                    contentDiv.innerHTML = '<div class="text-danger">Không thể tải dữ liệu. Vui lòng thử lại.</div>';
                    console.error('Error loading tab content:', error);
                });
        }

        // Lắng nghe sự kiện tab thay đổi
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                const tabId = event.target.dataset.bsTarget.replace('#', '');
                const url = document.getElementById(`${tabId}-content`).dataset.url;
                loadTabContent(tabId, url);
            });
        });

        // Tải dữ liệu của tab đầu tiên khi load trang
        document.addEventListener('DOMContentLoaded', () => {
            const activeTab = document.querySelector('.nav-link.active');
            const tabId = activeTab.dataset.bsTarget.replace('#', '');
            const url = document.getElementById(`${tabId}-content`).dataset.url;
            loadTabContent(tabId, url);

            // Tự động cập nhật tab khi form chính thay đổi
            document.getElementById('start_date')?.addEventListener('change', function() {
                const activeTab = document.querySelector('.nav-link.active');
                const tabId = activeTab.dataset.bsTarget.replace('#', '');
                const url = document.getElementById(`${tabId}-content`).dataset.url;
                loadTabContent(tabId, url);
            });

            document.getElementById('end_date')?.addEventListener('change', function() {
                const activeTab = document.querySelector('.nav-link.active');
                const tabId = activeTab.dataset.bsTarget.replace('#', '');
                const url = document.getElementById(`${tabId}-content`).dataset.url;
                loadTabContent(tabId, url);
            });
        });




    </script>
@endsection
