@extends('Layout.Layout')

@section('title', 'index Blog')

@section('content_admin')
    @if (session('error'))
        <div class="alert alert-modern alert-modern-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-modern alert-modern-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-center">
                <i class="fas fa-newspaper me-3"></i>
                Danh sách bài viết
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Blog Table -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-blog me-2"></i>
                    Danh sách bài viết
                </h3>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-modern badge-modern-info bounce-in">
                        {{ $blogs->count() }} bài viết
                    </span>
                    <a href="{{ route('blog.create') }}" class="btn-modern btn-modern-success hover-lift">
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
                                <th>Tiêu đề</th>
                                <th>Danh mục</th>
                                <th>Hình ảnh</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blogs as $blog)
                                <tr class="stagger-item">
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $blog->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-dark">{{ $blog->title }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern badge-modern-info">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $blog->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($blog->image)
                                            <img src="{{ asset('storage/' . $blog->image) }}"
                                                 alt="{{ $blog->title }}"
                                                 class="product-image hover-scale">
                                        @else
                                            <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($blog->is_active)
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
                                            <a href="{{ route('blog.edit', $blog->id) }}"
                                               class="action-btn action-btn-warning hover-glow">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-none d-md-inline">Cập nhật</span>
                                            </a>

                                            <!-- Delete -->
                                            <form action="{{ route('blog.destroy', $blog->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn action-btn-danger hover-glow"
                                                        onclick="return confirm('Are you sure?')">
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
    </div>
@endsection
