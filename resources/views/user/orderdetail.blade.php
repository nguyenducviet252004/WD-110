@extends('user.master')

@section('title')
    Chi tiết đơn hàng
@endsection

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Chi tiết đơn hàng #{{ $order->id }}</h2>
        <div class="card mb-4">
            <div class="card-header">
                <strong>Trạng thái:</strong>
                <span class="badge
                    @if ($order->status == 3) bg-success
                    @elseif($order->status == 4) bg-danger
                    @elseif($order->status == 2) bg-primary
                    @else bg-info @endif">
                    {{ $order->status == 2 ? 'Đang vận chuyển' : ($order->status == 3 ? 'Giao hàng thành công' : ($order->status == 4 ? 'Đã hủy' : 'Đang xử lý')) }}
                </span>
            </div>
            <div class="card-body">
                <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at }}</p>
                <p><strong>Thành tiền:</strong> ₫{{ number_format($order->total_amount ?? 0) }}</p>
                <p><strong>Đã giảm giá:</strong> {{ number_format($order->discount_value ?? 0) }} VNĐ</p>
                <p><strong>Địa chỉ nhận hàng:</strong> {{ $order->shipAddress->ship_address ?? 'Không có' }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->shipAddress->phone_number ?? 'Không có' }}</p>
                <p><strong>Người nhận:</strong> {{ $order->shipAddress->recipient_name ?? 'Không có' }}</p>
                <p><strong>Người gửi:</strong> {{ $order->shipAddress->sender_name ?? 'Không có' }}</p>
            </div>
        </div>
        <h4>Sản phẩm trong đơn hàng</h4>
        <div class="card">
            <div class="card-body">
                @foreach ($order->orderDetails as $detail)
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <img src="{{ Storage::url($detail->product->img_thumb ?? '') }}" alt="{{ $detail->product->name ?? '' }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-6">
                            <h6>{{ $detail->product->name ?? 'Sản phẩm đã bị xóa' }}</h6>
                            <p>Danh mục: {{ $detail->product->categories->name ?? 'Không rõ' }}</p>
                            <p>Màu sắc: {{ $detail->color->name_color ?? 'Không rõ' }}</p>
                            <p>Kích cỡ: {{ $detail->size->size ?? 'Không rõ' }}</p>
                        </div>
                        <div class="col-md-2">
                            <p>Số lượng: x{{ $detail->quantity }}</p>
                        </div>
                        <div class="col-md-2">
                            <p>Đơn giá: ₫{{ number_format($detail->price, 0, ',', '.') }}</p>
                            <p>Tổng: ₫{{ number_format($detail->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
        <a href="{{ route('userorder.index') }}" class="btn btn-secondary mt-4">Quay lại danh sách đơn hàng</a>
    </div>
@endsection
