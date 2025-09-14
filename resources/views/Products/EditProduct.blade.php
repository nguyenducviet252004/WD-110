@extends('Layout.Layout')

@section('title')
    Cập nhật sản phẩm - {{ $product->name }}
@endsection

@section('content_admin')

    @if (session('success'))
        <div class="alert alert-success modern-alert text-center mt-5">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

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
                        Cập nhật sản phẩm
                    </h1>
                    <h5 class="text-muted mt-2">{{ $product->name }}</h5>
                    <nav aria-label="breadcrumb" class="fade-in-up">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('products.index') }}" class="text-decoration-none">
                                    <i class="fas fa-tshirt me-1"></i>Sản phẩm
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-edit me-1"></i>Cập nhật
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4 text-end">
                    <div class="action-buttons">
                        <a href="{{ route('products.index') }}" class="btn btn-modern btn-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <a href="{{ route('product-variants.index', $product->id) }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-list me-2"></i>Quản lý biến thể
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="alert alert-info modern-alert mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Lưu ý:</strong> Để thay đổi giá và số lượng, hãy sử dụng
            <a href="{{ route('product-variants.index', $product->id) }}" class="alert-link">quản lý biến thể</a>.
        </div>
        <div class="row">
            <!-- Modern Form -->
            <div class="col-md-8">
                <div class="modern-card hover-lift">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin cơ bản
                        </h5>
                    </div>
                    <div class="modern-card-body">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <div class="form-group-modern">
                            <label for="name" class="form-label-modern">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-modern"
                                   value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
        </div>

        <div class="form-group-modern">
                            <label for="img_thumb" class="form-label-modern">Ảnh đại diện</label>
                            <div class="file-upload-modern">
                                <div class="form-control-modern" style="position: relative; height: 2.5rem; display: flex; align-items: center;">
                                    <div class="file-upload-text">Chọn tệp</div>
                                    <input type="file" name="img_thumb" id="img_thumb"
                                           accept="image/*" onchange="previewImage()"
                                           style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                </div>
                            </div>
                            @error('img_thumb')
                                <div class="error-message">{{ $message }}</div>
                            @enderror

                            @if ($product->img_thumb)
                                <div class="mt-3" id="currentImageContainer">
                                    <label class="form-label-modern">Ảnh hiện tại:</label>
                                    <div class="image-preview-modern">
                                        <img src="{{ Storage::url($product->img_thumb) }}" alt="Current Image"
                                             id="currentImage" class="rounded">
                                    </div>
                </div>
            @endif

                            <div id="imagePreviewContainer" style="display: none;" class="mt-3">
                                <label class="form-label-modern">Ảnh mới được chọn:</label>
                                <div class="image-preview-modern">
                                    <img id="imagePreview" src="" alt="Selected Image" class="rounded">
                                </div>
                            </div>
        </div>

        <div class="form-group-modern">
                            <label for="category_id" class="form-label-modern">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control form-control-modern" required>
                                <option value="">Chọn danh mục</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                    @endforeach
                            </select>
                            @error('category_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
        </div>

        <div class="form-group-modern">
                            <label for="description" class="form-label-modern">Mô tả</label>
                            <textarea name="description" id="description" class="form-control form-control-modern" rows="5"
                                      placeholder="Mô tả sản phẩm...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
        </div>

        <div class="form-group-modern">
                            <label for="images" class="form-label-modern">Thêm ảnh gallery mới</label>
                            <div class="file-upload-modern">
                                <div class="form-control-modern" style="position: relative; height: 2.5rem; display: flex; align-items: center;">
                                    <div class="file-upload-text">Chọn nhiều ảnh</div>
                                    <input type="file" id="image-input" name="images[]" multiple
                                           accept="image/*"
                                           style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                </div>
                            </div>
                            <div id="image-preview-container" class="gallery-preview-modern mt-3"></div>
                            <small class="text-muted">Chọn nhiều ảnh để thêm vào gallery</small>
                            @error('images.*')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
        </div>

        <div class="form-group-modern">
                            <div class="checkbox-modern">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                       value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">Hiển thị sản phẩm</label>
                            </div>
                            @error('is_active')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
        </div>

                        <div class="form-actions-modern">
                            <a href="{{ route('products.index') }}" class="btn btn-modern btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-modern btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật sản phẩm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modern Sidebar -->
        <div class="col-md-4">
            <!-- Product Stats -->
            <div class="modern-card hover-lift mb-4">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="fas fa-chart-bar me-2"></i>
                        Thống kê sản phẩm
                    </h5>
                </div>
                <div class="modern-card-body text-center">
                    <div class="row">
                        <div class="col-6">
                            <div class="stat-item-modern">
                                <div class="stat-icon-modern bg-primary">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <h5 class="stat-value-modern">{{ $product->variants->count() }}</h5>
                                <small class="stat-label-modern">Biến thể</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item-modern">
                                <div class="stat-icon-modern bg-success">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <h5 class="stat-value-modern">{{ $product->total_quantity ?? 0 }}</h5>
                                <small class="stat-label-modern">Tổng tồn kho</small>
                            </div>
                        </div>
                    </div>
                    @if ($product->variants->count() > 0)
                        <div class="mt-3">
                            <div class="price-range-modern">
                                {{ number_format($product->min_price) }}đ - {{ number_format($product->max_price) }}đ
                            </div>
                            <small class="text-muted">Khoảng giá</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gallery Management -->
            @if ($product->galleries->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Gallery hiện tại ({{ $product->galleries->count() }})</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($product->galleries as $gallery)
                                <div class="col-6 mb-3">
                                    <div class="position-relative">
                                        <img src="{{ $gallery->image_path }}" alt="Gallery Image"
                                             class="img-fluid rounded"
                                             style="width: 100%; height: 80px; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute delete-gallery-btn"
                                                style="top: 5px; right: 5px; font-size: 10px; padding: 2px 5px;"
                                                data-gallery-id="{{ $gallery->id }}"
                                                data-product-id="{{ $product->id }}">
                                            Xóa
                                        </button>
                                    </div>
                                </div>
            @endforeach
        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Chọn "Xóa" trên ảnh để xóa khỏi gallery
                        </small>
                    </div>
                </div>
            @endif
        </div>
        </div>

    <script>
        // Preview main image
        function previewImage() {
            const fileInput = document.getElementById('img_thumb');
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const currentImageContainer = document.getElementById('currentImageContainer');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                    if (currentImageContainer) {
                        currentImageContainer.style.opacity = '0.5';
                    }
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                imagePreviewContainer.style.display = 'none';
                if (currentImageContainer) {
                    currentImageContainer.style.opacity = '1';
                }
            }
        }

        // Preview multiple gallery images
        document.getElementById('image-input').addEventListener('change', function(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('image-preview-container');

            previewContainer.innerHTML = '';

            if (files.length > 0) {
                const title = document.createElement('div');
                title.innerHTML = `<strong>Ảnh mới sẽ thêm (${files.length}):</strong>`;
                title.className = 'mb-2';
                previewContainer.appendChild(title);

                const imageContainer = document.createElement('div');
                imageContainer.className = 'row';

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-6 mb-2';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                        img.className = 'img-fluid rounded';
                        img.style.width = '100%';
                        img.style.height = '60px';
                        img.style.objectFit = 'cover';

                        col.appendChild(img);
                        imageContainer.appendChild(col);
                };

                reader.readAsDataURL(file);
                }

                previewContainer.appendChild(imageContainer);
            }
        });

        // Handle delete gallery image
        document.querySelectorAll('.delete-gallery-btn').forEach(button => {
            button.addEventListener('click', function() {
                const galleryId = this.dataset.galleryId;
                const productId = this.dataset.productId;

                if (confirm('Bạn có chắc chắn muốn xóa ảnh này không?')) {
                    fetch(`/products/${productId}/galleries/${galleryId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('.col-6').remove(); // Remove the image from DOM
                            alert(data.message);
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi xóa ảnh.');
                    });
                }
            });
        });
    </script>

@endsection
