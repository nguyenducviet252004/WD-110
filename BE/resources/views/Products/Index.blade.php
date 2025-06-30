@extends('Layout.Layout')

@section('title')
    Danh sách sản phẩm
@endsection

@section('content_admin')



    <h1 class="text-center mt-5 mb-3">Danh sách sản phẩm</h1>

    <a href="{{ route('products.create') }}" class="btn btn-outline-success mb-4"
        style="font-size: 1.1em; padding: 10px 20px;">Thêm mới</a>

    <div class="d-flex gap-3 mb-4">
        <!-- Price Order Filter -->
        <form style="width: 200px;" method="GET" action="{{ route('products.index') }}">
            <select style="padding: 10px;" name="price_order" class="form-control" onchange="this.form.submit()">
                <option value="asc" {{ request('price_order') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                <option value="desc" {{ request('price_order') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
            </select>
        </form>

        <!-- Status Filter -->
        <form style="width: 200px;" method="GET" action="{{ route('products.index') }}">
            <select style="padding: 10px;" name="is_active" class="form-control" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </form>

        <!-- Price Range Filter -->
        <form style="width: 200px;" method="GET" action="{{ route('products.index') }}">
            <select style="padding: 10px;" name="price_range" class="form-control" onchange="this.form.submit()">
                <option value="">Chọn mức giá</option>
                <option value="under_200k" {{ request('price_range') == 'under_200k' ? 'selected' : '' }}>Dưới 200k
                </option>
                <option value="200k_500k" {{ request('price_range') == '200k_500k' ? 'selected' : '' }}>200k - 500k
                </option>
                <option value="over_500k" {{ request('price_range') == 'over_500k' ? 'selected' : '' }}>Trên 500k</option>
            </select>
        </form>
    </div>

    @if ($products->isEmpty())
        <p class="text-center text-muted">Không có sản phẩm nào phù hợp với bộ lọc của bạn.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-light text-center">
                    <tr>
                        <th>STT</th>
                        <th>Tên</th>
                        <th>Slug</th>
                        <th>SKU</th>
<th>Danh mục</th>
                        <th>Đại diện</th>
                        <th>Giá gốc</th>
                        <th>Giá KM</th>
                        <th>Tổng số lượng</th>
                        <th>Lượt xem</th>
                        <th>Mô tả</th>
                        <th>Mô tả chi tiết</th>
                        <th>Thư viện</th>
                        <!-- <th>Kích cỡ</th>
                        <th>Màu sắc</th> -->
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->slug }}</td>
                            <td>{{ $item->sku }}</td>
                            <td>
                                @if($item->category)
                                    {{ $item->category->name }}
                                @else
                                    Không có danh mục
                                @endif
                            </td>
                            <td>
                                @if($item->thumb_image)
                                    <img src="{{ $item->thumb_image }}" alt="{{ $item->name }}"
                                        style="width: 40px; height: 30px; border-radius: 8px; object-fit: cover;">
                                @else
                                    <span class="text-muted">không có ảnh</span>
                                @endif
                            </td>
                            <td>{{ number_format($item->price_regular) }} VND</td>
                            <td>{{ number_format($item->price_sale) }} VND</td>
                            <td>
                                {{ $item->variants->sum('quantity') }}
                            </td>
                            <!-- <td>{{ $item->sell_quantity ?? '-' }}</td> -->
                            <td>{{ $item->views ?? 0 }}</td>
                            <td class="description" data-description="{{ $item->description }}">
                                {{ Str::limit($item->description, 30) }}</td>
                            <td>{{ Str::limit($item->content, 30) }}</td>
                            <td>
                                @php $hasGallery = false; @endphp
                                @foreach ($item->variants as $variant)
                                    @if (!empty($variant->image))
                                        @php $hasGallery = true; @endphp
                                        <img src="{{ asset('storage/' . $variant->image) }}" alt="Variant Image" class="gallery-image"
                                            style="width: 40px; height: 30px; border-radius: 5px;">
@endif
                                @endforeach
                                @if (!$hasGallery)
                                    không có ảnh
                                @endif
                            </td>
                            <!-- <td>
                                @php $sizes = $item->variants->pluck('size.size')->unique()->filter(); @endphp
                                @if ($sizes->isNotEmpty())
                                    @foreach ($sizes as $size)
                                        <!-- <span class="badge bg-info text-dark" style="margin-right: 3px;">{{ $size }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">không có kích thước</span>
                                @endif
                            </td>
                            <td>
                                @php $colors = $item->variants->pluck('color.name_color')->unique()->filter(); @endphp
                                @if ($colors->isNotEmpty())
                                    @foreach ($colors as $color)
                                        <span class="badge bg-success text-light" style="margin-right: 3px;">{{ $color }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">không có màu</span>
                                @endif
                            </td> --> -->
                            <td class="d-flex gap-2 justify-content-center">
                                @if ($item->is_active == 1)
                                    <a href="{{ route('products.edit', $item->id) }}"
                                        class="btn btn-outline-warning btn-sm">Cập nhật</a>
                                @else
                                    <a href=""
                                        onclick="return confirm('vui lòng cập nhật trạng thái sang hiển thị để thao tác')"
                                        class="btn btn-outline-warning btn-sm">Cập nhật</a>
                                @endif

                                <!-- Toggle is_active (Hide/Show) -->
                                <form action="{{ route('products.index') }}" method="GET" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="toggle_is_active" value="1">
                                    <input type="hidden" name="product_id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái ẩn/hiển thị sản phẩm này?')">
{{ $item->is_active == 1 ? 'Ẩn' : 'Hiện' }}
                                    </button>
                                </form>

                                <form action="{{ route('products.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


    <div class="d-flex justify-content-center mt-4">
        {{ $products->appends(['status' => request()->get('status')])->links() }}

    </div>


    <style>
        .gallery-image {
            width: 40px;
            height: 40px;
            margin: 2px;
            border-radius: 5px;
        }

        .color-circle {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
        }

        /* Đặt chiều dài tối đa cho cột mô tả */
        .description {
            max-width: 400px;
            /* Thay đổi chiều rộng tối đa theo yêu cầu */
            white-space: nowrap;
            /* Không cho phép xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản thừa */
            text-overflow: ellipsis;
            /* Hiển thị ba chấm khi quá dài */
            cursor: pointer;
            /* Thêm con trỏ để người dùng biết là có thể hover */
        }

        /* Thanh cuộn ngang khi bảng có nhiều cột */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive table {
            width: 100%;
            min-width: 1000px;
            /* Đảm bảo rằng bảng sẽ rộng hơn khi có nhiều cột */
        }
    </style>
@endsection