<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- AdminLTE JS & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ asset('js/fix-errors.js') }}"></script>

    <link rel="icon" href="{{ asset('110.jpg') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Modern Admin CSS - Applied to all pages -->
    <link rel="stylesheet" href="{{ asset('css/modern-admin.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/adminlte-compatibility.css') }}?v={{ time() }}">

    @stack('styles')
</head>


<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Desktop Sidebar Toggle (when collapsed) -->
                    <button class="desktop-sidebar-toggle" id="desktopSidebarToggle" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            @include('Layout.Nav')
        </nav>
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" id="mainSidebar">
            @include('Layout.Sidebar')
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content pt-3">
                <div class="container-fluid">
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
                    @yield('content_admin')
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->
    </div>

    <!-- Custom JS (nếu cần) -->
    <script src="{{ url('js/chart.umd.js') }}"></script>
    <script src="{{ url('js/dashboard.js') }}"></script>
    <script src="{{ url('js/vendor.bundle.base.js') }}"></script>
    <script src="{{ url('js/misc.js') }}"></script>

    <!-- Modern Admin JS -->
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mainSidebar = document.getElementById('mainSidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const desktopSidebarToggle = document.getElementById('desktopSidebarToggle');
            const contentWrapper = document.querySelector('.content-wrapper');

            // Mobile menu toggle
            if (mobileMenuToggle && mainSidebar && sidebarOverlay) {
                mobileMenuToggle.addEventListener('click', function() {
                    mainSidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });

                sidebarOverlay.addEventListener('click', function() {
                    mainSidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            // Desktop sidebar toggle
            if (sidebarToggle && mainSidebar && contentWrapper) {
                // Load saved state from localStorage
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    mainSidebar.classList.add('collapsed');
                    contentWrapper.classList.add('sidebar-collapsed');
                    // Show desktop toggle button
                    if (desktopSidebarToggle) {
                        desktopSidebarToggle.style.display = 'flex';
                    }
                } else {
                    // Ensure sidebar is expanded and content has proper margin
                    mainSidebar.classList.remove('collapsed');
                    contentWrapper.classList.remove('sidebar-collapsed');
                    // Hide desktop toggle button
                    if (desktopSidebarToggle) {
                        desktopSidebarToggle.style.display = 'none';
                    }
                }

                sidebarToggle.addEventListener('click', function() {
                    const isCurrentlyCollapsed = mainSidebar.classList.contains('collapsed');

                    if (isCurrentlyCollapsed) {
                        // Expand sidebar
                        mainSidebar.classList.remove('collapsed');
                        contentWrapper.classList.remove('sidebar-collapsed');
                        localStorage.setItem('sidebarCollapsed', 'false');
                        // Hide desktop toggle button
                        if (desktopSidebarToggle) {
                            desktopSidebarToggle.style.display = 'none';
                        }
                    } else {
                        // Collapse sidebar
                        mainSidebar.classList.add('collapsed');
                        contentWrapper.classList.add('sidebar-collapsed');
                        localStorage.setItem('sidebarCollapsed', 'true');
                        // Show desktop toggle button
                        if (desktopSidebarToggle) {
                            desktopSidebarToggle.style.display = 'flex';
                        }
                    }
                });
            }

            // Desktop sidebar toggle (when collapsed)
            if (desktopSidebarToggle && mainSidebar && contentWrapper) {
                desktopSidebarToggle.addEventListener('click', function() {
                    // Expand sidebar
                    mainSidebar.classList.remove('collapsed');
                    contentWrapper.classList.remove('sidebar-collapsed');
                    localStorage.setItem('sidebarCollapsed', 'false');
                    // Hide desktop toggle button
                    this.style.display = 'none';
                });
            }

            // Force apply sidebar styles on page load
            function forceApplySidebarStyles() {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                const contentWrapper = document.querySelector('.content-wrapper');

                if (contentWrapper) {
                    if (isCollapsed) {
                        contentWrapper.classList.add('sidebar-collapsed');
                        contentWrapper.style.marginLeft = '0px';
                    } else {
                        contentWrapper.classList.remove('sidebar-collapsed');
                        contentWrapper.style.marginLeft = '250px';
                    }
                }
            }

            // Apply styles immediately and after delays
            forceApplySidebarStyles();
            setTimeout(forceApplySidebarStyles, 100);
            setTimeout(forceApplySidebarStyles, 500);

            // Hide Realtime Updates banners
            function hideRealtimeBanners() {
                try {
                    // Hide by class names
                    const banners = document.querySelectorAll('.realtime-banner, .realtime-status');
                    banners.forEach(banner => {
                        banner.style.display = 'none';
                        banner.style.visibility = 'hidden';
                    });
                } catch (error) {
                    console.log('Error hiding banners:', error);
                }
            }

            // Hide banners after page loads
            setTimeout(hideRealtimeBanners, 100);
            setTimeout(hideRealtimeBanners, 500);

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add loading animation to buttons
            document.querySelectorAll('.btn-modern, .action-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    if (this.type === 'submit' || this.tagName === 'A') {
                        this.classList.add('loading');
                    }
                });
            });

            // Smooth scroll for pagination
            document.querySelectorAll('.pagination-modern a').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    if (href && href !== '#') {
                        window.location.href = href;
                    }
                });
            });
        });
    </script>
</body>

</html>
