@extends('Layout.Layout')

@section('title')
    Quản lý tài khoản
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

    <h1 class="text-center mt-5 mb-3">Danh sách Người dùng</h1>
    <!-- Menu lọc trạng thái -->
    <div class="mb-3">
        <a href="{{ route('managers.index') }}" class="btn btn-info">Tất cả trạng thái</a>
        <a href="{{ route('managers.index', ['is_active' => 'locked']) }}" class="btn btn-warning">Đã khóa</a>
        <a href="{{ route('managers.index', ['is_active' => 'normal']) }}" class="btn btn-success">Bình thường</a>
    </div>

    <div class="table-responsive mt-5">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Họ Tên</th>
                    <th>Ngày Sinh</th>
                    <th>Số Điện Thoại</th>
                    <th>Địa Chỉ</th>
                    <th>Email</th>
                    <th>Vai Trò</th>
                    <th>is_active</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle" width="50px" height="50px"></td>
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->birth_day }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            @if ($user->role === 0)
                                <span class="badge badge-primary">User</span>
                            @elseif($user->role === 1)
                                <span class="badge badge-info">Manager</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($user->is_active)
                                <span class="badge bg-success">YES</span>
                            @else
                                <span class="badge bg-danger">NO</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('managers.update', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                @if ($user->is_active)
                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Bạn có chắc chắn muốn khóa tài khoản này?')">Khóa tài khoản </button>
                                @else
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Bạn có chắc chắn muốn mở khóa tài khoản này?')">Mở khóa tài khoản </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $data->links() }}
    </div>
@endsection
