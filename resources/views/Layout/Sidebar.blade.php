<aside class="main-sidebar sidebar-dark-primary elevation-4" id="mainSidebar">
    <!-- Sidebar Toggle Button -->
    <div class="sidebar-toggle-container">
        <button class="sidebar-toggle-btn" id="sidebarToggle" data-bs-toggle="tooltip" data-bs-placement="right" title="Thu gọn/Mở rộng sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('storage/' . (Auth::user()->avatar ?? 'default-avatar.png')) }}" alt="Admin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Realtime Status Banner -->
    <div class="realtime-banner">
        <div class="realtime-status">
            <i class="fas fa-wifi"></i>
            <span class="banner-text">Realtime Updates: Currently in offline mode.</span>
        </div>
    </div>

    <div class="sidebar">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" style="height: 100%; display: flex; flex-direction: column;">
            <div class="menu-items" style="flex: 1; overflow-y: auto; padding-bottom: 1rem;">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{route('admin.dashboard')}}">
                        <i class="fas fa-home menu-icon"></i>
                        <span class="menu-title">Trang chủ</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="fas fa-tshirt menu-icon"></i>
                        <span class="menu-title">Sản phẩm</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{route('categories.index')}}">
                        <i class="fas fa-list menu-icon"></i>
                        <span class="menu-title">Danh mục</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{route('orders.index')}}">
                        <i class="fas fa-shopping-cart menu-icon"></i>
                        <span class="menu-title">Đơn hàng</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('managers.*') ? 'active' : '' }}" href="{{ route('managers.index') }}">
                        <i class="fas fa-users menu-icon"></i>
                        <span class="menu-title">Người dùng</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('review.*') ? 'active' : '' }}" href="{{route('review.index')}}">
                        <i class="fas fa-star menu-icon"></i>
                        <span class="menu-title">Đánh giá</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sizes.*') ? 'active' : '' }}" href="{{route('sizes.index')}}">
                        <i class="fas fa-ruler menu-icon"></i>
                        <span class="menu-title">Kích cỡ</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('colors.*') ? 'active' : '' }}" href="{{route('colors.index')}}">
                        <i class="fas fa-palette menu-icon"></i>
                        <span class="menu-title">Màu sắc</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('vouchers.*') ? 'active' : '' }}" href="{{route('vouchers.index')}}">
                        <i class="fas fa-percentage menu-icon"></i>
                        <span class="menu-title">Phiếu giảm giá</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('logo_banners.*') ? 'active' : '' }}" href="{{route('logo_banners.index')}}">
                        <i class="fas fa-image menu-icon"></i>
                        <span class="menu-title">Banner</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{route('blog.index')}}">
                        <i class="fas fa-newspaper menu-icon"></i>
                        <span class="menu-title">Bài viết</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.edit') ? 'active' : '' }}" href="{{route('admin.edit')}}">
                        <i class="fas fa-user menu-icon"></i>
                        <span class="menu-title">Hồ sơ</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.chat') ? 'active' : '' }}" href="{{ route('admin.chat') }}">
                        <i class="fas fa-comments menu-icon"></i>
                        <span class="menu-title">Chat</span>
                    </a>
                </li>
            </div>
            <li class="nav-item logout-item" style="margin-top: auto;">
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="nav-link logout-btn" onclick="return confirm('Chắc chắn đăng xuất?')">
                        <i class="fas fa-sign-out-alt menu-icon"></i>
                        <span class="menu-title">Đăng xuất</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
