@extends('Layout.Layout')

@section('title')
    Thêm biến thể mới - {{ $product->name }}
@endsection

@section('content_admin')

    @if (session('error'))
        <div class="alert alert-danger mt-5">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
        <div>
            <h1>Thêm biến thể mới</h1>
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
                        Đã có: {{ $product->variants->count() }} biến thể
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
                    <form action="{{ route('product-variants.store', $product->id) }}" method="POST" novalidate>
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="size_id" class="form-label">Kích thước <span class="text-danger">*</span></label>
                                    <select name="size_id" id="size_id" class="form-control" required>
                                        <option value="">Chọn kích thước</option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>
                                                {{ $size->size }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('size_id')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color_id" class="form-label">Màu sắc <span class="text-danger">*</span></label>
                                    <select name="color_id" id="color_id" class="form-control" required>
                                        <option value="">Chọn màu sắc</option>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                                                {{ $color->name_color }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('color_id')
                                        <div class="text-danger mt-1">{{ $message }}</div>
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
                                               min="0" step="1000" required value="{{ old('price') }}"
                                               placeholder="VD: 150000">
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    @error('price')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price_sale" class="form-label">Giá sale (không bắt buộc)</label>
                                    <div class="input-group">
                                        <input type="number" name="price_sale" id="price_sale" class="form-control"
                                               min="0" step="1000" value="{{ old('price_sale') }}"
                                               placeholder="VD: 120000">
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    @error('price_sale')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Để trống nếu không có giá khuyến mãi</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                   min="0" required value="{{ old('quantity') }}" placeholder="VD: 20">
                            @error('quantity')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('product-variants.index', $product->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu biến thể
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview & Existing Variants -->
        <div class="col-md-4">
            

            <!-- Existing Variants Warning -->
            @if ($product->variants->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Biến thể đã có ({{ $product->variants->count() }})</h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            @foreach ($product->variants as $variant)
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ $variant->size->size ?? 'N/A' }} - {{ $variant->color->name_color ?? 'N/A' }}</span>
                                    <span class="text-success">{{ number_format($variant->effective_price) }}đ</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="alert alert-info mt-2 mb-0">
                            <small><i class="fas fa-info-circle"></i> Không thể tạo biến thể trùng lặp</small>
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
        // Validate price sale
        function validatePriceSale() {
            const priceInput = document.getElementById('price');
            const priceSaleInput = document.getElementById('price_sale');
            const price = parseInt(priceInput.value) || 0;
            const priceSale = parseInt(priceSaleInput.value) || 0;

            if (priceSale > 0 && priceSale >= price) {
                priceSaleInput.setCustomValidity('Giá sale phải nhỏ hơn giá gốc');
            } else {
                priceSaleInput.setCustomValidity('');
            }
        }

        const priceInput = document.getElementById('price');
        const priceSaleInput = document.getElementById('price_sale');

        if (priceInput && priceSaleInput) {
            priceInput.addEventListener('input', validatePriceSale);
            priceSaleInput.addEventListener('input', validatePriceSale);
        }

        // Initial validation
        if (priceInput && priceSaleInput) {
            validatePriceSale();
        }
    });
</script>
@endsection
