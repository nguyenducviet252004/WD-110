<h3 class="mb-5 text-center">Bộ lọc không được áp dụng tại đây</h3>

@if ($data['total_stock']->isEmpty() && $data['nearly_sold_out']->isEmpty())
    <p class="text-muted text-center">Không có sản phẩm nào thỏa mãn điều kiện.</p>
@else
    <div class="row mt-3 mb-5">
        <!-- Cột bên trái: Tồn kho trên 3 tháng -->
        <div class="col-md-6">
            <p class="text-center">Danh sách sản phẩm tồn kho trên 3 tháng kể từ thời điểm thêm vào hệ thống</p>
            @if ($data['total_stock']->isEmpty())
                <p class="text-muted text-center">Không có sản phẩm tồn kho thỏa mãn điều kiện.</p>
            @else
                <table class="table table-bordered mt-4 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Tổng số lượng</th>
                            <th>Đã bán</th>
                            <th>Tồn kho</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['total_stock'] as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $product->img_thumb) }}"
                                         alt="{{ $product->name }}"
                                         width="50"
                                         height="50">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->variants->sum('quantity') }}</td>
                                <td>
                                    @php
                                        $sold = $product->variants->sum(function($variant) {
                                            return $variant->sold_quantity ?? 0;
                                        });
                                    @endphp
                                    {{ $sold }}
                                </td>
                                <td>{{ $product->variants->sum('quantity') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Cột bên phải: Sản phẩm sắp bán hết -->
        <div class="col-md-6">
            <p class="text-center">Danh sách sản phẩm sắp bán hết (dưới 3 tháng từ thời điểm thêm vào hệ thống)</p>
            @if ($data['nearly_sold_out']->isEmpty())
                <p class="text-muted text-center">Không có sản phẩm nào sắp bán hết thỏa mãn điều kiện.</p>
            @else
                <table class="table table-bordered mt-4 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Tổng số lượng</th>
                            <th>Đã bán</th>
                            <th>Tồn kho</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['nearly_sold_out'] as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $product->img_thumb) }}"
                                         alt="{{ $product->name }}"
                                         width="50"
                                         height="50">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->variants->sum('quantity') }}</td>
                                <td>
                                    @php
                                        $sold = $product->variants->sum(function($variant) {
                                            return $variant->sold_quantity ?? 0;
                                        });
                                    @endphp
                                    {{ $sold }}
                                </td>
                                <td>{{ $product->variants->sum('quantity') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endif
