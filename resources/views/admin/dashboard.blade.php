@extends('Layout.Layout')

@section('title')
    Trang chủ
@endsection

@section('content_admin')
    <div class="container text-center mt-5 mb-3">
        <h2>Trang chủ quản trị viên</h2>
    </div>
    <div class="dashboard-stats mt-3">
        <div class="stat-item">
            <h2>Tổng Doanh Thu</h2>
            <p>{{ number_format($totalRevenue, 0, ',', '.') }} VND</p>
        </div>
        <div class="stat-item">
            <h2>Tổng Thành Viên</h2>
            <p>{{ $totalUsers }} Thành viên</p>
        </div>
        <div class="stat-item">
            <h2>Đã Hoàn Thành</h2>
            <p>{{ $completedOrders }} Đơn hàng</p>
        </div>
        <div class="stat-item">
            <h2>Chưa Xử Lí </h2>
            <p>{{ $pendingOrders }} Đơn hàng</p>
            <a class="btn btn-success" href="{{ route('orders.index') }}">Xử lí ngay</a>
        </div>
    </div>
    <!-- Menu -->
    <div class="container mt-4">
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account"
                    type="button" role="tab">
                    Tài khoản
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button"
                    role="tab">
                    Doanh thu - Dơn hàng
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="top-products-tab" data-bs-toggle="tab" data-bs-target="#top-products"
                    type="button" role="tab">
                    Sản phẩm bán chạy
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tonkho-tab" data-bs-toggle="tab" data-bs-target="#tonkho" type="button"
                    role="tab">
                    Tồn kho - Sắp hết
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="voucher-tab" data-bs-toggle="tab" data-bs-target="#voucher" type="button"
                    role="tab">
                    Voucher
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tiledon-tab" data-bs-toggle="tab" data-bs-target="#tiledon" type="button"
                    role="tab">
                    Tỉ lệ đơn
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="khachhang-tab" data-bs-toggle="tab" data-bs-target="#khachhang" type="button"
                    role="tab">
                    Khách hàng
                </button>
            </li>
        </ul>
<div class="mt-5 mb-5">
            <form id="filter-stats-form" action="" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start-date" class="form-label">Từ ngày:</label>
                    <input type="date" id="start-date" name="start_date" class="form-control"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end-date" class="form-label">Đến ngày:</label>
                    <input type="date" id="end-date" name="end_date" class="form-control"
                        value="{{ request('end_date') }}">
                </div>
                <!-- Input ẩn để lưu URL hiện tại -->
                <input type="hidden" id="current-url" name="current_url" value="">
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </div>
            </form>
        </div>
<!-- Nội dung của từng tab -->
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
    <style>
        .dashboard-stats {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .stat-item {
            flex: 1;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-item h2 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #333;
        }

        .stat-item p {
            font-size: 18px;
            color: #555;
            font-weight: bold;
        }
    </style>
    <script>
        // Hàm tải dữ liệu qua AJAX
        function loadTabContent(tabId, url) {
            const contentDiv = document.getElementById(`${tabId}-content`);
            if (!contentDiv || !url) return;

            // Lấy giá trị từ đầu tháng đến hôm nay
            const today = new Date();
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const formattedStartDate = startOfMonth.toISOString().split('T')[0];
            const formattedEndDate = today.toISOString().split('T')[0];

            // Gửi request với start_date và end_date
            const params = new URLSearchParams({
                start_date: formattedStartDate,
                end_date: formattedEndDate,
            });

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
        });

        // Cập nhật giá trị `current_url` khi tải trang ban đầu
        document.addEventListener('DOMContentLoaded', () => {
            updateCurrentUrl();

            document.querySelectorAll('.nav-link').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    updateCurrentUrl();
                });
            });

            function updateCurrentUrl() {
                const activeTab = document.querySelector('.nav-link.active');
                const tabId = activeTab.dataset.bsTarget.replace('#', '');
                const url = document.getElementById(`${tabId}-content`).dataset.url;
                document.getElementById('current-url').value = url;
            }

            const startDateInput = document.getElementById('start-date');
            const endDateInput = document.getElementById('end-date');

            const firstDayOfMonth = new Date();
            firstDayOfMonth.setDate(1);
            const formattedStartDate = firstDayOfMonth.toISOString().split('T')[0];

            const today = new Date();
            const formattedEndDate = today.toISOString().split('T')[0];

            startDateInput.value = formattedStartDate;
            endDateInput.value = formattedEndDate;
        });

        // Lọc dữ liệu khi submit form
        document.getElementById('filter-stats-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = event.target;
            const url = document.getElementById('current-url').value;
            const formData = new FormData(form);

            fetch(url + '?' + new URLSearchParams(formData).toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.text())
                .then(data => {
                    const activeTab = document.querySelector('.nav-link.active');
                    const tabId = activeTab.dataset.bsTarget.replace('#', '');
                    const contentDiv = document.getElementById(`${tabId}-content`);
                    contentDiv.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
