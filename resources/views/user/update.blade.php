@extends('user.master')

@section('title')
    Cập nhật tài khoản
@endsection

@section('content')
    <h1 class="text-center">Cập nhật tài khoản</h1>

    <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="fullname">Full Name</label>
        <input type="text" class="form-control mb-3" name="fullname" id="fullname" value="{{ old('fullname', Auth::user()->fullname) }}">

        <label for="birth_day">Birth Day</label>
        <input type="date" class="form-control mb-3" name="birth_day" id="birth_day" value="{{ old('birth_day', Auth::user()->birth_day) }}">

        <label for="phone">Phone</label>
        <input type="text" class="form-control mb-3" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}">

        <label for="email">Email</label>
        <input type="email" class="form-control mb-3" name="email" id="email" value="{{ old('email', Auth::user()->email) }}">

        <div class="d-flex">
            @if(!empty(Auth::user()->email))
                @if(Auth::user()->email_verified_at == null)
                    <span>Trạng thái:</span>
                    <p style="color: red" class="ms-3">Chưa xác thực</p>
                    <div class="">
                        <a href="{{ route('verify') }}" class="btn badge bg-success ms-3">Xác minh email</a>
                    </div>
                @else
                    <span>Trạng thái email:</span>
                    <p style="color: green" class="ms-3">Đã xác thực</p>
                @endif
            @endif
        </div>
        
        <label for="address">Address</label>
        <input type="text" class="form-control mb-3" name="address" id="address" value="{{ old('address', Auth::user()->address) }}">

        <label for="ship_address">Ship Default</label>
        <a href="{{route('address.create')}}" class="btn badge bg-success ms-3 mb-2">Thêm mới</a>
        <select name="address_id" class="form-control">
            @if ($addresses->isNotEmpty())
                @php
                    $defaultAddress = $addresses->firstWhere('is_default', 1);
                @endphp
        
                @if ($defaultAddress)
                    <option value="{{ $defaultAddress->id }}">
                        <strong style="color: red;">Địa chỉ:</strong> {{ $defaultAddress->ship_address }} - 
                        <strong style="color: red;">Số điện thoại:</strong> {{ $defaultAddress->phone_number }} - 
                        <strong style="color: red;">Tên người nhận:</strong> {{ $user->recipient_name ?? $user->fullname }}
                    </option>
                @else
                    <option value="">Chưa có địa chỉ mặc định, hãy thêm địa chỉ mới</option>
                @endif
            @else
                <option value="">Chưa có địa chỉ giao hàng nào, hãy thêm địa chỉ mới</option>
            @endif
        
            <!-- Duyệt qua tất cả địa chỉ để tạo danh sách chọn -->
            @foreach ($addresses as $address)
                <option value="{{ $address->id }}">
                    {{ $address->ship_address }} - {{ $address->phone_number }} - {{ $user->fullname ?? $user->account }}
                </option>
            @endforeach
        </select>
        
        <label for="avatar" class="mt-3">Avatar</label>
        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" width="100px" class="ms-3 mt-3">
        <input type="file" class="form-control mb-3 mt-3" name="avatar" id="avatar">

        <button type="submit" class="btn btn-success mt-3">Cập nhật</button>
        <a href="{{route('user.dashboard')}}" class="btn btn-secondary mt-3">Quay lai</a>
        
    </form>
@endsection
