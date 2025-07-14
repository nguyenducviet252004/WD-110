@extends('Layout.Layout')

@section('title')
    Danh sách phiếu giảm giá
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

    <h1 class="text-center mt-5">Danh sách phiếu giảm giá</h1>

    <a class="btn btn-outline-success mb-3 mt-3" href="{{ route('vouchers.create') }}">Thêm mới voucher</a>

    <form method="GET" action="{{ route('vouchers.index') }}" id="filterForm" class="mb-3 p-3">
        <div class="row">
            <!-- Status Filter (Active/Inactive) -->
            <div class="col-md-2">
                <select name="status" id="status" class="form-select"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="" class="text-dark">Tất cả rạng thái</option>
                    <option value="1" class="text-dark" {{ request('status') == '1' ? 'selected' : '' }}>Đang hoạt động
                    </option>
                    <option value="0" class="text-dark" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt
                        động</option>
                </select>
            </div>

            <!-- Expiry Status Filter (Valid/Expired) -->
            <div class="col-md-2">
                <select name="expiry_status" id="expiry_status" class="form-select"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="" class="text-dark">Tất cả thời hạn</option>
                    <option value="valid" class="text-dark" {{ request('expiry_status') == 'valid' ? 'selected' : '' }}>Còn
                        hạn</option>
                    <option value="expired" class="text-dark" {{ request('expiry_status') == 'expired' ? 'selected' : '' }}>
                        Đã hết hạn</option>
                </select>
            </div>

            <!-- Sort Filter (Discount Value Asc/Desc) -->
            <div class="col-md-2">
                <select name="sort_by" id="sort_by" class="form-select"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="" class="text-dark">Giá mặc định</option>
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
                    <th scope="col">Giá trị</th>
                    <th scope="col">Đơn đạt tối thiểu</th>
                    <th scope="col">Đơn đạt tối đa</th>
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
                        <td>{{ $voucher->discount_value ?? 'N/A' }} VND</td>
                        <td>{{ $voucher->total_min ?? 'N/A' }} VND</td>
                        <td>{{ $voucher->total_max ?? 'N/A' }} VND</td>
                        <td>{{ $voucher->description ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($voucher->start_day)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($voucher->end_day)->format('d-m-Y') }}</td>
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
                            <a onclick="return confirm('Bạn có chắc muốn cập nhật trạng thái?')"
                                href="{{ route('vouchers.index', ['toggle_active' => $voucher->id]) }}"
                                class="btn {{ $voucher->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }} mb-3">
                                {{ $voucher->is_active ? 'Ẩn' : 'Hiện' }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="pagination justify-content-center">
            {{ $vouchers->links() }}
        </div>
    </div>
@endsection
