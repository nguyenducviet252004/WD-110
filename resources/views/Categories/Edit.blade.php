@extends('Layout.Layout')

@section('title')
    Cập nhật danh mục
@endsection

@section('content_admin')
    


    <h1 class="text-center mt-5">Cập nhật danh mục</h1>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $category->name) }}" >
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control"
                value="{{ old('slug', $category->slug) }}" >
            @error('slug')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" id="description" class="form-control" >{{ old('description', $category->description) }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-control">
                <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Hiện</option>
                <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
            @error('status')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label mb-3">Kích hoạt</label>
            <div>
                <label>
                    <input type="radio" name="is_active" value="1"
                        {{ old('is_active', $category->is_active) == 1 ? 'checked' : '' }}>
                    Hoạt động
                </label>
                <label class="ms-3">
                    <input type="radio" name="is_active" value="0"
                        {{ old('is_active', $category->is_active) == 0 ? 'checked' : '' }}>
                    Không hoạt động
                </label>
            </div>
            @error('is_active')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
