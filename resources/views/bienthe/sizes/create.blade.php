@extends('Layout.Layout')

@section('title')
    Thêm mới kích cỡ
@endsection

@section('content_admin')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-center mt-5"> Thêm mới kích cỡ</h1>
    <div class="container">
        <form method="POST" action="{{ route('sizes.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label for="size" class="col-4 col-form-label">kích cỡ</label>

                <input type="text" class="form-control @error('size') is-invalid @enderror" name="size" id="size" value="{{ old('size') }}" />
                @error('size')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
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
