<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') | WebsolAI</title>
    <link rel="icon" type="image/png" href="/images/logo.png">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    {{-- AdminLTE --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <style>
        body, .nav-sidebar .nav-link p, .brand-text { font-family: 'Inter', sans-serif !important; }
        .main-sidebar { background: #0f172a !important; }
        .brand-link { background: #0f172a !important; border-bottom-color: #1e293b !important; }
        .brand-link:hover { background: #1e293b !important; }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover { background: #4f46e5 !important; }
        .sidebar { scrollbar-width: thin; }
        .nav-sidebar .nav-link { border-radius: 8px; margin: 2px 8px; }
        .main-header { border-bottom: 1px solid #e2e8f0; }
        .content-header h1 { font-size: 1.35rem; font-weight: 700; color: #0f172a; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed" style="font-family:'Inter',sans-serif;">
<div class="wrapper">

    {{-- Top Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="{{ url('/') }}" target="_blank" class="nav-link text-muted" title="View website">
                    <i class="fas fa-external-link-alt mr-1"></i> View Website
                </a>
            </li>
            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-danger px-3" style="background:none;border:none;">
                        <i class="fas fa-sign-out-alt mr-1"></i> Sign Out
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-3">
        <a href="{{ route('admin.contacts.index') }}" class="brand-link px-4">
            <img src="/images/logo.png" alt="WebsolAI" class="brand-image" style="max-height:34px;width:34px;object-fit:cover;border-radius:8px;opacity:1;">
            <span class="brand-text font-weight-bold ml-2" style="color:#fff;">WebSol<span style="color:#818cf8;">AI</span></span>
        </a>
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="border-bottom:1px solid #1e293b;">
                <div class="image">
                    <img src="/images/logo.png" class="img-circle elevation-2" alt="Admin" style="width:34px;height:34px;object-fit:cover;">
                </div>
                <div class="info">
                    <span class="d-block" style="color:#94a3b8;font-size:0.8rem;font-weight:600;">Administrator</span>
                    <span style="color:#64748b;font-size:0.72rem;">{{ config('admin.email') }}</span>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-header" style="color:#475569;font-size:0.65rem;letter-spacing:.08em;">MAIN MENU</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}"
                           class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>
                                Contact Enquiries
                                @if(isset($unreadCount) && $unreadCount > 0)
                                <span class="badge badge-danger right">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                                @endif
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Content Wrapper --}}
    <div class="content-wrapper" style="background:#f8fafc;">

        {{-- Page Header --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="m-0">@yield('heading', 'Dashboard')</h1>
                        @hasSection('subheading')
                        <small class="text-muted">@yield('subheading')</small>
                        @endif
                    </div>
                    <div class="col-auto">
                        <ol class="breadcrumb float-sm-right mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title', 'Admin')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="content">
            <div class="container-fluid">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                @endif

                @yield('content')

            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="main-footer">
        <strong>&copy; {{ date('Y') }} <a href="{{ url('/') }}">WebsolAI</a></strong>
        <span class="float-right text-muted">Admin Panel</span>
    </footer>

    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>
