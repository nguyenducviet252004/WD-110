@extends('Layout.Layout')

@section('title')
    Thêm mới sản phẩm
@endsection

@section('content_admin')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <style>
        .btn {
            font-size: 1rem;
            padding: 8px 18px;
            min-width: 110px;
            border-radius: 4px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: background 0.2s, color 0.2s;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #495057;
        }
        .btn-danger {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }
        .btn-danger:hover {
            background-color: #a71d2a;
        }
        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 2px #007bff33;
        }
        .variant-item .btn-danger {
            min-width: 120px;
        }
    </style>

    <!-- Debug information -->
    <!-- <div class="alert alert-info">
        <h5>Debug Info:</h5>
        <p>Sizes: {{ print_r($sizes->toArray(), true) }}</p>
        <p>Colors: {{ print_r($colors->toArray(), true) }}</p>
    </div> -->

    <h1 class="text-center mt-5">Thêm mới sản phẩm </h1>

    <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        <div id="form-errors" class="alert alert-danger d-none"></div>

        <div class="mb-3">
            <label for="name">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" >
            <div class="text-danger error-message" id="error-name"></div>
        </div>

        <div class="mb-3">
            <label for="thumb_image">Ảnh đại diện</label>
            <input type="file" name="thumb_image" class="form-control" accept="image/*" id="thumb_image" >
            <div class="text-danger error-message" id="error-thumb_image"></div>
        </div>

        <div class="mb-3">
            <label for="price_regular">Giá gốc</label>
            <input type="number" name="price_regular" step="0.01" value="{{ old('price_regular') }}" class="form-control" >
            <div class="text-danger error-message" id="error-price_regular"></div>
        </div>

        <div class="mb-3">
            <label for="price_sale">Giá khuyến mãi</label>
            <input type="number" name="price_sale" step="0.01" value="{{ old('price_sale') }}" class="form-control" >
            <div class="text-danger error-message" id="error-price_sale"></div>
        </div>

        <div class="mb-3">
            <label for="description">Mô tả ngắn</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            <div class="text-danger error-message" id="error-description"></div>
        </div>

        <div class="mb-3">
            <label for="content">Nội dung chi tiết</label>
            <textarea name="content" class="form-control" rows="10">{{ old('content') }}</textarea>
            <div class="text-danger error-message" id="error-content"></div>
        </div>

        <div class="mb-3">
            <label for="category_id">Danh mục</label>
            <select name="category_id" id="category_id" class="form-control" >
                @foreach ($categories as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            <div class="text-danger error-message" id="error-category_id"></div>
        </div>

        <div class="mb-3">
            <h4>Biến thể sản phẩm</h4>
            <div id="variants-container">
                <div class="variant-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Kích thước:</label>
                            <select name="variants[0][product_size_id]" class="form-control" >
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message variant-error-size"></div>
                        </div>
                        <div class="col-md-3">
                            <label>Màu sắc:</label>
                            <select name="variants[0][product_color_id]" class="form-control" >
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message variant-error-color"></div>
                        </div>
                        <div class="col-md-3">
                            <label>Số lượng:</label>
                            <input type="number" name="variants[0][quantity]" class="form-control"  min="0">
                            <div class="text-danger error-message variant-error-quantity"></div>
                        </div>
                        <div class="col-md-3">
                            <label>Ảnh biến thể:</label>
                            <input type="file" name="variants[0][image]" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addVariant()">Thêm biến thể</button>
        </div>

        <div class="mb-3">
            <label for="is_active">Trạng thái hoạt động:</label>
            <input type="checkbox" name="is_active" value="1" checked>
        </div>

        <div class="text-center mb-5 mt-3">
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>

    <script>
        let variantCount = 1;

        function addVariant() {
            const container = document.getElementById('variants-container');
            const newVariant = document.createElement('div');
            newVariant.className = 'variant-item border p-3 mb-3';
            newVariant.innerHTML = `
                <div class="row">
                    <div class="col-md-3">
                        <label>Kích thước:</label>
                        <select name="variants[${variantCount}][product_size_id]" class="form-control" >
                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Màu sắc:</label>
                        <select name="variants[${variantCount}][product_color_id]" class="form-control" >
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Số lượng:</label>
                        <input type="number" name="variants[${variantCount}][quantity]" class="form-control"  min="0">
                    </div>
                    <div class="col-md-3">
                        <label>Ảnh biến thể:</label>
                        <input type="file" name="variants[${variantCount}][image]" class="form-control" accept="image/*">
                    </div>
                </div>
                <button type="button" class="btn btn-danger mt-2" onclick="removeVariant(this)">Xóa biến thể</button>
            `;
            container.appendChild(newVariant);
            variantCount++;
        }

        function removeVariant(button) {
            button.parentElement.remove();
        }

        document.getElementById('productForm').addEventListener('submit', function(e) {
            let hasError = false;
            // Xóa lỗi cũ
            document.querySelectorAll('.error-message').forEach(el => el.innerHTML = '');
            // Validate product name
            if (!this.name.value.trim()) {
                document.getElementById('error-name').innerText = 'Vui lòng nhập tên sản phẩm.';
                hasError = true;
            }
            if (!this.thumb_image.value) {
                document.getElementById('error-thumb_image').innerText = 'Vui lòng chọn ảnh đại diện.';
                hasError = true;
            }
            if (!this.price_regular.value) {
                document.getElementById('error-price_regular').innerText = 'Vui lòng nhập giá gốc.';
                hasError = true;
            }
            if (!this.price_sale.value) {
                document.getElementById('error-price_sale').innerText = 'Vui lòng nhập giá khuyến mãi.';
                hasError = true;
            }
            if (!this.description.value.trim()) {
                document.getElementById('error-description').innerText = 'Vui lòng nhập mô tả ngắn.';
                hasError = true;
            }
            if (!this.content.value.trim()) {
                document.getElementById('error-content').innerText = 'Vui lòng nhập nội dung chi tiết.';
                hasError = true;
            }
            if (!this.category_id.value) {
                document.getElementById('error-category_id').innerText = 'Vui lòng chọn danh mục.';
                hasError = true;
            }
            // Validate variants
            let variantItems = document.querySelectorAll('.variant-item');
            if (variantItems.length === 0) {
                alert('Vui lòng thêm ít nhất một biến thể sản phẩm.');
                hasError = true;
            } else {
                variantItems.forEach(function(item, idx) {
                    let size = item.querySelector('select[name^="variants"][name$="[product_size_id]"]');
                    let color = item.querySelector('select[name^="variants"][name$="[product_color_id]"]');
                    let quantity = item.querySelector('input[name^="variants"][name$="[quantity]"]');
                    if (!size || !size.value) {
                        item.querySelector('.variant-error-size').innerText = 'Chọn kích thước!';
                        hasError = true;
                    }
                    if (!color || !color.value) {
                        item.querySelector('.variant-error-color').innerText = 'Chọn màu sắc!';
                        hasError = true;
                    }
                    if (!quantity || !quantity.value) {
                        item.querySelector('.variant-error-quantity').innerText = 'Nhập số lượng!';
                        hasError = true;
                    }
                });
            }
            if (hasError) {
                e.preventDefault();
            }
        });
    </script>
@endsection