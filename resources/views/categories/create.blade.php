@extends('Layout.Layout')

@section('title')
    Thêm mới danh mục
@endsection

@section('content_admin')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-center mt-5">Thêm danh mục</h1>

    <form action="{{ route('categories.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <input type="text" name="is_active" id="name" class="form-control" value="1" hidden>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Thêm mới</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
