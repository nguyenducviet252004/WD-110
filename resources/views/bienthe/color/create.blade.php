@extends('Layout.Layout')

@section('title')
    Thêm mới màu sác
@endsection

@section('content_admin')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <h1 class="text-center mt-5">Thêm mới màu sác </h1>
    <div class="container">
        <form method="POST" action="{{ route('colors.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label for="name_color" class="col-4 col-form-label">Tên màu sắc</label>
                <input type="text" class="form-control" name="hex_color" id="name_color" value="1" hidden />

                <input type="text" class="form-control" name="name_color" id="name_color"
                    value="{{ old('name_color') }}" />

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
