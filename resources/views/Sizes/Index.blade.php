@extends('Layout.Layout')

@section('title')
    Danh sách kích cỡ
@endsection

@section('content_admin')
   

    <h1 class="text-center mt-5">Danh sách kích cỡ</h1>
    <a class="btn btn-outline-success mb-3 mt-3" href="{{ route('sizes.create') }}">Thêm mới</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kích cỡ</th>
                    <!-- <th>Sản phẩm liên quan</th> Thêm cột này để hiển thị số lượng sản phẩm -->
                    <th>Tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $size)
                    <tr>
                        <td>{{ $size->id }}</td>
                        <td>{{ $size->name }}</td>

                        <!-- Hiển thị số lượng sản phẩm liên quan
                        <td>
                            {{ $size->product_count }} sản phẩm
                        </td> -->

                        <td>{{ $size->created_at ? $size->created_at->format('d/m/Y H:i') : 'N/A' }}</td>

                        <td>
                            {{-- <a class="btn btn-info" href="{{ route('sizes.show', $size->id) }}">Xem</a> --}}
                            <a class="btn btn-outline-warning mb-3" href="{{ route('sizes.edit', $size->id) }}">Cập nhật</a>
                            <form action="{{ route('sizes.destroy', $size->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"
                                    class="btn btn-outline-danger mb-3">
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
