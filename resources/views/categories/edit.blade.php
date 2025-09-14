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

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-center">Cập nhật danh mục</h1>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label mb-3">Trạng thái</label>
                        <div>
                            <label class="me-3">
                                <input type="radio" name="is_active" value="1" {{ old('is_active', $category->is_active) == 1 ? 'checked' : '' }}> Hoạt động
                            </label>
                            <label>
                                <input type="radio" name="is_active" value="0" {{ old('is_active', $category->is_active) == 0 ? 'checked' : '' }}> Không hoạt động
                            </label>
                        </div>
                        @error('is_active')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
