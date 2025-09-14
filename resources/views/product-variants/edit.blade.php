@extends('Layout.Layout')

@section('title')
    Sửa biến thể - {{ $product->name }}
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
                        <i class="fas fa-edit me-3"></i>
                        Sửa biến thể
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
                                <i class="fas fa-edit me-1"></i>Sửa
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
                            <i class="fas fa-layer-group me-2"></i>
                            Thông tin biến thể
                        </h5>
                    </div>
                    <div class="modern-card-body">
                        <form action="{{ route('product-variants.update', [$product->id, $variant->id]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="size_id" class="form-label-modern">Kích thước <span class="text-danger">*</span></label>
                                        <select name="size_id" id="size_id" class="form-control form-control-modern" required>
                                            <option value="">Chọn kích thước</option>
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}"
                                                    {{ (old('size_id', $variant->size_id) == $size->id) ? 'selected' : '' }}>
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
                                                <option value="{{ $color->id }}"
                                                    {{ (old('color_id', $variant->color_id) == $color->id) ? 'selected' : '' }}>
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
                                                   min="0" step="1000" required
                                                   value="{{ old('price', $variant->price) }}"
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
                                                   min="0" step="1000"
                                                   value="{{ old('price_sale', $variant->price_sale) }}"
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
                                       min="0" required value="{{ old('quantity', $variant->quantity) }}"
                                       placeholder="VD: 20">
                                @error('quantity')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-actions-modern">
                                <a href="{{ route('product-variants.index', $product->id) }}" class="btn btn-modern btn-secondary">
                                    <i class="fas fa-times me-2"></i>Hủy
                                </a>
                                <button type="submit" class="btn btn-modern btn-primary">
                                    <i class="fas fa-save me-2"></i>Cập nhật biến thể
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modern Sidebar -->
            <div class="col-md-4">
                <!-- Current Variant Info -->
                <div class="modern-card hover-lift mb-4">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin hiện tại
                        </h5>
                    </div>
                    <div class="modern-card-body text-center">
                        <div class="stat-icon-modern bg-primary mb-3">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <div class="mb-3">
                            <span class="badge bg-info me-1">{{ $variant->size->size ?? 'N/A' }}</span>
                            <span class="badge bg-secondary">{{ $variant->color->name_color ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-3">
                            @if ($variant->price_sale)
                                <div class="price-modern">
                                    <span class="text-decoration-line-through text-muted">{{ number_format($variant->price) }}đ</span>
                                    <br><strong class="text-danger">{{ number_format($variant->price_sale) }}đ</strong>
                                </div>
                            @else
                                <div class="price-modern">
                                    <strong>{{ number_format($variant->price) }}đ</strong>
                                </div>
                            @endif
                        </div>
                        <div>
                            @if ($variant->quantity > 0)
                                <span class="badge bg-success">{{ $variant->quantity }} sản phẩm</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Other Variants -->
                @if ($product->variants->where('id', '!=', $variant->id)->count() > 0)
                    <div class="modern-card hover-lift">
                        <div class="modern-card-header">
                            <h5 class="modern-card-title">
                                <i class="fas fa-list me-2"></i>
                                Biến thể khác ({{ $product->variants->where('id', '!=', $variant->id)->count() }})
                            </h5>
                        </div>
                        <div class="modern-card-body">
                            <div class="small">
                                @foreach ($product->variants->where('id', '!=', $variant->id) as $otherVariant)
                                    <div class="d-flex justify-content-between mb-2 p-2 bg-light rounded">
                                        <span class="fw-medium">{{ $otherVariant->size->size ?? 'N/A' }} - {{ $otherVariant->color->name_color ?? 'N/A' }}</span>
                                        <span class="text-success fw-bold">{{ number_format($otherVariant->effective_price) }}đ</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="modern-alert mt-3">
                                <small><i class="fas fa-info-circle me-1"></i> Không thể trùng với biến thể khác</small>
                            </div>
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

        // Current variant data
        const currentVariant = {
            size_id: {{ $variant->size_id }},
            color_id: {{ $variant->color_id }},
            price: {{ $variant->price }},
            price_sale: {{ $variant->price_sale ?? 0 }},
            quantity: {{ $variant->quantity }}
        };

        // Other variants for duplicate check
        const otherVariants = @json($product->variants->where('id', '!=', $variant->id)->map(function($v) {
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

        // Event listeners
        priceInput.addEventListener('input', validatePriceSale);
        priceSaleInput.addEventListener('input', validatePriceSale);

        // Initial validation
        validatePriceSale();
    });
</script>
@endsection
