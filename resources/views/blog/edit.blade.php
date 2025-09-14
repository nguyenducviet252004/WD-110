@extends('Layout.Layout')

@section('title', 'Sửa Blog')

@section('content_admin')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-center">Sửa bài viết</h1>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <form action="{{ route('blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $blog->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $blog->title) }}" required>
                        @error('title')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Tóm tắt</label>
                        <textarea name="description" id="description" class="form-control" required>{{ old('description', $blog->description) }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Mô tả</label>
                        <textarea name="content" id="content" class="form-control" required>{{ old('content', $blog->content) }}</textarea>
                        @error('content')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh</label>
                        <input type="file" name="image" id="image" class="form-control">
                        @if ($blog->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blog->image) }}" alt="Current Image" style="max-width: 200px;" class="rounded">
                            </div>
                        @endif
                        @error('image')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Trạng thái</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1" {{ $blog->is_active == 1 ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ $blog->is_active == 0 ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                        @error('is_active')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
