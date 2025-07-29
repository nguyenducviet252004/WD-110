@extends('Layout.Layout')

@section('title', 'index Blog')

@section('content_admin')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="container">
        <h1 class="text-center mt-5 ">Danh sách bài viết </h1>
        <a href="{{ route('blog.create') }}" class="btn btn-primary mt-3 mb-33">Thêm mới </a>
        <table class="table table-bordered text-center mt-3 mb-5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề </th>
                    <th>Danh mục </th>
                    <th>Hình ảnh </th>
                    <th>Trạng thái </th>
                    <th>Thao tác </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($blogs as $blog)
                    <tr>
                        <td>{{ $blog->id }}</td>
                        <td>{{ $blog->title }}</td>
                        <td>{{ $blog->category->name ?? 'N/A' }}</td>
                        <td><img src="{{ asset('storage/' . $blog->image) }}" width="50" height="50"></td>
                        <td>{{ $blog->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-warning">Cập nhật </a>
                            <form action="{{ route('blog.destroy', $blog->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
