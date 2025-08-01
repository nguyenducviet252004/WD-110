@extends('Layout.Layout')

@section('title')
    Danh sách sản phẩm
@endsection

@section('content_admin')

    @if (session('success'))
        <div class="alert alert-success text-center mt-5">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger text-center mt-5">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-center mt-5 mb-3">Danh sách sản phẩm</h1>

    <a href="{{ route('products.create') }}" class="btn btn-outline-success mb-4"
        style="font-size: 1.1em; padding: 10px 20px;">Thêm mới</a>

    <div class="d-flex gap-3 mb-4">
        <!-- Sắp xếp theo giá -->
        <form style="width: 200px;" method="GET" action="{{ route('products.index') }}">
            <select style="padding: 10px;" name="price_order" class="form-control" onchange="this.form.submit()">
                <option value="asc" {{ request('price_order') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                <option value="desc" {{ request('price_order') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
            </select>
        </form>

        <!-- Trạng thái -->
        <form style="width: 200px;" method="GET" action="{{ route('products.index') }}">
            <select style="padding: 10px;" name="is_active" class="form-control" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </form>

        <!-- Mức giá -->
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
                        <th>Danh mục</th>
                        <th>Đại diện</th>
                        <th>Số lượng</th>
                        <th>Bán</th>
                        <th>Giá</th>
                        <th>Mô tả</th>
                        <th>Thư viện</th>
                        <th>Kích cỡ</th>
                        <th>Màu sắc</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->categories->name }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $item->avatar) }}" alt="{{ $item->name }}"
                                    style="width: 40px; height: 30px; border-radius: 8px;">
                            </td>
                            <td>{{ number_format($item->quantity) }}</td>
                            <td>{{ number_format($item->sell_quantity) }}</td>
                            <td>{{ number_format($item->price) }} VND</td>
                            <td class="description" data-description="{{ $item->description }}">
                                {{ Str::limit($item->description, 30) }}</td>

                            <!-- Modal cho mô tả -->
                            <div id="descriptionModal" class="description-modal">
                                <div class="description-content"></div>
                            </div>

                            <td>
                                @if ($item->galleries->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($item->galleries as $gallery)
                                            <img src="{{ $gallery->image_path }}" alt="Gallery Image" class="gallery-image"
                                                style="width: 40px; height: 30px; border-radius: 5px;">
                                        @endforeach
                                    </div>
                                @else
                                    Không có ảnh
                                @endif
                            </td>
                            <td>
                                @if ($item->sizes->isNotEmpty())
                                    {{ $item->sizes->pluck('size')->implode(', ') }}
                                @else
                                    Không có kích thước
                                @endif
                            </td>
                            <td>
                                @if ($item->colors->isNotEmpty())
                                    @foreach ($item->colors as $color)
                                        <span>{{ $color->name_color }}</span>@if (!$loop->last), @endif
                                    @endforeach
                                @else
                                    Không có màu
                                @endif
                            </td>

                            <td class="d-flex gap-2 justify-content-center">
                                @if ($item->is_active == 1)
                                    <a href="{{ route('products.edit', $item->id) }}"
                                        class="btn btn-outline-warning btn-sm">Cập nhật</a>
                                @else
                                    <a href=""
                                        onclick="return confirm('vui lòng cập nhật trạng thái sang hiển thị để thao tác')"
                                        class="btn btn-outline-warning btn-sm">Cập nhật</a>
                                @endif

                                <!-- Form cập nhật trạng thái -->
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

@endsection
