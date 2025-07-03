<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile dropdown no-arrow">
        </li>
        <li class="nav-item">
            <a class="nav-link" ">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-tshirt-crew menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('products.index') }}">
                <span class="menu-title">Sản phẩm</span>
                <i class="mdi mdi-tshirt-crew menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('categories.index')}}">
                <span class="menu-title">Danh mục</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
        </li>
     
        <li class="nav-item">
            <a class="nav-link" href="{{route('sizes.index')}}">
                <span class="menu-title">Kích cỡ</span>
                <i class="mdi mdi-format-size menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('colors.index')}}">
                <span class="menu-title">Màu sắc</span>
                <i class="mdi mdi-palette menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('vouchers.index')}}">
                <span class="menu-title">Phiếu giảm giá</span>
                <i class="mdi mdi-ticket-percent menu-icon"></i>
            </a>
        </li>
      
        
    
    </ul>
</nav>
