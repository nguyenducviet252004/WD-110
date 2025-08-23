@extends('Layout.Layout')

@section('title')
    Quản lý biến thể - {{ $product->name }}
@endsection

@section('content_admin')

    @if (session('success'))
        <div class="alert alert-success text-center mt-5">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-5">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
        <div>
            <h1>Quản lý biến thể</h1>
            <h5 class="text-muted">Sản phẩm: <strong>{{ $product->name }}</strong></h5>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
            <a href="{{ route('products.show', $product->id) }}" class="btn btn-info me-2">
                <i class="fas fa-eye"></i> Xem sản phẩm
            </a>
        </div>
    </div>

    <!-- Product Summary Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2">
                    @if ($product->img_thumb)
                        <img src="{{ Storage::url($product->img_thumb) }}" alt="{{ $product->name }}"
                             class="img-fluid rounded" style="max-height: 100px;">
                    @endif
                </div>
                <div class="col-md-6">
                    <h5 class="mb-1">{{ $product->name }}</h5>
                    <p class="text-muted mb-1">Danh mục: {{ $product->categories->name ?? 'Không có' }}</p>
                    <p class="text-muted mb-0">Slug: <code>{{ $product->slug }}</code></p>
                </div>
                <div class="col-md-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <strong class="d-block">{{ $variants->total() }}</strong>
                            <small class="text-muted">Biến thể</small>
                        </div>
                        <div class="col-4">
                            <strong class="d-block">{{ $product->total_quantity ?? 0 }}</strong>
                            <small class="text-muted">Tổng tồn kho</small>
                        </div>
                        <div class="col-4">
                            @if ($product->variants->count() > 0)
                                <strong class="d-block">{{ number_format($product->min_price) }}đ - {{ number_format($product->max_price) }}đ</strong>
                                <small class="text-muted">Khoảng giá</small>
                            @else
                                <strong class="d-block text-warning">Chưa có giá</strong>
                                <small class="text-muted">Khoảng giá</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ route('product-variants.create', $product->id) }}" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-plus"></i> Thêm biến thể mới
            </a>
        </div>
        <div class="col-md-6 text-end">
            @if ($variants->total() == 0)
                <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
                    <i class="fas fa-magic"></i> Tạo tất cả biến thể
                </button>
            @endif
        </div>
    </div>

    <!-- Variants Table -->
    @if ($variants->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Danh sách biến thể ({{ $variants->total() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Kích thước</th>
                                <th>Màu sắc</th>
                                <th>Giá gốc</th>
                                <th>Giá sale</th>
                                <th>Giá hiệu lực</th>
                                <th>Số lượng</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($variants as $variant)
                                <tr>
                                    <td>{{ $variant->id }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $variant->size->size ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $variant->color->name_color ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ number_format($variant->price) }}đ</td>
                                    <td>
                                        @if ($variant->price_sale)
                                            <span class="text-danger fw-bold">{{ number_format($variant->price_sale) }}đ</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($variant->effective_price) }}đ</strong>
                                    </td>
                                    <td>
                                        @if ($variant->quantity > 0)
                                            <span class="badge bg-success">{{ $variant->quantity }}</span>
                                        @else
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($variant->quantity > 0)
                                            <span class="badge bg-success">Có sẵn</span>
                                        @else
                                            <span class="badge bg-warning">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('product-variants.edit', [$product->id, $variant->id]) }}"
                                               class="btn btn-sm btn-warning" title="Sửa biến thể">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('product-variants.destroy', [$product->id, $variant->id]) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa biến thể này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa biến thể">
                                                    <i class="fas fa-trash"></i> Xóa
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
        <div class="d-flex justify-content-center mt-4">
            {{ $variants->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5>Chưa có biến thể nào</h5>
                <p class="text-muted">Hãy thêm biến thể để khách hàng có thể chọn size và màu sắc.</p>
                <a href="{{ route('product-variants.create', $product->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus"></i> Thêm biến thể đầu tiên
                </a>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
                    <i class="fas fa-magic"></i> Tạo tất cả biến thể
                </button>
            </div>
        </div>
    @endif

    <!-- Bulk Create Modal -->
    <div class="modal fade" id="bulkCreateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('product-variants.bulk-create', $product->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tạo tất cả biến thể</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Tạo biến thể cho tất cả combinations của size và màu có trong hệ thống với cùng giá và số lượng.</p>

                        <div class="mb-3">
                            <label for="bulk_price" class="form-label">Giá cơ bản <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="bulk_price" class="form-control"
                                   min="0" step="1000" required placeholder="VD: 150000">
                        </div>

                        <div class="mb-3">
                            <label for="bulk_quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="bulk_quantity" class="form-control"
                                   min="0" required placeholder="VD: 20">
                        </div>

                        <div class="mb-3">
                            <label for="bulk_price_sale" class="form-label">Giá sale (không bắt buộc)</label>
                            <input type="number" name="price_sale" id="bulk_price_sale" class="form-control"
                                   min="0" step="1000" placeholder="VD: 120000">
                        </div>

                        <div class="alert alert-info">
                            <small><i class="fas fa-info-circle"></i> Chỉ tạo các biến thể chưa tồn tại. Biến thể đã có sẽ không bị thay đổi.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-magic"></i> Tạo tất cả
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // Validation for bulk create modal
    document.getElementById('bulk_price_sale').addEventListener('input', function() {
        const price = parseInt(document.getElementById('bulk_price').value) || 0;
        const priceSale = parseInt(this.value) || 0;

        if (priceSale > 0 && priceSale >= price) {
            this.setCustomValidity('Giá sale phải nhỏ hơn giá gốc');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endsection
