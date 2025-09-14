<h4 class="text-center mb-4">Top 10 Sản Phẩm Bán Chạy Nhất</h4>
@if ($topProducts && count($topProducts) > 0)
    <div class="list-group">
        @foreach ($topProducts as $product)
            <div class="list-group-item d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @php
                        $img = !empty($product['image']) ? $product['image'] : null;
                    @endphp
                    <img
                        src="{{ $img ? asset('storage/' . $img) : asset('assets/images/no-image.png') }}"
                        alt="{{ $product['product_name'] }}"
                        class="rounded-circle"
                        style="width: 60px; height: 60px; margin-right: 15px;"
                        onerror="this.onerror=null;this.src='{{ asset('assets/images/no-image.png') }}';"
                    >
                    <div>
                        <h5 class="mb-1">{{ $product['product_name'] }}</h5>
                        <div class="text-muted">
                            <span>Số đơn: {{ $product['sales_count'] }}</span> |
                            <span>Số lượng bán: {{ $product['total_quantity'] }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="badge bg-primary">{{ $product['sales_count'] }} đơn</span>
                    <span class="badge bg-success">{{ $product['total_quantity'] }} bán</span>
                    <span class="badge bg-info">{{ number_format($product['total_revenue'], 0, ',', '.') }} VNĐ</span>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-center">Không có sản phẩm bán chạy nào trong khoảng thời gian này.</p>
@endif
