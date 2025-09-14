@extends('Layout.Layout')

@section('title')
    Quản lý tài khoản
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
                <i class="fas fa-users me-3"></i>
                Danh sách Người dùng
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filter Section -->
        <div class="filter-section fade-in-up">
            <div class="filter-row">
                <div class="filter-group stagger-item">
                    <a href="{{ route('managers.index') }}" class="btn-modern btn-modern-info {{ !request('is_active') ? 'active' : '' }}">
                        <i class="fas fa-list"></i> Tất cả trạng thái
                    </a>
                </div>
                <div class="filter-group stagger-item">
                    <a href="{{ route('managers.index', ['is_active' => 'locked']) }}" class="btn-modern btn-modern-warning {{ request('is_active') == 'locked' ? 'active' : '' }}">
                        <i class="fas fa-lock"></i> Đã khóa
                    </a>
                </div>
                <div class="filter-group stagger-item">
                    <a href="{{ route('managers.index', ['is_active' => 'normal']) }}" class="btn-modern btn-modern-success {{ request('is_active') == 'normal' ? 'active' : '' }}">
                        <i class="fas fa-unlock"></i> Bình thường
                    </a>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-users me-2"></i>
                    Danh sách người dùng
                </h3>
                <span class="badge badge-modern badge-modern-info bounce-in">
                    {{ $data->total() }} người dùng
                </span>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Avatar</th>
                                <th>Họ Tên</th>
                                <th>Ngày Sinh</th>
                                <th>Số Điện Thoại</th>
                                <th>Địa Chỉ</th>
                                <th>Email</th>
                                <th>Vai Trò</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $user)
                                <tr class="stagger-item">
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $user->id }}</span>
                                    </td>
                                    <td>
                                        @if ($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                                 alt="{{ $user->fullname }}"
                                                 class="product-image hover-scale">
                                        @else
                                            <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-dark">{{ $user->fullname }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $user->birth_day ?? 'N/A' }}</td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td>{{ $user->address ?? 'N/A' }}</td>
                                    <td>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </td>
                                    <td>
                                        @if ($user->role === 0)
                                            <span class="badge badge-modern badge-modern-info">
                                                <i class="fas fa-user me-1"></i>
                                                User
                                            </span>
                                        @elseif($user->role === 1)
                                            <span class="badge badge-modern badge-modern-warning">
                                                <i class="fas fa-crown me-1"></i>
                                                Manager
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->is_active)
                                            <span class="badge badge-modern badge-modern-success">
                                                <i class="fas fa-check me-1"></i>
                                                Hoạt động
                                            </span>
                                        @else
                                            <span class="badge badge-modern badge-modern-danger">
                                                <i class="fas fa-ban me-1"></i>
                                                Bị khóa
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <form action="{{ route('managers.update', $user->id) }}" method="POST" style="display: inline;" novalidate>
                                                @csrf
                                                @method('PATCH')
                                                @if ($user->is_active)
                                                    <button type="submit"
                                                            class="action-btn action-btn-warning hover-glow"
                                                            onclick="return confirm('Bạn có chắc chắn muốn khóa tài khoản này?')">
                                                        <i class="fas fa-lock"></i>
                                                        <span class="d-none d-md-inline">Khóa</span>
                                                    </button>
                                                @else
                                                    <button type="submit"
                                                            class="action-btn action-btn-success hover-glow"
                                                            onclick="return confirm('Bạn có chắc chắn muốn mở khóa tài khoản này?')">
                                                        <i class="fas fa-unlock"></i>
                                                        <span class="d-none d-md-inline">Mở khóa</span>
                                                    </button>
                                                @endif
                                            </form>
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
            {{ $data->links() }}
        </div>
    </div>
@endsection
