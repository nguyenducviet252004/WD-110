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
            <label for="name">Name::</label>
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
            <label for="description">Description:</label>
            <textarea  rows="30" name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
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
@endsection