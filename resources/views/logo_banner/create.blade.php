@extends('Layout.Layout')

@section('title')
    Thêm mới
@endsection

@section('content_admin')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-center mt-5">Thêm mới Logo - Banner</h1>

    <form action="{{ isset($logoBanner) ? route('logo_banners.update', $logoBanner->id) : route('logo_banners.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($logoBanner))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="type">Loại</label>
            <select name="type" id="type" class="form-control" required>
                <option value="1" {{ isset($logoBanner) && $logoBanner->type == 1 ? 'selected' : '' }}>Banner</option>
                <option value="2" {{ isset($logoBanner) && $logoBanner->type == 2 ? 'selected' : '' }}>Logo</option>
            </select>
        </div>

        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control"
                value="{{ $logoBanner->title ?? old('title') }}" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea name="description" id="description" class="form-control">{{ $logoBanner->description ?? old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" name="image" id="image" class="form-control"
                value="{{ $logoBanner->image ?? old('image') }}" required>
        </div>

        <div class="form-group" hidden>
            <label for="is_active">Trạng thái</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ isset($logoBanner) && $logoBanner->is_active ? 'selected' : '' }}>Hoạt động
                </option>
                <option value="0" {{ isset($logoBanner) && !$logoBanner->is_active ? 'selected' : '' }}>Không hoạt động
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ isset($logoBanner) ? 'Cập nhật' : 'Thêm mới' }}</button>
    </form>
@endsection
