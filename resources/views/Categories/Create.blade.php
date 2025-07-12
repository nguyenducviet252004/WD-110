@extends('Layout.Layout')

@section('title')
    Thêm mới danh mục
@endsection

@section('content_admin')


    <h1 class="text-center mt-5">Thêm danh mục</h1>

    <form action="{{ route('categories.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" >
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" >
            @error('slug')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" id="description" class="form-control" >{{ old('description') }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-control">
                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Hiện</option>
                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
            @error('status')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <input type="hidden" name="is_active" value="1">

        <div class="text-center">
            <button type="submit" class="btn btn-success">Thêm mới</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
