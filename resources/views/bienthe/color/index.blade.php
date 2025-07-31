@extends('Layout.Layout')

@section('title')
    Danh sách màu sắc
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
    <h1 class="text-center mt-5 mb-3"> Danh sách màu sắc</h1>
    <a class="btn btn-outline-success mb-3" href="{{ route('colors.create') }}">Thêm mới</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Tên màu sắc</th>
                    <th scope="col">Sản phẩm liên quan</th> <!-- Thêm cột này để hiển thị số lượng sản phẩm -->
                    <th scope="col">Tạo</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $color)
                    <tr>
                        <td>{{ $color->id }}</td>
                        <td>{{ $color->name_color }}</td>
    
                        <!-- Hiển thị số lượng sản phẩm liên quan -->
                        <td>
                            {{ $color->product_count }} sản phẩm
                        </td>
    
                        <td>{{ $color->created_at ? $color->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>
                            {{-- <a class="btn btn-info" href="{{ route('colors.show', $color->id) }}">Xem</a> --}}
                            <a class="btn btn-outline-warning mb-3" href="{{ route('colors.edit', $color->id) }}">Cập nhật</a>
                            <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')" class="btn btn-outline-danger mb-3">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $data->links() }}
    </div>
    
@endsection

