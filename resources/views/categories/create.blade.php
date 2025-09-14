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

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-center">Thêm danh mục</h1>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card card-outline card-success">
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="text" name="is_active" id="is_active" class="form-control" value="1" hidden>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Thêm mới</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
