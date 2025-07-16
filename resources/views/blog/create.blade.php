@extends('Layout.Layout')

@section('title', 'Create Blog')

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

    <div class="container">
        <h1 class="text-center mt-5 mb-3 ">Thêm mới bài viết </h1>
        <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="category_id">Danh mục </label>
                <select name="category_id" id="category_id" class="form-control" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title">Tiêu đề </label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Tóm tắt </label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="content">Mô tả </label>
                <textarea name="content" id="content" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh </label>
                <input type="file" name="image" id="image" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="is_active">Trạng thái </label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1">Hoạt động </option>
                    <option value="0">không hoạt động </option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm mới </button>
        </form>
    </div>
@endsection
