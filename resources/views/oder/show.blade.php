@extends('Layout.Layout')

@section('title')
    Chi tiết đơn hàng
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
    <h1 class="text-center mt-5">Chi tiết đơn hàng</h1>

    <div class="container">
        <h2 class="my-4">Chi tiết đơn hàng #{{ $order->id }}</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Màu sắc</th>
                        <th>Kích cỡ</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $detail)
                        <tr>
                            @if ($detail->is_deleted)
                                <td colspan="8" class="text-center">Sản phẩm đã bị xóa bởi hệ thống</td>
                                <!-- Hiển thị thông báo nếu sản phẩm đã bị xóa -->
                            @else
                                <td>{{ $detail->product->id }}</td>
                                <td>{{ $detail->product->name }}</td>
                                <td class="text-center">
                                    <img src="{{ asset('storage/' . $detail->product->avatar) }}" alt="image"
                                        style="width: 50px; height: 50px;">
                                </td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ number_format($detail->price) }} VNĐ</td>
                                <td>{{ $detail->color->name_color }}</td>
                                <td>{{ $detail->size->size }}</td>
                                <td>{{ number_format($detail->total) }} VNĐ</td>
                            @endif
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
<div class="my-4">
            <h4>Tổng tiền: <span class="text-success">{{ number_format($order->total_amount) }} VNĐ</span></h4>

            @if ($order->payment_method == 2)
                <!-- Thông tin thanh toán online -->
                @if ($order->payment)
                    <!-- Kiểm tra nếu có dữ liệu payment -->
                    <div class="mt-3">
                        <h5>Thông tin thanh toán online</h5>
                        <ul>
                            <li><strong>Mã giao dịch:</strong>
                                {{ $order->payment->transaction_id ?? 'Không có thông tin' }}</li>
                            <li><strong>Ngày thanh toán:</strong>
                                {{ $order->payment->created_at ? $order->payment->created_at->format('d-m-Y H:i') : 'Không có thông tin' }}
                            </li>
                            <li><strong>Số tiền thanh toán:</strong>
                                ₫{{ isset($order->payment->amount) ? number_format($order->payment->amount, 0, ',', '.') : 'Không có thông tin' }}
                            </li>
                            <li><strong>Trạng thái:</strong>
                                {{ $order->payment->status ?? 'Không có thông tin' }}
                            </li>
                        </ul>
                    </div>
                    @else
                    <!-- Không có dữ liệu thanh toán online -->
                    <div class="mt-3">
                        <h5>Không có thông tin thanh toán online</h5>
                    </div>
                @endif
            @elseif ($order->payment_method == 1)
                <!-- Thông tin thanh toán COD -->
                <div class="mt-3">
                    <h4>Phương thức thanh toán: <span class="text-success">COD (Thanh toán khi nhận hàng)</span></h4>
                </div>
            @else
                <!-- Phương thức thanh toán không xác định -->
                <div class="mt-3">
                    <h5>Phương thức thanh toán không xác định</h5>
                </div>
            @endif


            <h4>Đã giảm giá:
                <span class="text-warning">
                    {{ number_format($order->discount_value ?? 0) }} VNĐ
                </span>
            </h4>
            <h4>Người dùng: {{ $order->user->email }}</h4>
            <h4>Địa chỉ giao hàng:</h4>
            <p>
                <strong>Tên người nhận:</strong>
                @if ($order->shipAddress && $order->shipAddress->recipient_name)
                    {{ $order->shipAddress->recipient_name }}
                @else
                    Không rõ
                @endif
                <br>

                <strong>Số điện thoại:</strong>
                @if ($order->shipAddress && $order->shipAddress->phone_number)
                    {{ $order->shipAddress->phone_number }}
                @else
                    Không rõ
                @endif
                <br>

                <strong>Địa chỉ:</strong>
                @if ($order->shipAddress && $order->shipAddress->ship_address)
                    {{ $order->shipAddress->ship_address }}
                @else
                    Không rõ
                @endif
            </p>

            <h4>Trạng thái:
                @switch($order->status)
                    @case(0)
                        <span class="badge bg-warning text-dark">Chờ xử lý</span>
                    @break

                    @case(1)
                        <span class="badge bg-info text-dark">Đã xử lý</span>
                    @break

                    @case(2)
                        <span class="badge bg-primary text-white">Đang vận chuyển</span>
                    @break

                    @case(3)
                        <span class="badge bg-success">Giao hàng thành công</span>
                    @break

                    @case(4)
                        <span class="badge bg-danger">Đã hủy</span>
                    @break

                    @case(5)
                        <span class="badge bg-secondary">Đã trả lại</span>
                    @break
                @endswitch
            </h4>
        </div>

        <div class="text-center">
            <a href="{{ route('orders.index') }}" class="btn btn-primary">Quay lại danh sách đơn hàng</a>
        </div>
    </div>
@endsection
