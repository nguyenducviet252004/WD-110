@extends('layouts.app')

@section('title', 'Chỉnh sửa voucher')

@section('content')
<h2>Chỉnh sửa Voucher</h2>

{{-- Thông báo lỗi tổng quát --}}
{{-- @if ($errors->any())
  <div class="alert alert-danger">
    <strong>Đã có lỗi xảy ra:</strong>
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif --}}

<form method="POST" action="{{ route('admin.vouchers.update', $voucher->id) }}">
  @csrf
  @method('PUT')

  <div class="mb-3">
    <label for="code" class="form-label">Mã code</label>
    <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $voucher->code) }}"  disabled>
    @error('code') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label for="title" class="form-label">Tiêu đề</label>
    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $voucher->title) }}">
    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">Mô tả</label>
    <textarea id="description" name="description" class="form-control">{{ old('description', $voucher->description) }}</textarea>
    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label for="discount" class="form-label">Giảm giá</label>
    <input type="number" id="discount" name="discount" class="form-control" value="{{ old('discount', (int) $voucher->discount) }}" >
    @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label for="start_date_time" class="form-label">Thời gian bắt đầu</label>
    <input type="datetime-local" id="start_date_time" name="start_date_time" class="form-control"
           value="{{ old('start_date_time', $voucher->start_date_time->format('Y-m-d\TH:i')) }}" >
    @error('start_date_time') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label for="end_date_time" class="form-label">Thời gian kết thúc</label>
    <input type="datetime-local" id="end_date_time" name="end_date_time" class="form-control"
           value="{{ old('end_date_time', $voucher->end_date_time->format('Y-m-d\TH:i')) }}" >
    @error('end_date_time') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label for="is_active" class="form-label">Trạng thái</label>
    <select id="is_active" name="is_active" class="form-select">
      <option value="1" {{ old('is_active', $voucher->is_active) ? 'selected' : '' }}>Hiệu lực</option>
      <option value="0" {{ !old('is_active', $voucher->is_active) ? 'selected' : '' }}>Ngừng</option>
    </select>
    @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label for="min_order_amount" class="form-label">Giá trị đơn hàng tối thiểu</label>
    <input type="number" id="min_order_amount" name="min_order_amount" class="form-control"
           value="{{ old('min_order_amount', (int) $voucher->min_order_amount) }}">
    @error('min_order_amount') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label for="max_usage" class="form-label">Số lần sử dụng tối đa</label>
    <input type="number" id="max_usage" name="max_usage" class="form-control"
           value="{{ old('max_usage', $voucher->max_usage) }}">
    @error('max_usage') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <button type="submit" class="btn btn-primary">Cập nhật</button>
  <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Quay lại</a>
</form>
@endsection
