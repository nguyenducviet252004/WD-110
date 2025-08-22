<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Nhúng Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('user/style.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="container mt-5">
    <div class="d-flex">
        <!-- Phần Menu -->
        <div class="menu-container bg-light p-3">
            <a href="{{ route('user.edit') }}">
                <div class="nav-profile-text d-flex align-items-center">

                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" width="50px" alt="profile"
                        class="img-profile rounded-circle" />
                    <span class="login-status online"></span>

                    <div class="ms-2">
                        <span class="d-block" style="color: cadetblue">Xin chào</span>
                        <span
                            style="color: purple">{{ Auth::user()->fullname ?? (Auth::user()->email ?? Auth::user()->username) }}</span>
                    </div>

                    <i class="fa-solid fa-circle ms-2 mt-4" style="color: green; font-size: 10px;"></i>
                </div>
            </a>
            <ul class="menu list-unstyled">
                <li><a href="http://localhost:3000" class="btn btn-light w-100 text-start">Quay lại trang chủ</a></li>
                <li class="dropdown">
                    <button class="dropdown-btn btn btn-light w-100 text-start" onclick="toggleDropdown(this)">
                        <i class="icon-user"></i> Tài Khoản Của Tôi
                    </button>
                    <ul class="dropdown-content list-unstyled ps-3" style="display: block;"> <!-- Đặt display: block -->
                        <li><a href="{{ route('user.edit') }}">Hồ Sơ</a></li>
                        <li><a href="{{ route('address.index') }}">Địa Chỉ</a></li>
                        <li><a href="{{ route('user.changepass.form') }}">Đổi Mật Khẩu</a></li>
                    </ul>
                </li>
                <li class="zalo-icon">
                    <a href="https://zalo.me/g/bqdqdb905" target="_blank" title="Chăm sóc khách hàng">
                        <i class="fas fa-headset"></i>
                    </a>
                    <span class="tooltip">Chăm sóc khách hàng</span>
                </li>

                <style>
                    /* Vị trí và kiểu dáng của biểu tượng Zalo */
                    .zalo-icon {
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        z-index: 9999;
                        text-align: center;
                    }

                    .zalo-icon a {
                        display: inline-block;
                        width: 60px;
                        height: 60px;
                        background-color: #25D366;
                        /* Màu xanh của Zalo */
                        border-radius: 50%;
                        overflow: hidden;
                        position: relative;
                        text-align: center;
                        line-height: 60px;
                        /* Căn giữa icon trong vòng tròn */
                    }

                    .zalo-icon i {
                        font-size: 30px;
                        /* Điều chỉnh kích thước của icon */
                        color: white;
                    }

                    .zalo-icon .tooltip {
                        display: none;
                        position: absolute;
                        bottom: 80px;
                        left: 50%;
                        transform: translateX(-50%);
                        background-color: #333;
                        color: #fff;
                        padding: 5px 10px;
                        border-radius: 5px;
                        font-size: 14px;
                        white-space: nowrap;
                        z-index: 10000;
                    }

                    .zalo-icon:hover .tooltip {
                        display: block;
                    }
                </style>

                <li><a href="{{ route('userorder.index') }}" class="btn btn-light w-100 text-start">Đơn Mua</a></li>
                <li><a href="{{ route('uservouchers.index') }}" class="btn btn-light w-100 text-start">Kho Voucher</a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn badge bg-danger ms-3 mt-2"
                            onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">Đăng xuất</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Phần Content -->
        <div class="content-container p-4 flex-grow-1">
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



            @yield('content')
        </div>
    </div>


    <!-- Nhúng Bootstrap JavaScript -->
    <script src="{{ asset('user/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
