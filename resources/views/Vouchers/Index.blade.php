@extends('Layout.Layout')

@section('title')
    Danh sách phiếu giảm giá
@endsection

@section('content_admin')


    <h1 class="text-center mt-5">Danh sách phiếu giảm giá</h1>

    <a class="btn btn-outline-success mb-3 mt-3" href="{{ route('vouchers.create') }}">Thêm mới voucher</a>

    <form method="GET" action="{{ route('vouchers.index') }}" id="filterForm" class="mb-3 p-3">
        <div class="row">
            <!-- Status Filter (Active/Inactive) -->
            <div class="col-md-2">
                <select name="is_active" id="is_active" class="form-select"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="" class="text-dark">Tất cả trạng thái</option>
                    <option value="1" class="text-dark" {{ request('is_active') == '1' ? 'selected' : '' }}>Đang hoạt động
                    </option>
                    <option value="0" class="text-dark" {{ request('is_active') == '0' ? 'selected' : '' }}>Không hoạt
                        động</option>
                </select>
            </div>

            <!-- Sort Filter (Discount Value Asc/Desc) -->
            <div class="col-md-2">
                <select name="sort_by" id="sort_by" class="form-select"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="" class="text-dark">Sắp xếp mặc định</option>
                    <option value="asc" class="text-dark" {{ request('sort_by') == 'asc' ? 'selected' : '' }}>Giảm dần
                    </option>
                    <option value="desc" class="text-dark" {{ request('sort_by') == 'desc' ? 'selected' : '' }}>Tăng dần
                    </option>
                </select>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Mã giảm giá</th>
                    <th scope="col">Tiêu đề</th>
                    <th scope="col">Giá trị giảm giá</th>
                    <th scope="col">Đơn tối thiểu</th>
                    <th scope="col">Số lần dùng tối đa</th>
                    <th scope="col">Đã dùng</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Bắt đầu</th>
                    <th scope="col">Kết thúc</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->id }}</td>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ $voucher->title ?? 'N/A' }}</td>
                        <td>{{ $voucher->discount ?? 'N/A' }}</td>
                        <td>{{ $voucher->min_order_amount ?? 'N/A' }}</td>
                        <td>{{ $voucher->max_usage ?? 'N/A' }}</td>
                        <td>{{ $voucher->used_count ?? 0 }}</td>
                        <td>{{ $voucher->description ?? 'N/A' }}</td>
                        <td>{{ $voucher->start_date_time ? \Carbon\Carbon::parse($voucher->start_date_time)->format('d-m-Y H:i') : '' }}</td>
                        <td>{{ $voucher->end_date_time ? \Carbon\Carbon::parse($voucher->end_date_time)->format('d-m-Y H:i') : '' }}</td>
                        <td>
                            @if ($voucher->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Không hoạt động</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-outline-warning mb-3" href="{{ route('vouchers.edit', $voucher->id) }}">
                                Cập nhật</a>
                            <form action="{{ route('vouchers.toggle-status', $voucher->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button type="submit" onclick="return confirm('Bạn có chắc muốn cập nhật trạng thái?')"
                                    class="btn {{ $voucher->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }} mb-3">
                                    {{ $voucher->is_active ? 'Ẩn' : 'Hiện' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination justify-content-center">
            {{ $vouchers->links() }}
        </div>
    </div>
@endsection
