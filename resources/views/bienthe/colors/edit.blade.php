@extends('Layout.Layout')

@section('title')
    Cập nhật màu sắc
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

    <h1 class="text-center mt-5">Cập nhật màu sắc</h1>

    <div class="container">
        <form method="POST" action="{{ route('colors.update', $color->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
                <label for="name_color" class="col-4 col-form-label">Tên Màu</label>

                <input type="text" class="form-control" name="name_color" id="name_color"
                    value="{{ old('name_color', $color->name_color) }}" required />

            </div>

            <div class="mb-3 row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('colors.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </form>
    </div>
@endsection
