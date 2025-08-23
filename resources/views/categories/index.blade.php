@extends('Layout.Layout')

@section('title')
    Danh sách danh mục
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

    <h1 class="text-center mt-5">Danh sách danh mục</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('categories.create') }}" class="btn btn-outline-success mb-3">Thêm mới</a>
    </div>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th scope="col">Số lượng sản phẩm</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Tạo</th>
                <th scope="col">Sửa</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->products()->count() }}</td>
                    <td>
                        @if ($category->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Không hoạt động</span>
                        @endif
                    </td>

                    <td>{{ $category->created_at ? $category->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td>{{ $category->updated_at ? $category->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td>
                        <a onclick="return confirm('Bạn có chắc muốn cập nhật trạng thái?')"
                            href="{{ route('categories.index', ['toggle_active' => $category->id]) }}"
                            class="btn {{ $category->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }} mb-3">
                            {{ $category->is_active ? 'Ẩn' : 'Hiện' }}
                        </a>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-warning mb-3">Cập
                            nhật</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger mb-3"
                                onclick="return confirm('Bạn có chắc muốn xóa không?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $categories->links() }}
    </div>
@endsection
