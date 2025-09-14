@extends('Layout.Layout')

@section('title')
    Danh sách phiếu giảm giá
@endsection

@section('content_admin')
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
                <i class="fas fa-percentage me-3"></i>
                Danh sách phiếu giảm giá
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filter Section -->
        <div class="filter-section fade-in-up">
            <div class="filter-row">
                <div class="filter-group stagger-item">
                    <form method="GET" action="{{ route('vouchers.index') }}" id="filterForm">
                        <div class="d-flex gap-2 flex-wrap">
                            <select name="status" id="status" class="form-control-modern" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>

                            <select name="expiry_status" id="expiry_status" class="form-control-modern" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Tất cả thời hạn</option>
                                <option value="valid" {{ request('expiry_status') == 'valid' ? 'selected' : '' }}>Còn hạn</option>
                                <option value="expired" {{ request('expiry_status') == 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                            </select>

                            <select name="sort_by" id="sort_by" class="form-control-modern" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Giá mặc định</option>
                                <option value="asc" {{ request('sort_by') == 'asc' ? 'selected' : '' }}>Giảm dần</option>
                                <option value="desc" {{ request('sort_by') == 'desc' ? 'selected' : '' }}>Tăng dần</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="filter-group stagger-item">
                    <a href="{{ route('vouchers.create') }}" class="btn-modern btn-modern-success hover-lift">
                        <i class="fas fa-plus"></i> Thêm mới voucher
                    </a>
                </div>
            </div>
        </div>

        <!-- Vouchers Table -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-tags me-2"></i>
                    Danh sách phiếu giảm giá
                </h3>
                <span class="badge badge-modern badge-modern-info bounce-in">
                    {{ $vouchers->total() }} voucher
                </span>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mã giảm giá</th>
                                <th>Giá trị</th>
                                <th>Đơn tối thiểu</th>
                                <th>Đơn tối đa</th>
                                <th>Mô tả</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $voucher)
                                <tr class="stagger-item">
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $voucher->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-dark">{{ $voucher->code }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern badge-modern-warning">
                                            <i class="fas fa-money-bill me-1"></i>
                                            {{ number_format($voucher->discount_value ?? 0) }} VND
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ number_format($voucher->total_min ?? 0) }} VND
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ number_format($voucher->total_max ?? 0) }} VND
                                        </small>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            {{ $voucher->description ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-start me-1"></i>
                                                {{ \Carbon\Carbon::parse($voucher->start_day)->format('d/m/Y') }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-end me-1"></i>
                                                {{ \Carbon\Carbon::parse($voucher->end_day)->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($voucher->is_active)
                                            <span class="badge badge-modern badge-modern-success">
                                                <i class="fas fa-check me-1"></i>
                                                Hoạt động
                                            </span>
                                        @else
                                            <span class="badge badge-modern badge-modern-danger">
                                                <i class="fas fa-ban me-1"></i>
                                                Không hoạt động
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Edit -->
                                            <a href="{{ route('vouchers.edit', $voucher->id) }}"
                                               class="action-btn action-btn-warning hover-glow">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-none d-md-inline">Cập nhật</span>
                                            </a>

                                            <!-- Toggle Status -->
                                            <a onclick="return confirm('Bạn có chắc muốn cập nhật trạng thái?')"
                                                href="{{ route('vouchers.index', ['toggle_active' => $voucher->id]) }}"
                                                class="action-btn {{ $voucher->is_active ? 'action-btn-secondary' : 'action-btn-success' }} hover-glow">
                                                @if ($voucher->is_active)
                                                    <i class="fas fa-eye-slash"></i>
                                                    <span class="d-none d-md-inline">Ẩn</span>
                                                @else
                                                    <i class="fas fa-eye"></i>
                                                    <span class="d-none d-md-inline">Hiện</span>
                                                @endif
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-modern">
            {{ $vouchers->links() }}
        </div>
    </div>
@endsection
