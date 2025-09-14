@extends('Layout.Layout')

@section('title')
    Chi tiết sản phẩm - {{ $product->name }}
@endsection

@section('content_admin')
    <!-- Modern Header with Actions -->
    <div class="content-header" style="padding-top: 1rem; padding-bottom: 1rem;">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="m-0">
                        <i class="fas fa-eye me-3"></i>
                        Chi tiết sản phẩm
                    </h1>
                    <nav aria-label="breadcrumb" class="fade-in-up">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('products.index') }}" class="text-decoration-none">
                                    <i class="fas fa-tshirt me-1"></i>Sản phẩm
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-tag me-1"></i>{{ $product->name }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4 text-end">
                    <div class="action-buttons fade-in-up">
                        <a href="{{ route('products.index') }}" class="btn-modern btn-modern-secondary hover-lift">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <a href="{{ route('product-variants.index', $product->id) }}" class="btn-modern btn-modern-primary hover-lift">
                            <i class="fas fa-list me-2"></i>Quản lý biến thể
                        </a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn-modern btn-modern-warning hover-lift">
                            <i class="fas fa-edit me-2"></i>Sửa sản phẩm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="padding-top: 0;">
        <div class="row">
            <!-- Product Basic Info -->
            <div class="col-lg-8 col-md-7">
                <div class="modern-card hover-lift mb-4">
                    <div class="modern-card-header">
                        <h3>
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin cơ bản
                        </h3>
                        <span class="badge badge-modern badge-modern-info bounce-in">
                            <i class="fas fa-tag me-1"></i>
                            ID: {{ $product->id }}
                        </span>
                    </div>
                    <div class="modern-card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if ($product->img_thumb)
                                    <div class="product-image-modern">
                                        <img src="{{ Storage::url($product->img_thumb) }}" alt="{{ $product->name }}"
                                             class="img-fluid rounded shadow-lg">
                                        <div class="image-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="no-image-placeholder">
                                        <i class="fas fa-image fa-4x text-muted"></i>
                                        <p class="text-muted mt-3">Chưa có ảnh</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h3 class="product-title-modern mb-4">{{ $product->name }}</h3>

                                <div class="product-details-modern">
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-hashtag me-2"></i>
                                            <strong>ID:</strong>
                                        </div>
                                        <div class="detail-value">
                                            <code class="code-modern">{{ $product->id }}</code>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-link me-2"></i>
                                            <strong>Slug:</strong>
                                        </div>
                                        <div class="detail-value">
                                            <code class="code-modern">{{ $product->slug ?? 'Chưa có slug' }}</code>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-list me-2"></i>
                                            <strong>Danh mục:</strong>
                                        </div>
                                        <div class="detail-value">
                                            <span class="badge badge-modern badge-modern-info">
                                                {{ $product->categories->name ?? 'Không có' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-toggle-on me-2"></i>
                                            <strong>Trạng thái:</strong>
                                        </div>
                                        <div class="detail-value">
                                            @if ($product->is_active)
                                                <span class="badge badge-modern badge-modern-success">
                                                    <i class="fas fa-eye me-1"></i>Đang hiển thị
                                                </span>
                                            @else
                                                <span class="badge badge-modern badge-modern-danger">
                                                    <i class="fas fa-eye-slash me-1"></i>Đã ẩn
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-eye me-2"></i>
                                            <strong>Lượt xem:</strong>
                                        </div>
                                        <div class="detail-value">
                                            <span class="view-count-modern">{{ number_format($product->view ?? 0) }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-calendar me-2"></i>
                                            <strong>Ngày tạo:</strong>
                                        </div>
                                        <div class="detail-value">
                                            <span class="date-modern">{{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($product->description)
                            <div class="description-section-modern mt-4">
                                <h6 class="description-title">
                                    <i class="fas fa-align-left me-2"></i>
                                    Mô tả sản phẩm
                                </h6>
                                <div class="description-content-modern">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats & Quick Actions -->
            <div class="col-lg-4 col-md-5">
                <!-- Statistics Card -->
                <div class="modern-card hover-lift mb-4">
                    <div class="modern-card-header">
                        <h3>
                            <i class="fas fa-chart-bar me-2"></i>
                            Thống kê biến thể
                        </h3>
                        <span class="badge badge-modern badge-modern-primary bounce-in">
                            <i class="fas fa-cubes me-1"></i>
                            {{ $product->variants->count() }} biến thể
                        </span>
                    </div>
                    <div class="modern-card-body">
                        <div class="stats-grid-modern">
                            <div class="stat-item-modern">
                                <div class="stat-icon-modern stat-icon-primary">
                                    <i class="fas fa-cubes"></i>
                                </div>
                                <div class="stat-content-modern">
                                    <h4 class="stat-number-modern">{{ $product->variants->count() }}</h4>
                                    <small class="stat-label-modern">Biến thể</small>
                                </div>
                            </div>

                            <div class="stat-item-modern">
                                <div class="stat-icon-modern stat-icon-success">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div class="stat-content-modern">
                                    <h4 class="stat-number-modern">{{ $product->total_quantity ?? 0 }}</h4>
                                    <small class="stat-label-modern">Tổng tồn kho</small>
                                </div>
                            </div>
                        </div>

                        @if ($product->variants->count() > 0)
                            <div class="price-range-modern">
                                <div class="price-range-header">
                                    <i class="fas fa-tags me-2"></i>
                                    <strong>Khoảng giá</strong>
                                </div>
                                <div class="price-range-content">
                                    <span class="price-range-text">
                                        {{ number_format($product->min_price) }}đ - {{ number_format($product->max_price) }}đ
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-modern alert-modern-warning text-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Chưa có biến thể nào</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="modern-card hover-lift">
                    <div class="modern-card-header">
                        <h3>
                            <i class="fas fa-bolt me-2"></i>
                            Thao tác nhanh
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="quick-actions-modern">
                            <a href="{{ route('product-variants.index', $product->id) }}" class="action-btn-modern action-btn-primary hover-glow">
                                <i class="fas fa-list me-2"></i>
                                Quản lý biến thể
                            </a>
                            <a href="{{ route('product-variants.create', $product->id) }}" class="action-btn-modern action-btn-success hover-glow">
                                <i class="fas fa-plus me-2"></i>
                                Thêm biến thể mới
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}" class="action-btn-modern action-btn-warning hover-glow">
                                <i class="fas fa-edit me-2"></i>
                                Sửa thông tin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gallery Images -->
        @if ($product->galleries->count() > 0)
            <div class="modern-card hover-lift mb-4">
                <div class="modern-card-header">
                    <h3>
                        <i class="fas fa-images me-2"></i>
                        Thư viện ảnh
                    </h3>
                    <span class="badge badge-modern badge-modern-info bounce-in">
                        <i class="fas fa-camera me-1"></i>
                        {{ $product->galleries->count() }} ảnh
                    </span>
                </div>
                <div class="modern-card-body">
                    <div class="gallery-grid-modern">
                        @foreach ($product->galleries as $gallery)
                            <div class="gallery-item-modern">
                                @php
                                    $imageSrc = str_starts_with($gallery->image_path, 'http')
                                        ? $gallery->image_path
                                        : Storage::url($gallery->image_path);
                                @endphp
                                <div class="gallery-image-container">
                                    <img src="{{ $imageSrc }}" alt="Gallery Image"
                                         class="gallery-image-modern"
                                         data-bs-toggle="modal" data-bs-target="#imageModal{{ $gallery->id }}">
                                    <div class="gallery-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>

                                <!-- Image Modal -->
                                <div class="modal fade" id="imageModal{{ $gallery->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content modern-modal">
                                            <div class="modal-header modern-modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-image me-2"></i>
                                                    Ảnh chi tiết
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ $imageSrc }}" alt="Gallery Image"
                                                     class="img-fluid rounded shadow-lg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Product Variants -->
        @if ($product->variants->count() > 0)
            <div class="modern-card hover-lift">
                <div class="modern-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>
                            <i class="fas fa-cubes me-2"></i>
                            Biến thể sản phẩm
                        </h3>
                        <div class="header-actions">
                            <span class="badge badge-modern badge-modern-primary bounce-in">
                                <i class="fas fa-list me-1"></i>
                                {{ $product->variants->count() }} biến thể
                            </span>
                            <a href="{{ route('product-variants.index', $product->id) }}" class="btn-modern btn-modern-primary btn-sm hover-lift">
                                <i class="fas fa-external-link-alt me-1"></i>Xem tất cả
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modern-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-ruler me-1"></i>Size</th>
                                    <th><i class="fas fa-palette me-1"></i>Màu</th>
                                    <th><i class="fas fa-tag me-1"></i>Giá gốc</th>
                                    <th><i class="fas fa-percentage me-1"></i>Giá sale</th>
                                    <th><i class="fas fa-boxes me-1"></i>Số lượng</th>
                                    <th><i class="fas fa-toggle-on me-1"></i>Trạng thái</th>
                                    <th><i class="fas fa-cogs me-1"></i>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->variants->take(10) as $variant)
                                    <tr class="stagger-item">
                                        <td>
                                            <span class="badge badge-modern badge-modern-info">
                                                {{ $variant->size->size ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-modern badge-modern-secondary">
                                                {{ $variant->color->name_color ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="price-modern">{{ number_format($variant->price) }}đ</span>
                                        </td>
                                        <td>
                                            @if ($variant->price_sale)
                                                <span class="price-sale-modern">{{ number_format($variant->price_sale) }}đ</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($variant->quantity > 0)
                                                <span class="badge badge-modern badge-modern-success">
                                                    <i class="fas fa-check me-1"></i>{{ $variant->quantity }}
                                                </span>
                                            @else
                                                <span class="badge badge-modern badge-modern-danger">
                                                    <i class="fas fa-times me-1"></i>0
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($variant->quantity > 0)
                                                <span class="badge badge-modern badge-modern-success">
                                                    <i class="fas fa-check-circle me-1"></i>Có sẵn
                                                </span>
                                            @else
                                                <span class="badge badge-modern badge-modern-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Hết hàng
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('product-variants.edit', [$product->id, $variant->id]) }}"
                                                   class="action-btn action-btn-warning hover-glow" title="Sửa biến thể">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="d-none d-md-inline">Sửa</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($product->variants->count() > 10)
                        <div class="modern-card-footer text-center">
                            <div class="pagination-info-modern">
                                <i class="fas fa-info-circle me-2"></i>
                                Hiển thị 10/{{ $product->variants->count() }} biến thể
                            </div>
                            <a href="{{ route('product-variants.index', $product->id) }}" class="btn-modern btn-modern-primary btn-sm hover-lift">
                                <i class="fas fa-external-link-alt me-1"></i>Xem tất cả
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="modern-card hover-lift">
                <div class="modern-card-body text-center py-5">
                    <div class="empty-state-modern">
                        <div class="empty-icon-modern">
                            <i class="fas fa-box-open fa-4x text-muted"></i>
                        </div>
                        <h4 class="empty-title-modern">Chưa có biến thể nào</h4>
                        <p class="empty-description-modern">
                            Sản phẩm này chưa có biến thể. Hãy thêm các biến thể để khách hàng có thể lựa chọn size và màu sắc.
                        </p>
                        <div class="empty-actions-modern">
                            <a href="{{ route('product-variants.create', $product->id) }}" class="btn-modern btn-modern-primary hover-lift">
                                <i class="fas fa-plus me-2"></i>Thêm biến thể đầu tiên
                            </a>
                            <a href="{{ route('product-variants.index', $product->id) }}" class="btn-modern btn-modern-success hover-lift">
                                <i class="fas fa-magic me-2"></i>Tạo tất cả biến thể
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
