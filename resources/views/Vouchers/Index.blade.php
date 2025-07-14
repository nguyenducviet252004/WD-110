@extends('Layout.Layout')

@section('title')
    Danh sách phiếu giảm giá
@endsection

@section('content_admin')
    <h1 class="text-center mt-5">Danh sách phiếu giảm giá</h1>

    <a class="btn btn-outline-success mb-3 mt-3" href="{{ route('admin.vouchers.create') }}">Thêm mới voucher</a>

    <form method="GET" action="{{ route('admin.vouchers.index') }}" id="filterForm" class="mb-3 p-3 border rounded bg-light">
        <div class="row g-2 align-items-end">

            {{-- Trạng thái --}}
            <div class="col-md-2">
                <label for="is_active" class="form-label">Trạng thái</label>
                <select name="is_active" id="is_active" class="form-select" onchange="this.form.submit()" style="height: 48px; border-radius: 0;">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>

            {{-- Từ ngày --}}
            <div class="col-md-3">
                <label for="start_day" class="form-label">Từ ngày</label>
                <input type="date" name="start_day" id="start_day" class="form-control"
                    value="{{ request('start_day') }}">
            </div>

            {{-- Đến ngày --}}
            <div class="col-md-3">
                <label for="end_day" class="form-label">Đến ngày</label>
                <input type="date" name="end_day" id="end_day" class="form-control" value="{{ request('end_day') }}">
            </div>

            {{-- Giá trị giảm từ --}}
            <div class="col-md-2">
                <label for="discount_min" class="form-label">Giảm giá từ</label>
                <input type="number" name="discount_min" id="discount_min" class="form-control"
                    value="{{ request('discount_min') }}">
            </div>

            {{-- Giá trị giảm đến --}}
            <div class="col-md-2">
                <label for="discount_max" class="form-label">Đến</label>
                <input type="number" name="discount_max" id="discount_max" class="form-control"
                    value="{{ request('discount_max') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label d-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </div>
    </form>


    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Mã</th>
                    <th>Giảm giá</th>
                    <th>Đơn tối thiểu</th>
                    <th>Đơn tối đa</th>
                    <th>SL còn</th>
                    <th>Đã dùng</th>
                    <th>Mô tả</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->id }}</td>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ number_format($voucher->discount_value, 0, ',', '.') }}đ</td>
                        <td>{{ number_format($voucher->total_min, 0, ',', '.') }}đ</td>
                        <td>{{ $voucher->total_max ? number_format($voucher->total_max, 0, ',', '.') . 'đ' : 'Không giới hạn' }}
                        </td>
                        <td>{{ $voucher->quantity - $voucher->used_times }}</td>
                        <td>{{ $voucher->used_times }}</td>
                        <td>{{ $voucher->description ?? '—' }}</td>
                        <td>{{ $voucher->start_day ? \Carbon\Carbon::parse($voucher->start_day)->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td>{{ $voucher->end_day ? \Carbon\Carbon::parse($voucher->end_day)->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td>
                            @if ($voucher->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Không hoạt động</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-sm btn-warning"
                                href="{{ route('admin.vouchers.edit', $voucher->id) }}">Sửa</a>
                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Xóa voucher này?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">Không có voucher nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination justify-content-center">
            {{ $vouchers->withQueryString()->links() }}
        </div>
    </div>
@endsection
