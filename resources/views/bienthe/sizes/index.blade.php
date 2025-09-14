@extends('Layout.Layout')

@section('title')
    Danh sách kích cỡ
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
                <i class="fas fa-ruler me-3"></i>
                Danh sách kích cỡ
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Sizes Table -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-ruler-combined me-2"></i>
                    Danh sách kích cỡ
                </h3>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-modern badge-modern-info bounce-in">
                        {{ $data->total() }} kích cỡ
                    </span>
                    <a href="{{ route('sizes.create') }}" class="btn-modern btn-modern-success hover-lift">
                        <i class="fas fa-plus"></i> Thêm mới
                    </a>
                </div>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kích cỡ</th>
                                <th>Sản phẩm liên quan</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $size)
                                <tr class="stagger-item">
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $size->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-dark">{{ $size->size }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern badge-modern-warning">
                                            <i class="fas fa-boxes me-1"></i>
                                            {{ $size->product_count }} sản phẩm
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $size->created_at ? $size->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Edit -->
                                            <a href="{{ route('sizes.edit', $size->id) }}"
                                               class="action-btn action-btn-warning hover-glow">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-none d-md-inline">Cập nhật</span>
                                            </a>

                                            <!-- Delete -->
                                            <form action="{{ route('sizes.destroy', $size->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn action-btn-danger hover-glow"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="d-none d-md-inline">Xóa</span>
                                                </button>
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
