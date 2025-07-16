@extends('Layout.Layout')

@section('title')
    Quản lý đánh giá
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

    <h1 class="text-center mb-3 mt-5">Quản Lý Đánh Giá</h1>

    <div class="card">
        <div class="card-body">
            @if ($reviews->isEmpty())
                <div class="alert alert-warning text-center">
                    Không có đánh giá nào!
                </div>
            @else
                <table class="table table-hover">
                    <thead class="thead-light">
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
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>{{ $review->user->email ?? 'N/A' }}</td> <!-- Hiển thị email của người dùng -->
                                <td>{{ $review->product->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($review->image_path)
                                        <img src="{{ asset('storage/' . $review->image_path) }}" alt="Review Image"
                                            class="img-thumbnail" width="80">
                                    @else
                                        Không có ảnh
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success">{{ $review->rating }}/5</span>
                                </td>
                                <td>{{ $review->comment ?? 'Không có bình luận' }}</td>
                                <td>
                                    <span class="badge {{ $review->is_reviews ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ $review->is_reviews ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('review.update', $review->id) }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        @if($review->product_id !== null) <!-- Kiểm tra xem product_id có phải là null hay không -->
                                            <button type="submit" 
                                                    class="btn {{ $review->is_reviews ? 'btn-warning' : 'btn-success' }} btn-sm"
                                                    onclick="return confirm('Chắc chắn muốn thay đổi trạng thái')">
                                                {{ $review->is_reviews ? 'Ẩn' : 'Hiển thị' }}
                                            </button>
                                        @else
                                            <!-- Nếu product_id là null, không cho phép thay đổi trạng thái -->
                                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                Sản phẩm đã bị xóa khỏi hệ thống 
                                            </button>
                                        @endif
                                    </form>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@endsection
