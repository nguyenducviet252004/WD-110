@extends('Layout.Layout')

@section('title')
    Cập nhật danh mục
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

    <h1 class="text-center mt-5">Cập nhật danh mục</h1>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="mb-3 mt-3">
            <label class="form-label mb-3">Trạng thái</label>
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
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
