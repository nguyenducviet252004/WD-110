@extends('Layout.Layout')

@section('title')
    Thêm biến thể mới - {{ $product->name }}
@endsection

@section('content_admin')

    @if (session('error'))
        <div class="alert alert-danger modern-alert mt-5">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Modern Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="m-0">
                        <i class="fas fa-plus-circle me-3"></i>
                        Thêm biến thể mới
                    </h1>
                    <h5 class="text-muted mt-2">{{ $product->name }}</h5>
                    <nav aria-label="breadcrumb" class="fade-in-up">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('products.index') }}" class="text-decoration-none">
                                    <i class="fas fa-tshirt me-1"></i>Sản phẩm
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('product-variants.index', $product->id) }}" class="text-decoration-none">
                                    <i class="fas fa-layer-group me-1"></i>Biến thể
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-plus me-1"></i>Thêm mới
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4 text-end">
                    <div class="action-buttons">
                        <a href="{{ route('product-variants.index', $product->id) }}" class="btn btn-modern btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Modern Form -->
            <div class="col-md-8">
                <div class="modern-card hover-lift">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title">
                            <i class="fas fa-plus-circle me-2"></i>
                            Thông tin biến thể mới
                        </h5>
                    </div>
                    <div class="modern-card-body">
                        <form action="{{ route('product-variants.store', $product->id) }}" method="POST" novalidate>
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="size_id" class="form-label-modern">Kích thước <span class="text-danger">*</span></label>
                                        <select name="size_id" id="size_id" class="form-control form-control-modern" required>
                                            <option value="">Chọn kích thước</option>
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>
                                                    {{ $size->size }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('size_id')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="color_id" class="form-label-modern">Màu sắc <span class="text-danger">*</span></label>
                                        <select name="color_id" id="color_id" class="form-control form-control-modern" required>
                                            <option value="">Chọn màu sắc</option>
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                                                    {{ $color->name_color }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('color_id')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="price" class="form-label-modern">Giá gốc <span class="text-danger">*</span></label>
                                        <div class="input-group-modern">
                                            <input type="number" name="price" id="price" class="form-control form-control-modern"
                                                   min="0" step="1000" required value="{{ old('price') }}"
                                                   placeholder="VD: 150000">
                                            <span class="input-group-text-modern">đ</span>
                                        </div>
                                        @error('price')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="price_sale" class="form-label-modern">Giá sale (không bắt buộc)</label>
                                        <div class="input-group-modern">
                                            <input type="number" name="price_sale" id="price_sale" class="form-control form-control-modern"
                                                   min="0" step="1000" value="{{ old('price_sale') }}"
                                                   placeholder="VD: 120000">
                                            <span class="input-group-text-modern">đ</span>
                                        </div>
                                        @error('price_sale')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Để trống nếu không có giá khuyến mãi</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-modern">
                                <label for="quantity" class="form-label-modern">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="quantity" class="form-control form-control-modern"
                                       min="0" required value="{{ old('quantity') }}" placeholder="VD: 20">
                                @error('quantity')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-actions-modern">
                                <a href="{{ route('product-variants.index', $product->id) }}" class="btn btn-modern btn-secondary">
                                    <i class="fas fa-times me-2"></i>Hủy
                                </a>
                                <button type="submit" class="btn btn-modern btn-primary">
                                    <i class="fas fa-save me-2"></i>Lưu biến thể
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modern Sidebar -->
            <div class="col-md-4">
                <!-- Product Info -->
                <div class="modern-card hover-lift mb-4">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin sản phẩm
                        </h5>
                    </div>
                    <div class="modern-card-body text-center">
                        <div class="stat-icon-modern bg-primary mb-3">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <h6 class="fw-bold mb-3">{{ $product->name }}</h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-value-modern text-info">{{ $product->variants->count() }}</div>
                                <small class="stat-label-modern">Biến thể</small>
                            </div>
                            <div class="col-4">
                                <div class="stat-value-modern text-success">{{ number_format($product->price) }}đ</div>
                                <small class="stat-label-modern">Giá gốc</small>
                            </div>
                            <div class="col-4">
                                <div class="stat-value-modern text-warning">{{ $product->quantity }}</div>
                                <small class="stat-label-modern">Tồn kho</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Existing Variants -->
                @if ($product->variants->count() > 0)
                    <div class="modern-card hover-lift">
                        <div class="modern-card-header">
                            <h5 class="modern-card-title">
                                <i class="fas fa-list me-2"></i>
                                Biến thể đã có ({{ $product->variants->count() }})
                            </h5>
                        </div>
                        <div class="modern-card-body">
                            <div class="small">
                                @foreach ($product->variants as $variant)
                                    <div class="d-flex justify-content-between mb-2 p-2 bg-light rounded">
                                        <span class="fw-medium">{{ $variant->size->size ?? 'N/A' }} - {{ $variant->color->name_color ?? 'N/A' }}</span>
                                        <span class="text-success fw-bold">{{ number_format($variant->effective_price) }}đ</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="modern-alert mt-3">
                                <small><i class="fas fa-info-circle me-1"></i> Không thể tạo biến thể trùng lặp</small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="modern-card hover-lift">
                        <div class="modern-card-body text-center">
                            <div class="stat-icon-modern bg-warning mb-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h6 class="fw-bold mb-2">Chưa có biến thể</h6>
                            <p class="text-muted small mb-0">Đây sẽ là biến thể đầu tiên của sản phẩm này.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sizeSelect = document.getElementById('size_id');
        const colorSelect = document.getElementById('color_id');
        const priceInput = document.getElementById('price');
        const priceSaleInput = document.getElementById('price_sale');
        const quantityInput = document.getElementById('quantity');

        // Existing variants for duplicate check
        const existingVariants = @json($product->variants->map(function($v) {
            return $v->size_id . '-' . $v->color_id;
        })->values());

        // Validate price sale
        function validatePriceSale() {
            const price = parseInt(priceInput.value) || 0;
            const priceSale = parseInt(priceSaleInput.value) || 0;

            if (priceSale > 0 && priceSale >= price) {
                priceSaleInput.setCustomValidity('Giá sale phải nhỏ hơn giá gốc');
            } else {
                priceSaleInput.setCustomValidity('');
            }
        }

        // Check for duplicate combination
        function checkDuplicate() {
            const sizeId = sizeSelect.value;
            const colorId = colorSelect.value;

            if (sizeId && colorId) {
                const combination = sizeId + '-' + colorId;
                if (existingVariants.includes(combination)) {
                    sizeSelect.setCustomValidity('Biến thể này đã tồn tại');
                    colorSelect.setCustomValidity('Biến thể này đã tồn tại');
                } else {
                    sizeSelect.setCustomValidity('');
                    colorSelect.setCustomValidity('');
                }
            } else {
                sizeSelect.setCustomValidity('');
                colorSelect.setCustomValidity('');
            }
        }

        // Event listeners
        sizeSelect.addEventListener('change', checkDuplicate);
        colorSelect.addEventListener('change', checkDuplicate);
        priceInput.addEventListener('input', validatePriceSale);
        priceSaleInput.addEventListener('input', validatePriceSale);

        // Initial validation
        validatePriceSale();
        checkDuplicate();
    });
</script>
@endsection
