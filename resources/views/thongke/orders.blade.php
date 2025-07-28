{{-- Bộ lọc --}}
<div class="card text-white bg-info h-100 mt-4">
    <div class="card-body">
        @if (isset($data['filtered_revenue']) && $data['filtered_revenue'] > 0)
            <h5 class="card-title">Doanh Thu Theo Bộ Lọc</h5>
            <p class="card-text">{{ number_format($data['filtered_revenue'], 0, ',', '.') }} VNĐ</p>
        @else
            <p class="card-text">Không có dữ liệu doanh thu trong khoảng thời gian lọc.</p>
        @endif
        @if (isset($data['filtered_order_count']) && $data['filtered_order_count'] > 0)
            <p class="card-text">Số lượng đơn: {{ $data['filtered_order_count'] }} đơn</p>
        @else
            <p class="card-text">Không có dữ liệu đơn trong khoảng thời gian lọc.</p>
        @endif
    </div>
</div>

{{-- Doanh thu và đơn hàng - share thành 2 phần --}}
<div class="row mt-4 mb-4">
    {{-- Doanh thu --}}
    <div class="col-md-6">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <h5 class="card-title">Doanh Thu Tháng Này</h5>
                @if (isset($data['current_revenue']) && $data['current_revenue'] > 0)
                    <p class="card-text">{{ number_format($data['current_revenue'], 0, ',', '.') }} VNĐ</p>
                @else
                    <p class="card-text">Không có dữ liệu doanh thu tháng này.</p>
                @endif

                <h5 class="card-title">Doanh Thu Tháng Trước</h5>
                @if (isset($data['last_revenue']) && $data['last_revenue'] > 0)
                    <p class="card-text">{{ number_format($data['last_revenue'], 0, ',', '.') }} VNĐ</p>
                @else
                    <p class="card-text">Không có dữ liệu doanh thu tháng trước.</p>
                @endif

                <h5 class="card-title">Sự Thay Đổi Doanh Thu</h5>
                <p class="card-text">
                    <span class="font-weight-bold">{{ number_format($data['change_revenue'], 0, ',', '.') }} VNĐ</span>
                    @if ($data['change_revenue'] > 0)
                        <i class="fas fa-arrow-up text-success"></i>
                    @elseif($data['change_revenue'] < 0)
                        <i class="fas fa-arrow-down text-danger"></i>
                    @else
                        <i class="fas fa-arrow-right text-muted"></i>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Đơn hàng --}}
    <div class="col-md-6">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <h5 class="card-title">Số Lượng Đơn Hoàn Thành Tháng Này</h5>
                @if (isset($data['current_order_count']) && $data['current_order_count'] > 0)
                    <p class="card-text">{{ $data['current_order_count'] }} đơn</p>
                @else
                    <p class="card-text">Không có dữ liệu đơn hoàn thành tháng này.</p>
                @endif

                <h5 class="card-title">Số Lượng Đơn Hoàn Thành Tháng Trước</h5>
                @if (isset($data['last_order_count']) && $data['last_order_count'] > 0)
                    <p class="card-text">{{ $data['last_order_count'] }} đơn</p>
                @else
                    <p class="card-text">Không có dữ liệu đơn hoàn thành tháng trước</p>
                @endif

                <h5 class="card-title">Sự thay đổi về số lượng đơn</h5>
                <p class="card-text">
                    <span class="font-weight-bold">{{ $data['order_count_change'] }} đơn</span>
                    @if ($data['order_count_change'] > 0)
                        <i class="fas fa-arrow-up text-success"></i>
                    @elseif($data['order_count_change'] < 0)
                        <i class="fas fa-arrow-down text-danger"></i>
                    @else
                        <i class="fas fa-arrow-right text-muted"></i>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>