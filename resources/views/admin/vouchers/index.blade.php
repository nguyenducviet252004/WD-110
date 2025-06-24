@extends('layouts.app')

@section('title', 'Danh sách voucher')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Danh sách Voucher</h2>
    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">+ Tạo mới</a>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Code</th>
      <th>Tiêu đề</th>
      <th>Giảm</th>
      <th>Số lần voucher đã sử dụng</th>
      <th>Hết hạn</th>
      <th>Trạng thái</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    @foreach($vouchers as $v)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $v->code }}</td>
      <td>{{ $v->title }}</td>
      <td>{{ number_format($v->discount, 0) }}%</td>
      <td>{{ $v->used_count}}</td>
      <td>{{ $v->end_date_time->format('d/m/Y H:i') }}</td>
      <td>
        <span class="badge {{ $v->is_active ? 'bg-success' : 'bg-secondary' }}">
          {{ $v->is_active ? 'Hiệu lực' : 'Ngừng' }}
        </span>
      </td>
      <td>
        <a href="{{ route('admin.vouchers.edit', $v->id) }}" class="btn btn-sm btn-warning">Sửa</a>
        <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Xoá voucher này?');">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Xoá</button>
        </form>
      </td>
    </tr>
    {{ $vouchers->onEachSide(1)->links('pagination::bootstrap-5') }}
    @endforeach
  </tbody>
</table>
@endsection
