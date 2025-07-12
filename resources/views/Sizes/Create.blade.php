@extends('Layout.Layout')

@section('title')
    Thêm mới kích cỡ
@endsection

@section('content_admin')


    <h1 class="text-center mt-5"> Thêm mới kích cỡ</h1>
    <div class="container">
        <form method="POST" action="{{ route('sizes.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label for="name" class="col-4 col-form-label">Kích cỡ</label>

                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" />
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3 row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">
                        Thêm mới
                    </button>
                    <a href="{{ route('sizes.index') }}" class="btn btn-secondary"> Quay lại</a>
                </div>
            </div>
        </form>
    </div>
@endsection
