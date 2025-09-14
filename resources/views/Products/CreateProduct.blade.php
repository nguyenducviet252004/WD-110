@extends('Layout.Layout')

@section('title')
    Thêm mới sản phẩm
@endsection

@section('content_admin')
    @if (session('error'))
        <div class="alert alert-modern alert-modern-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-center">
                <i class="fas fa-plus-circle me-3"></i>
                Thêm mới sản phẩm
            </h1>
            <p class="text-center text-muted fade-in-up">
                <i class="fas fa-info-circle me-2"></i>
                Sau khi tạo sản phẩm, bạn sẽ có thể thêm các biến thể (variants) với giá và số lượng riêng biệt.
            </p>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Create Product Form -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-tshirt me-2"></i>
                    Thông tin sản phẩm
                </h3>
                <span class="badge badge-modern badge-modern-info bounce-in">
                    <i class="fas fa-edit me-1"></i>
                    Tạo mới
                </span>
            </div>
            <div class="modern-card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" novalidate class="fade-in-up">
                    @csrf

                    <!-- Product Name -->
                    <div class="form-group-modern mb-4">
                        <label for="name" class="form-label-modern">
                            <i class="fas fa-tag me-2"></i>
                            Tên sản phẩm <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control-modern" value="{{ old('name') }}" required placeholder="Nhập tên sản phẩm...">
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Avatar Image -->
                    <div class="form-group-modern mb-4">
                        <label for="img_thumb" class="form-label-modern">
                            <i class="fas fa-image me-2"></i>
                            Ảnh đại diện <span class="text-danger">*</span>
                        </label>
                        <div class="file-upload-modern">
                            <input type="file" name="img_thumb" id="img_thumb" class="file-input-modern" accept="image/*" required onchange="previewImage()">
                            <label for="img_thumb" class="file-label-modern">
                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                <span class="file-text">Chọn ảnh đại diện</span>
                            </label>
                        </div>
                        @error('img_thumb')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <!-- Image Preview -->
                        <div id="imagePreviewContainer" class="image-preview-modern" style="display: none;">
                            <div class="preview-header">
                                <i class="fas fa-eye me-2"></i>
                                Hình ảnh đã chọn:
                            </div>
                            <img id="imagePreview" src="" alt="Selected Image" class="preview-image" />
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="form-group-modern mb-4">
                        <label for="category_id" class="form-label-modern">
                            <i class="fas fa-list me-2"></i>
                            Danh mục <span class="text-danger">*</span>
                        </label>
                        <select name="category_id" id="category_id" class="form-control-modern" required>
                            <option value="">Chọn danh mục</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group-modern mb-4">
                        <label for="description" class="form-label-modern">
                            <i class="fas fa-align-left me-2"></i>
                            Mô tả sản phẩm
                        </label>
                        <textarea name="description" id="description" class="form-control-modern" rows="5" placeholder="Mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Gallery Images -->
                    <div class="form-group-modern mb-4">
                        <label for="image_path" class="form-label-modern">
                            <i class="fas fa-images me-2"></i>
                            Ảnh chi tiết <span class="text-danger">*</span>
                        </label>
                        <div class="file-upload-modern">
                            <input type="file" id="image-input" class="file-input-modern" name="image_path[]" multiple accept="image/*" required>
                            <label for="image-input" class="file-label-modern">
                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                <span class="file-text">Chọn nhiều ảnh</span>
                            </label>
                        </div>
                        <p id="image-count" class="file-hint">
                            <i class="fas fa-info-circle me-1"></i>
                            Bạn có thể chọn nhiều ảnh để tạo gallery
                        </p>

                        <!-- Gallery Preview -->
                        <div id="image-preview-container" class="gallery-preview-modern"></div>

                        @error('image_path')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        @error('image_path.*')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="form-group-modern mb-4">
                        <div class="checkbox-modern">
                            <input type="checkbox" name="is_active" id="is_active" class="checkbox-input-modern" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="checkbox-label-modern">
                                <i class="fas fa-eye me-2"></i>
                                Hiển thị sản phẩm
                            </label>
                        </div>
                        @error('is_active')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions-modern">
                        <button type="submit" class="btn-modern btn-modern-success hover-lift">
                            <i class="fas fa-save me-2"></i>
                            Tạo sản phẩm
                        </button>
                        <a href="{{ route('products.index') }}" class="btn-modern btn-modern-secondary hover-lift">
                            <i class="fas fa-arrow-left me-2"></i>
                            Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview main image
        function previewImage() {
            const fileInput = document.getElementById('img_thumb');
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                imagePreviewContainer.style.display = 'none';
            }
        }

        // Preview multiple gallery images
        document.getElementById('image-input').addEventListener('change', function(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('image-preview-container');
        const imageCount = document.getElementById('image-count');

            previewContainer.innerHTML = '';

            if (files.length > 0) {
            imageCount.textContent = `Đã chọn ${files.length} ảnh`;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.style.margin = '5px';
                        img.className = 'rounded';
                        previewContainer.appendChild(img);
                };

                reader.readAsDataURL(file);
                }
            } else {
                imageCount.textContent = 'Bạn có thể chọn nhiều ảnh';
            }
        });
    </script>

@endsection
