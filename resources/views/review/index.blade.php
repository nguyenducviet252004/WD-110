@extends('Layout.Layout')

@section('title')
    Quản lý đánh giá
@endsection

@section('content_admin')
    @if (session('success'))
        <div class="alert alert-modern alert-modern-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-modern alert-modern-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-center">
                <i class="fas fa-star me-3"></i>
                Quản lý đánh giá
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Reviews Table -->
        <div class="modern-card hover-lift">
            <div class="modern-card-header">
                <h3>
                    <i class="fas fa-comments me-2"></i>
                    Danh sách đánh giá
                </h3>
                <span class="badge badge-modern badge-modern-info bounce-in">
                    {{ $reviews->count() }} đánh giá
                </span>
            </div>
            <div class="modern-card-body">
                @if ($reviews->isEmpty())
                    <div class="text-center py-5">
                        <div class="text-muted fade-in-up">
                            <i class="fas fa-star fa-3x mb-3"></i>
                            <h5>Không có đánh giá nào</h5>
                            <p>Chưa có đánh giá nào từ khách hàng!</p>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Người dùng</th>
                                    <th>Sản phẩm</th>
                                    <th>Hình ảnh</th>
                                    <th>Đánh giá</th>
                                    <th>Bình luận</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr class="stagger-item">
                                        <td>
                                            <span class="fw-bold text-primary">#{{ $review->id }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong class="text-dark">{{ $review->user->email ?? 'N/A' }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong class="text-dark">{{ $review->product->name ?? 'N/A' }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($review->image_path)
                                                <img src="{{ asset('storage/' . $review->image_path) }}"
                                                     alt="Review Image"
                                                     class="product-image hover-scale">
                                            @else
                                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-modern badge-modern-success">
                                                <i class="fas fa-star me-1"></i>
                                                {{ $review->rating }}/5
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                {{ $review->comment ?? 'Không có bình luận' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($review->is_reviews)
                                                <span class="badge badge-modern badge-modern-success">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Hiển thị
                                                </span>
                                            @else
                                                <span class="badge badge-modern badge-modern-danger">
                                                    <i class="fas fa-eye-slash me-1"></i>
                                                    Ẩn
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $review->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <form method="POST" action="{{ route('review.update', $review->id) }}" novalidate style="display: inline;">
                                                    @csrf
                                                    @method('PUT')

                                                    @if($review->product_id !== null)
                                                        <button type="submit"
                                                                class="action-btn {{ $review->is_reviews ? 'action-btn-warning' : 'action-btn-success' }} hover-glow"
                                                                onclick="return confirm('Chắc chắn muốn thay đổi trạng thái')">
                                                            @if ($review->is_reviews)
                                                                <i class="fas fa-eye-slash"></i>
                                                                <span class="d-none d-md-inline">Ẩn</span>
                                                            @else
                                                                <i class="fas fa-eye"></i>
                                                                <span class="d-none d-md-inline">Hiển thị</span>
                                                            @endif
                                                        </button>
                                                    @else
                                                        <button type="button" class="action-btn action-btn-secondary" disabled>
                                                            <i class="fas fa-ban"></i>
                                                            <span class="d-none d-md-inline">Sản phẩm đã xóa</span>
                                                        </button>
                                                    @endif
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
