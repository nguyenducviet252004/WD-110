@extends('Layout.Layout')

@section('title')
    Cập nhật màu sắc
@endsection

@section('content_admin')
  

    <h1 class="text-center mt-5">Cập nhật màu sắc</h1>

    <div class="container">
        <form method="POST" action="{{ route('colors.update', $color->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
                <label for="name" class="col-4 col-form-label">Tên Màu</label>

                <input type="text" class="form-control" name="name" id="name"
                    value="{{ old('name', $color->name) }}"  />
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

            </div>
            <div class="mb-3 row">
                <label for="code" class="col-4 col-form-label">Mã Màu</label>

                <input type="text" class="form-control" name="code" id="code"
                    value="{{ old('code', $color->code) }}"  />
                    @error('code')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

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
