@extends('Layout.Layout')

@section('title')
    Thêm mới màu sác
@endsection

@section('content_admin')

    <h1 class="text-center mt-5">Thêm mới màu sác </h1>
    <div class="container">
        <form method="POST" action="{{ route('colors.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label for="name" class="col-4 col-form-label">Tên màu sắc</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"  />
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label for="code" class="col-4 col-form-label">Mã màu</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}"  />
                    @error('code')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">
                        Thêm mới
                    </button>
                    <a href="{{ route('colors.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </form>
    </div>
@endsection
