@extends('Layout.Layout')

@section('title')
    Cập nhật sản phẩm
@endsection

@section('content_admin')

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1>Cập nhât sản phẩm</h1>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="avatar">Avatar:</label>
            <input type="file" name="avatar" class="form-control">
            @if ($product->avatar)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->avatar) }}" alt="Current Avatar"
                        style="width: 100px; height: auto;">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="images">Gallery:</label>
            <input type="file" id="image-input" name="images[]" multiple class="form-control" accept="image/*">
            @if ($product->galleries->isNotEmpty())
                <div class="mt-2" style="display: flex; gap: 10px" id="existing-images">
                    @foreach ($product->galleries as $gallery)
                        <div class="image-preview">
                            <img src="{{ $gallery->image_path }}" alt="Gallery Image"
                                style="width: 100px; height: 60px; margin-right: 10px;">
                            <input type="checkbox" name="delete_gallery[]" value="{{ $gallery->id }}"
                                class="delete-checkbox">
                            <label for="delete_gallery">Xóa</label>
                        </div>
                    @endforeach
                </div>
            @endif
            <div id="image-preview-container" class="mt-2"></div>
            <p id="image-count" class="mt-1">Có thể chọn nhiều ảnh</p>
        </div>

        <div class="mb-3">
            <label for="import_price">Import Price:</label>
            <input type="number" name="import_price" class="form-control"
                value="{{ old('import_price', $product->import_price) }}" required>
        </div>

        <div class="mb-3">
            <label for="price">Price:</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="mb-3">
            <label for="price">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}"
                required>
        </div>

        <div class="mb-3">
            <label for="slug">Slug:</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}" required>
        </div>
        <div class="mb-3">
            <label for="sku">SKU:</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
        </div>
        <div class="mb-3">
            <label for="price_regular">Giá gốc:</label>
            <input type="number" name="price_regular" class="form-control" step="0.01" value="{{ old('price_regular', $product->price_regular) }}" required>
        </div>
        <div class="mb-3">
            <label for="price_sale">Giá khuyến mãi:</label>
            <input type="number" name="price_sale" class="form-control" step="0.01" value="{{ old('price_sale', $product->price_sale) }}">
        </div>
        <div class="mb-3">
            <label for="content">Mô tả chi tiết:</label>
            <textarea name="content" class="form-control" rows="5">{{ old('content', $product->content) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="views">Lượt xem:</label>
            <input type="number" name="views" class="form-control" value="{{ old('views', $product->views) }}">
        </div>

        <div class="mb-3">
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" class="form-control">
                @foreach ($categories as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $product->category_id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Kích thước:</label><br>
            @foreach ($sizes as $size)
                <input type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size_{{ $size->id }}"
                    {{ $product->sizes->contains($size->id) ? 'checked' : '' }}>
                <label for="size_{{ $size->id }}">{{ $size->size }}</label><br>
            @endforeach
        </div>

        <div class="mb-3">
            <label>Màu Sắc:</label><br>
            @foreach ($colors as $color)
                <input type="checkbox" name="colors[]" value="{{ $color->id }}" id="color_{{ $color->id }}"
                    {{ $product->colors->contains($color->id) ? 'checked' : '' }}>
                <label for="color_{{ $color->id }}">{{ $color->name_color }}</label><br>
            @endforeach
        </div>


        <div class="mb-3">
            <label for="is_active">Active:</label>
            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>

    <!-- Image Preview Script -->
    <script>
        const imageInput = document.getElementById('image-input');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        const imageCount = document.getElementById('image-count');

        imageInput.addEventListener('change', function() {
            const files = Array.from(imageInput.files);
            imageCount.textContent = `Đã chọn ${files.length} ảnh`;
            imagePreviewContainer.innerHTML = ''; // Clear previous previews

            files.forEach((file) => {
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
                        files.splice(files.indexOf(file), 1); // Remove the file from the list
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
            width: 100px;
            height: auto;
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
