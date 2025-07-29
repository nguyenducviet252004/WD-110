@extends('user.master')

@section('title')
    Địa chỉ
@endsection

@section('content')



    <h2 class="text-center"> Địa chỉ của tôi</h2>
    <a href="{{ route('address.create') }}" class="btn btn-success">Thêm mới</a>

    <ul class="list-group mt-2">
        @foreach ($addresses as $address)
            <li class="list-group-item {{ $address->is_default ? 'list-group-item-primary' : '' }}">
                <strong>Tên người nhận:</strong> {{ $address->recipient_name }}<br>
                <strong>Địa chỉ:</strong> {{ $address->ship_address }}<br>
                <strong>Số điện thoại:</strong> {{ $address->phone_number }}<br>
                @if ($address->is_default)
                    <span class="badge bg-primary">Địa chỉ mặc định</span>
                @else
                    <form action="{{ route('address.set-default', $address->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-primary">Đặt làm địa chỉ mặc định</button>
                    </form>
                @endif
                <a href="{{ route('address.edit', $address->id) }}" class="btn btn-sm btn-warning float-end ms-5 ">Cập
                    nhật</a>

                <form action="{{ route('address.destroy', $address->id) }}" method="POST" class="d-inline float-end ms-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')"
                        class="btn btn-sm btn-outline-danger">
                        Xóa
                    </button>
                </form>
            </li>
        @endforeach
    </ul>

    <!-- Phân trang -->
    <div class="mt-3">
        {{ $addresses->links() }}
    </div>
@endsection
