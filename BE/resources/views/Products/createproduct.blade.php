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

    <h1 class="text-center mt-5">Thêm mới sản phẩm </h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name">Tên</label>
            <input type="text" name="name" class="form-control"  value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="avatar">Ảnh đại diện</label>
            <input type="file" name="avatar" class="form-control" accept="image/*" id="avatar" required
                onchange="previewImage()">
        </div>
        <div id="imagePreviewContainer" style="display: none;">
            <label>Hình ảnh đã chọn:</label>
            <img id="imagePreview" src="" alt="Selected Avatar"
                style="width: 150px; height: 100px; margin-top: 10px;" />
        </div>

        <div class="mb-3 mt-3">
            <label for="images">Ảnh chi tiết</label>
            <input type="file" id="image-input" class="form-control" name="image_path[]" multiple accept="image/*"
                placeholder="Có thể chọn nhiều ảnh">
            <div id="image-preview-container" class="mt-2"></div>
            <p id="image-count" class="mt-1">Có thể chọn nhiều ảnh</p>
        </div>

        <div class="mb-3">
            <label for="import_price">Giá nhập</label>
            <input type="number" name="import_price" step="0.01"  value="{{ old('import_price') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="price">Giá bán</label>
            <input type="number" name="price" step="0.01"   value="{{ old('import_price') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="price">Số lượng </label>
            <input type="number" name="quantity"  value="{{ old('quantity') }}"  class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description">Mô tả</label>
            <textarea name="description" class="form-control" value="{{ old('description') }}"  rows="30"></textarea>
        </div>

        <div class="mb-3">
            <label for="slug">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
        </div>
        <div class="mb-3">
            <label for="sku">SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
        </div>
        <div class="mb-3">
            <label for="price_regular">Giá gốc</label>
            <input type="number" name="price_regular" step="0.01" value="{{ old('price_regular') }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="price_sale">Giá khuyến mãi</label>
            <input type="number" name="price_sale" step="0.01" value="{{ old('price_sale') }}" class="form-control">
        </div>
        <div class="mb-3">
            <label for="content">Mô tả chi tiết</label>
            <textarea name="content" class="form-control" rows="5">{{ old('content') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="views">Lượt xem</label>
            <input type="number" name="views" value="{{ old('views', 0) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="category_id">Danh mục</label>
            <select name="category_id" id="category_id" class="form-control" required>
                @foreach ($categories as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Kích thước:</label><br>
            @foreach ($sizes as $size)
                <input type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size_{{ $size->id }}">
                <label for="size_{{ $size->id }}">{{ $size->size }}</label><br>
            @endforeach
        </div>

        <div class="mb-3">
            <label>Màu Sắc:</label><br>
            @foreach ($colors as $color)
                <input type="checkbox" name="colors[]" value="{{ $color->id }}" id="color_{{ $color->id }}">
                <label for="color_{{ $color->id }}">{{ $color->name_color }}</label><br>
            @endforeach
        </div>

        <!-- Thêm phần kiểm tra trạng thái hoạt động -->
        <div class="mb-3">
            <label for="is_active">Hoạt động:</label>
            <input type="checkbox" name="is_active" value="1" checked>
        </div>

        <div class="text-center mb-5 mt-3">
            <button type="submit" class="btn btn-primary">Thêm mối</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>

    <!-- avatar Preview Script -->
    <script>
        function previewImage() {
            const fileInput = document.getElementById('avatar');
            const file = fileInput.files[0];

            if (file) {
                const reader = new FileReader();

                // Chạy khi file đã được đọc xong
                reader.onload = function(event) {
                    const imagePreview = document.getElementById('imagePreview');
                    imagePreview.src = event.target.result; // Đặt ảnh đã chọn vào thẻ <img>

                    // Hiển thị vùng chứa hình ảnh
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                }

                // Đọc file ảnh dưới dạng Data URL
                reader.readAsDataURL(file);
            }
        }
    </script>

    <!-- Image Preview Script -->
    <script>
        const imageInput = document.getElementById('image-input');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        const imageCount = document.getElementById('image-count');

        imageInput.addEventListener('change', function() {
            const files = Array.from(imageInput.files);
            imageCount.textContent = `Đã chọn ${files.length} ảnh`;
            imagePreviewContainer.innerHTML = ''; // Clear previous previews

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.createElement('div');
                    imagePreview.classList.add('image-preview');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('preview-img');

                    // Create delete button overlay
                    const deleteButton = document.createElement('button');
                    deleteButton.classList.add('delete-btn');
                    deleteButton.innerHTML = 'X';
                    deleteButton.addEventListener('click', () => {
                        imagePreview.remove();
                        files.splice(index, 1); // Remove the file from the list
                        imageCount.textContent = `Đã chọn ${files.length} ảnh`;
                    });

                    imagePreview.appendChild(img);
                    imagePreview.appendChild(deleteButton);
                    imagePreviewContainer.appendChild(imagePreview);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>

    <!-- Style for Preview Images -->
    <style>
        .image-preview {
            position: relative;
            display: inline-block;
            margin-right: 10px;
        }

        .preview-img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
        }
    </style>
@endsection
