@extends('Layout.Layout')

@section('title')
    Cập nhật Logo - Banner
@endsection
@section('content_admin')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-center mt-5">Cập nhật Logo - Banner</h1>

    <form action="{{ route('logo_banners.update', $logoBanner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="type">Loại</label>
            <select name="type" id="type" class="form-control" required>
                <option value="1" {{ $logoBanner->type == 1 ? 'selected' : '' }}>Banner</option>
                <option value="2" {{ $logoBanner->type == 2 ? 'selected' : '' }}>Logo</option>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control"
                value="{{ old('title', $logoBanner->title) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $logoBanner->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" name="image" id="image" class="form-control">
            @if ($logoBanner->image)
                <img src="{{ asset('storage/' . $logoBanner->image) }}" alt="Current Image" class="mt-2" width="200">
            @endif
        </div>

        <div class="form-group" hidden>
            <label for="is_active">Trạng thái</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" selected></option>
                <option value="0" ></option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
@endsection
