@extends('Layout.Layout')

@section('title')
    Danh sách sản phẩm
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/debug.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('css/modern-admin.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('css/adminlte-compatibility.css') }}?v={{ time() }}">
@endpush

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
                <i class="fas fa-box me-3"></i>
                Danh sách sản phẩm
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filter Section -->
        <div class="filter-section fade-in-up">
            <div class="filter-row">
                <div class="filter-group stagger-item">
                    <label class="filter-label">Sắp xếp theo giá</label>
                    <form method="GET" action="{{ route('products.index') }}">
                        <select name="price_order" class="form-control-modern" onchange="this.form.submit()">
                            <option value="">Tất cả</option>
                            <option value="asc" {{ request('price_order') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="desc" {{ request('price_order') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        </select>
                    </form>
                </div>
                <div class="filter-group stagger-item">
                    <label class="filter-label">Trạng thái</label>
                    <form method="GET" action="{{ route('products.index') }}">
                        <select name="is_active" class="form-control-modern" onchange="this.form.submit()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </form>
                </div>
                <div class="filter-group stagger-item">
                    <label class="filter-label">Mức giá</label>
                    <form method="GET" action="{{ route('products.index') }}">
                        <select name="price_range" class="form-control-modern" onchange="this.form.submit()">
                            <option value="">Tất cả mức giá</option>
                            <option value="under_200k" {{ request('price_range') == 'under_200k' ? 'selected' : '' }}>Dưới 200k</option>
                            <option value="200k_500k" {{ request('price_range') == '200k_500k' ? 'selected' : '' }}>200k - 500k</option>
                            <option value="over_500k" {{ request('price_range') == 'over_500k' ? 'selected' : '' }}>Trên 500k</option>
                        </select>
                    </form>
                </div>
                <div class="filter-group stagger-item">
                    <a href="{{ route('products.create') }}" class="btn-modern btn-modern-success hover-lift">
                        <i class="fas fa-plus"></i> Thêm sản phẩm mới
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-list me-2"></i>
                    Danh sách sản phẩm
                </h3>
                <span class="badge badge-modern badge-modern-info bounce-in">
                    {{ $products->total() }} sản phẩm
                </span>
            </div>
            <div class="modern-card-body">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Slug</th>
                                <th>Danh mục</th>
                                <th>Tồn kho</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr class="stagger-item">
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $product->id }}</span>
                                    </td>
                                    <td>
                                        @if ($product->img_thumb)
                                            <img src="{{ Storage::url($product->img_thumb) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-image hover-scale">
                                        @else
                                            <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-dark">{{ $product->name }}</strong>
                                            @if($product->variants->count() > 0)
                                                <small class="d-block text-muted">
                                                    {{ $product->variants->count() }} biến thể
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <code class="text-danger">{{ $product->slug ?? 'Chưa có slug' }}</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern badge-modern-info">
                                            {{ $product->categories->name ?? 'Không có' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern badge-modern-warning">
                                            <i class="fas fa-boxes me-1"></i>
                                            {{ $product->total_quantity ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($product->is_active)
                                            <span class="badge badge-modern badge-modern-success">
                                                <i class="fas fa-eye me-1"></i>
                                                Hiển thị
                                            </span>
                                        @else
                                            <span class="badge badge-modern badge-modern-danger">
                                                <i class="fas fa-eye-slash me-1"></i>
                                                Ẩn
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Xem chi tiết -->
                                            <a href="{{ route('products.show', $product->id) }}"
                                               class="action-btn action-btn-info hover-glow"
                                               data-bs-toggle="tooltip"
                                               title="Xem chi tiết sản phẩm">
                                                <i class="fas fa-eye"></i>
                                                <span class="d-none d-md-inline">Chi tiết</span>
                                            </a>

                                            <!-- Toggle trạng thái -->
                                            <form method="POST" action="{{ route('products.toggle-status', $product->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit"
                                                        class="action-btn action-btn-secondary hover-glow"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $product->is_active ? 'Ẩn sản phẩm' : 'Hiển thị sản phẩm' }}">
                                                    @if ($product->is_active)
                                                        <i class="fas fa-eye-slash"></i>
                                                        <span class="d-none d-md-inline">Ẩn</span>
                                                    @else
                                                        <i class="fas fa-eye"></i>
                                                        <span class="d-none d-md-inline">Hiện</span>
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Xóa sản phẩm -->
                                            <form method="POST" action="{{ route('products.destroy', $product->id) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Tất cả biến thể cũng sẽ bị xóa!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn action-btn-danger hover-glow"
                                                        data-bs-toggle="tooltip"
                                                        title="Xóa sản phẩm vĩnh viễn">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="d-none d-md-inline">Xóa</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted fade-in-up">
                                            <i class="fas fa-box-open fa-3x mb-3"></i>
                                            <h5>Không có sản phẩm nào</h5>
                                            <p>Hãy thêm sản phẩm đầu tiên của bạn!</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-modern">
            {{ $products->appends(request()->query())->links() }}
        </div>

@endsection

@section('scripts')
<script>
    // Auto-submit forms when select changes
    document.querySelectorAll('select[onchange]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
