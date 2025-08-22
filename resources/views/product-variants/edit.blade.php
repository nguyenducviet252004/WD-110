@extends('Layout.Layout')

@section('title')
    Sửa biến thể - {{ $product->name }}
@endsection

@section('content_admin')

    @if (session('error'))
        <div class="alert alert-danger mt-5">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
        <div>
            <h1>Sửa biến thể</h1>
            <h5 class="text-muted">Sản phẩm: <strong>{{ $product->name }}</strong></h5>
        </div>
        <div>
            <a href="{{ route('product-variants.index', $product->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Product Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2">
                    @if ($product->img_thumb)
                        <img src="{{ Storage::url($product->img_thumb) }}" alt="{{ $product->name }}"
                             class="img-fluid rounded" style="max-height: 80px;">
                    @endif
                </div>
                <div class="col-md-10">
                    <h6 class="mb-1">{{ $product->name }}</h6>
                    <p class="text-muted mb-0">
                        Danh mục: {{ $product->categories->name ?? 'Không có' }} |
                        Biến thể ID: {{ $variant->id }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin biến thể</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('product-variants.update', [$product->id, $variant->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="size_id" class="form-label">Kích thước <span class="text-danger">*</span></label>
                                    <select name="size_id" id="size_id" class="form-control" required>
                                        <option value="">Chọn kích thước</option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}"
                                                {{ (old('size_id', $variant->size_id) == $size->id) ? 'selected' : '' }}>
                                                {{ $size->size }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('size_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color_id" class="form-label">Màu sắc <span class="text-danger">*</span></label>
                                    <select name="color_id" id="color_id" class="form-control" required>
                                        <option value="">Chọn màu sắc</option>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}"
                                                {{ (old('color_id', $variant->color_id) == $color->id) ? 'selected' : '' }}>
                                                {{ $color->name_color }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('color_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá gốc <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="price" id="price" class="form-control"
                                               min="0" step="1000" required
                                               value="{{ old('price', $variant->price) }}"
                                               placeholder="VD: 150000">
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price_sale" class="form-label">Giá sale (không bắt buộc)</label>
                                    <div class="input-group">
                                        <input type="number" name="price_sale" id="price_sale" class="form-control"
                                               min="0" step="1000"
                                               value="{{ old('price_sale', $variant->price_sale) }}"
                                               placeholder="VD: 120000">
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    @error('price_sale')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Để trống nếu không có giá khuyến mãi</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                   min="0" required value="{{ old('quantity', $variant->quantity) }}"
                                   placeholder="VD: 20">
                            @error('quantity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('product-variants.index', $product->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật biến thể
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Current Variant Info & Preview -->
        <div class="col-md-4">
            <!-- Current Variant Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Thông tin hiện tại</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-tshirt fa-2x text-primary mb-2"></i>
                        <div class="mb-2">
                            <span class="badge bg-info me-1">{{ $variant->size->size ?? 'N/A' }}</span>
                            <span class="badge bg-secondary">{{ $variant->color->name_color ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-2">
                            @if ($variant->price_sale)
                                <span class="text-decoration-line-through text-muted">{{ number_format($variant->price) }}đ</span>
                                <br><strong class="text-danger">{{ number_format($variant->price_sale) }}đ</strong>
                            @else
                                <strong>{{ number_format($variant->price) }}đ</strong>
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
            </div>

            <!-- Preview Changes -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Xem trước thay đổi</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-eye fa-2x text-muted mb-2"></i>
                        <div id="preview-combination">
                            <span class="text-muted">Thông tin sẽ hiển thị khi bạn thay đổi</span>
                        </div>
                        <div id="preview-price" class="mt-2"></div>
                    </div>
                </div>
            </div>

            <!-- Other Variants -->
            @if ($product->variants->where('id', '!=', $variant->id)->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Biến thể khác ({{ $product->variants->where('id', '!=', $variant->id)->count() }})</h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            @foreach ($product->variants->where('id', '!=', $variant->id) as $otherVariant)
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ $otherVariant->size->size ?? 'N/A' }} - {{ $otherVariant->color->name_color ?? 'N/A' }}</span>
                                    <span class="text-success">{{ number_format($otherVariant->effective_price) }}đ</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="alert alert-info mt-2 mb-0">
                            <small><i class="fas fa-info-circle"></i> Không thể trùng với biến thể khác</small>
                        </div>
                    </div>
                </div>
            @endif
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
        const previewCombination = document.getElementById('preview-combination');
        const previewPrice = document.getElementById('preview-price');

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

        function updatePreview() {
            const sizeText = sizeSelect.options[sizeSelect.selectedIndex]?.text || '';
            const colorText = colorSelect.options[colorSelect.selectedIndex]?.text || '';
            const price = parseInt(priceInput.value) || 0;
            const priceSale = parseInt(priceSaleInput.value) || 0;
            const quantity = parseInt(quantityInput.value) || 0;

            // Check if anything changed
            const hasChanges = (
                sizeSelect.value != currentVariant.size_id ||
                colorSelect.value != currentVariant.color_id ||
                price != currentVariant.price ||
                priceSale != currentVariant.price_sale ||
                quantity != currentVariant.quantity
            );

            if (!hasChanges) {
                previewCombination.innerHTML = '<span class="text-muted">Chưa có thay đổi</span>';
                previewPrice.innerHTML = '';
                return;
            }

            if (sizeText && colorText && sizeText !== 'Chọn kích thước' && colorText !== 'Chọn màu sắc') {
                const combination = sizeSelect.value + '-' + colorSelect.value;

                // Check for duplicates (excluding current variant)
                if (otherVariants.includes(combination)) {
                    previewCombination.innerHTML = `
                        <span class="text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            ${sizeText} - ${colorText}
                            <br><small>Trùng với biến thể khác!</small>
                        </span>
                    `;
                    previewPrice.innerHTML = '';
                } else {
                    previewCombination.innerHTML = `
                        <span class="text-warning">
                            <i class="fas fa-edit"></i>
                            ${sizeText} - ${colorText}
                            <br><small>Thay đổi mới</small>
                        </span>
                    `;

                    if (price > 0) {
                        let priceDisplay = `<strong>${price.toLocaleString()}đ</strong>`;
                        if (priceSale > 0) {
                            priceDisplay = `
                                <span class="text-decoration-line-through text-muted">${price.toLocaleString()}đ</span>
                                <br><strong class="text-danger">${priceSale.toLocaleString()}đ</strong>
                            `;
                        }
                        priceDisplay += `<br><small class="text-muted">${quantity} sản phẩm</small>`;
                        previewPrice.innerHTML = priceDisplay;
                    } else {
                        previewPrice.innerHTML = '';
                    }
                }
            } else {
                previewCombination.innerHTML = '<span class="text-muted">Chọn size và màu để xem trước</span>';
                previewPrice.innerHTML = '';
            }
        }

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
        sizeSelect.addEventListener('change', updatePreview);
        colorSelect.addEventListener('change', updatePreview);
        priceInput.addEventListener('input', function() {
            updatePreview();
            validatePriceSale();
        });
        priceSaleInput.addEventListener('input', function() {
            updatePreview();
            validatePriceSale();
        });
        quantityInput.addEventListener('input', updatePreview);

        // Initial preview
        updatePreview();
    });
</script>
@endsection
