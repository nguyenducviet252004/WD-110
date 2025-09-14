@extends('Layout.Layout')

@section('title')
    Danh sách danh mục
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
                <i class="fas fa-list me-3"></i>
                Danh sách danh mục
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Categories Table -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-tags me-2"></i>
                    Danh sách danh mục
                </h3>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-modern badge-modern-info bounce-in">
                        {{ $categories->total() }} danh mục
                    </span>
                    <a href="{{ route('categories.create') }}" class="btn-modern btn-modern-success hover-lift">
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
                                <th>Tên danh mục</th>
                                <th>Số sản phẩm</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Cập nhật</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr class="stagger-item">
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $category->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-dark">{{ $category->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern badge-modern-warning">
                                            <i class="fas fa-boxes me-1"></i>
                                            {{ $category->products()->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($category->is_active)
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
                                        <small class="text-muted">
                                            {{ $category->created_at ? $category->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $category->updated_at ? $category->updated_at->format('d/m/Y H:i') : 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Toggle Status -->
                                            <a onclick="return confirm('Bạn có chắc muốn cập nhật trạng thái?')"
                                                href="{{ route('categories.index', ['toggle_active' => $category->id]) }}"
                                                class="action-btn {{ $category->is_active ? 'action-btn-secondary' : 'action-btn-success' }} hover-glow">
                                                @if ($category->is_active)
                                                    <i class="fas fa-eye-slash"></i>
                                                    <span class="d-none d-md-inline">Ẩn</span>
                                                @else
                                                    <i class="fas fa-eye"></i>
                                                    <span class="d-none d-md-inline">Hiện</span>
                                                @endif
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('categories.edit', $category->id) }}"
                                               class="action-btn action-btn-warning hover-glow">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-none d-md-inline">Sửa</span>
                                            </a>

                                            <!-- Delete -->
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn action-btn-danger hover-glow"
                                                        onclick="return confirm('Bạn có chắc muốn xóa không?')">
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
            {{ $categories->links() }}
        </div>
    </div>
@endsection
