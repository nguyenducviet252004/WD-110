@extends('Layout.Layout')

@section('title')
    Danh sách Logo - Banner
@endsection

@section('content_admin')
    @if (session('error'))
        <div class="alert alert-modern alert-modern-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-modern alert-modern-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @php
        $bannerCount = \App\Models\LogoBanner::count();
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-center">
                <i class="fas fa-images me-3"></i>
                Danh sách Logo - Banner
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Logo Banner Table -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-photo-video me-2"></i>
                    Danh sách Logo - Banner
                </h3>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-modern badge-modern-info bounce-in">
                        {{ $bannerCount }} mục
                    </span>
                    <a href="{{ route('logo_banners.create') }}"
                        class="btn-modern {{ $bannerCount >= 5 ? 'btn-modern-secondary' : 'btn-modern-success' }} hover-lift @if ($bannerCount >= 5) disabled @endif">
                        @if ($bannerCount >= 5)
                            <i class="fas fa-ban"></i> Đã đủ 5 bản ghi
                        @else
                            <i class="fas fa-plus"></i> Thêm mới
                        @endif
                    </a>
                </div>
            </div>
            <div class="modern-card-body">
                @if($logoBanners->isEmpty())
                    <div class="text-center py-5">
                        <div class="text-muted fade-in-up">
                            <i class="fas fa-images fa-3x mb-3"></i>
                            <h5>Không có Logo/Banner nào</h5>
                            <p>Chưa có logo hoặc banner nào được tạo!</p>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Loại</th>
                                    <th>Tiêu đề</th>
                                    <th>Mô tả</th>
                                    <th>Hình ảnh</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logoBanners as $banner)
                                    <tr class="stagger-item">
                                        <td>
                                            <span class="fw-bold text-primary">#{{ $banner->id }}</span>
                                        </td>
                                        <td>
                                            @if($banner->type == 1)
                                                <span class="badge badge-modern badge-modern-warning">
                                                    <i class="fas fa-image me-1"></i>
                                                    Banner
                                                </span>
                                            @else
                                                <span class="badge badge-modern badge-modern-info">
                                                    <i class="fas fa-font me-1"></i>
                                                    Logo
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong class="text-dark">{{ $banner->title }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                {{ $banner->description }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($banner->image)
                                                <img src="{{ asset('storage/' . $banner->image) }}"
                                                     alt="{{ $banner->title }}"
                                                     class="product-image hover-scale">
                                            @else
                                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <!-- Edit -->
                                                <a href="{{ route('logo_banners.edit', $banner->id) }}"
                                                   class="action-btn action-btn-warning hover-glow">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="d-none d-md-inline">Cập nhật</span>
                                                </a>

                                                <!-- Delete -->
                                                <form action="{{ route('logo_banners.destroy', $banner->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="action-btn action-btn-danger hover-glow"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa logo/banner này không?');">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="d-none d-md-inline">Xóa</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
