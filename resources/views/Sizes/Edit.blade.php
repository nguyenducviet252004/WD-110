@extends('Layout.Layout')

@section('title')
    Cập nhật kích cỡ
@endsection



    <h1 class="text-center mt-5 mb-3">Cập nhật kích cỡ</h1>
    <div class="container">
        <form method="POST" action="{{ route('sizes.update', $size->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
                <label for="name" class="col-4 col-form-label">Tên kích cỡ</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $size->name) }}" required />
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                    <a href="{{ route('sizes.index') }}" class="btn btn-secondary"> Quay lại</a>
                </div>
            </div>
        </form>
    </div>
@endsection
