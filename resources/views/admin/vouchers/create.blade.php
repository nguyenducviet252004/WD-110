@extends('layouts.app')

@section('title', 'Tạo voucher')

@section('content')
<h2>Tạo mới Voucher</h2>

<form method="POST" action="{{ route('admin.vouchers.store') }}">
  @csrf

  <div class="mb-3">
    <label>Mã code</label>
    <input type="text" name="code" value="{{ old('code', strtoupper(\Str::random(8))) }}" class="form-control">
    @error('code') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label>Tiêu đề</label>
    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label>Mô tả</label>
    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label>Giảm giá</label>
    <input type="number" min="1" name="discount" class="form-control" value="{{ old('discount') }}">
    @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label>Thời gian bắt đầu</label>
    <input type="datetime-local" name="start_date_time" class="form-control" value="{{ old('start_date_time') }}">
    @error('start_date_time') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label>Thời gian kết thúc</label>
    <input type="datetime-local" name="end_date_time" class="form-control" value="{{ old('end_date_time') }}">
    @error('end_date_time') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label>Trạng thái</label>
    <select name="is_active" class="form-select">
      <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Hiệu lực</option>
      <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Ngừng</option>
    </select>
    @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label>Giá trị đơn hàng tối thiểu</label>
    <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount') }}">
    @error('min_order_amount') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="mb-3">
    <label>Số lần sử dụng tối đa</label>
    <input type="number" name="max_usage" class="form-control" value="{{ old('max_usage') }}">
    @error('max_usage') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <button class="btn btn-success">Lưu</button>
  <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Quay lại</a>
</form>
@endsection
