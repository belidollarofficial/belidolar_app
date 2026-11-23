<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', $title ?? 'Dashboard') - {{ config('app.name', 'Beli Dolar') }} Admin</title>
    <link rel="icon" href="{{ \App\Support\Branding::faviconUrl() }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; }
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --bg: #0f1724;
            --bg-card: #1e293b;
            --bg-sidebar: #111c31;
            --border: #334155;
            --text: #e5e7eb;
            --text-muted: #94a3b8;
        }
        body {
            background: var(--bg);
            color: var(--text);
        }
        .admin-sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            transition: transform 0.3s ease;
        }
        .admin-sidebar .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .admin-sidebar .nav-item:hover {
            background: rgba(79, 70, 229, 0.15);
            color: #ffffff;
        }
        .admin-sidebar .nav-item.active {
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.35);
        }
        .admin-sidebar .nav-item i { width: 20px; text-align: center; }
        .admin-topbar {
            height: 64px;
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
        }
        .user-menu { position: relative; }
        .user-menu .dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            min-width: 200px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.4);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: all 0.2s;
            z-index: 50;
        }
        .user-menu .dropdown.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .admin-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            color: white;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(79, 70, 229, 0.4);
            color: white;
        }
        .btn-ghost {
            background: #334155;
            color: #e5e7eb;
            transition: all 0.3s;
        }
        .btn-ghost:hover { background: #3b4a63; color: #ffffff; }
        .btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #fbbf24;
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.35);
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .btn-reset:hover:not(:disabled) {
            background: rgba(245, 158, 11, 0.22);
            color: #fcd34d;
        }
        .btn-reset:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: #0f1724;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        .rte-wrapper {
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: #0f1724;
        }
        .rte-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px;
            padding: 8px;
            background: #1e293b;
            border-bottom: 1px solid var(--border);
        }
        .rte-toolbar button,
        .rte-toolbar select {
            height: 32px;
            min-width: 32px;
            border-radius: 8px;
            border: 1px solid transparent;
            background: transparent;
            color: #cbd5e1;
            font-size: 13px;
            padding: 0 8px;
            transition: all 0.15s;
        }
        .rte-toolbar button:hover,
        .rte-toolbar select:hover {
            background: #334155;
            color: #ffffff;
        }
        .rte-toolbar select {
            background: #0f1724;
            border-color: var(--border);
        }
        .rte-sep {
            width: 1px;
            height: 20px;
            background: var(--border);
            margin: 0 4px;
        }
        .rte-content {
            min-height: 280px;
            max-height: 520px;
            overflow-y: auto;
            padding: 16px 18px;
            color: #e5e7eb;
            font-size: 14px;
            line-height: 1.75;
            outline: none;
        }
        .rte-content:empty::before {
            content: 'Tulis isi artikel di sini...';
            color: #64748b;
        }
        .rte-content h2 { font-size: 1.4rem; font-weight: 800; color: #fff; margin: 1rem 0 0.5rem; }
        .rte-content h3 { font-size: 1.2rem; font-weight: 700; color: #fff; margin: 0.9rem 0 0.4rem; }
        .rte-content h4 { font-size: 1.05rem; font-weight: 700; color: #fff; margin: 0.8rem 0 0.35rem; }
        .rte-content p { margin: 0.5rem 0; }
        .rte-content ul { list-style: disc; padding-left: 1.4rem; }
        .rte-content ol { list-style: decimal; padding-left: 1.4rem; }
        .rte-content blockquote { border-left: 3px solid var(--primary); padding-left: 1rem; color: #cbd5e1; margin: 0.75rem 0; }
        .rte-content a { color: #22d3ee; text-decoration: underline; }
        .rte-content pre { background: #020617; border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem; overflow-x: auto; }
        .rte-content code { background: #020617; border: 1px solid var(--border); border-radius: 4px; padding: 0 0.25rem; }
        .admin-table th, .admin-table td {
            padding: 12px 16px;
            font-size: 14px;
            text-align: left;
        }
        .admin-table thead th {
            background: #0f1724;
            color: var(--text-muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .admin-table tbody tr { border-top: 1px solid var(--border); }
        .admin-table tbody tr:hover { background: rgba(79, 70, 229, 0.06); }
        .badge { font-size: 12px; padding: 4px 12px; border-radius: 999px; font-weight: 600; }
        .badge-green { background: rgba(34,197,94,.15); color: #4ade80; }
        .badge-red { background: rgba(239,68,68,.15); color: #f87171; }
        .badge-gray { background: rgba(148,163,184,.15); color: #94a3b8; }
        .pagination { display: flex; gap: 6px; flex-wrap: wrap; }
        .pagination a, .pagination span {
            min-width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text);
            font-size: 13px;
        }
        .pagination .active { background: var(--primary); border-color: var(--primary); color: #fff; }
        .overlay-sidebar {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 30;
            display: none;
        }
        /* Toast */
        #toast-container {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
            max-width: 380px;
            width: calc(100vw - 40px);
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }
        .toast-item {
            pointer-events: auto;
            background: #1e293b;
            border-left: 4px solid #4f46e5;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            animation: slideInLeft 0.4s ease;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .toast-item.border-success { border-left-color: #22c55e; }
        .toast-item.border-warning { border-left-color: #f59e0b; }
        .toast-item.border-error   { border-left-color: #ef4444; }
        .toast-item.border-info    { border-left-color: #4f46e5; }
        .toast-item.toast-out { opacity: 0; transform: translateX(-100%); }
        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @media (max-width: 1023px) {
            .admin-sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 40; transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .overlay-sidebar.show { display: block; }
        }
    </style>
</head>
<body>

    <div class="flex min-h-screen">

        <!-- ===== SIDEBAR ===== -->
        <aside class="admin-sidebar fixed inset-y-0 left-0 z-40 lg:static lg:translate-x-0" id="adminSidebar">
            <div class="h-[64px] flex items-center gap-3 px-5 border-b border-[#334155]">
                <x-brand-mark class="w-9 h-9 rounded-xl" />
                <div>
                    <p class="font-extrabold leading-tight">
                        <span class="text-[#818cf8]">Beli</span> <span class="text-[#22d3ee]">Dolar</span>
                        <span class="text-xs text-[#94a3b8] font-medium">Admin</span>
                    </p>
                </div>
            </div>

            <nav class="p-4 space-y-1.5 overflow-y-auto" style="max-height: calc(100vh - 64px);">
                <p class="px-3 pt-1 pb-2 text-[11px] font-bold uppercase tracking-wider text-[#64748b]">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Manajemen User
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Transaksi
                </a>
                <a href="{{ route('admin.contents.index') }}" class="nav-item {{ request()->routeIs('admin.contents.*') ? 'active' : '' }}">
                    <i class="fas fa-pen"></i> Konten Landing
                </a>
                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Pengaturan (Kurs & Rate)
                </a>
                <a href="{{ route('admin.rate-ranges.index') }}" class="nav-item {{ request()->routeIs('admin.rate-ranges.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i> Rate Range
                </a>
                <a href="{{ route('admin.bank-accounts.index') }}" class="nav-item {{ request()->routeIs('admin.bank-accounts.*') ? 'active' : '' }}">
                    <i class="fas fa-building-columns"></i> Rekening Bank
                </a>
                <a href="{{ route('admin.payout-methods.index') }}" class="nav-item {{ request()->routeIs('admin.payout-methods.*') ? 'active' : '' }}">
                    <i class="fas fa-wallet"></i> Metode Pencairan
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="nav-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <i class="fas fa-comment-dots"></i> Testimoni
                </a>
                <a href="{{ route('admin.posts.index') }}" class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> Blog / Artikel
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="nav-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <i class="fas fa-circle-question"></i> Pertanyaan Umum
                </a>
                <a href="{{ route('admin.banned-words.index') }}" class="nav-item {{ request()->routeIs('admin.banned-words.*') ? 'active' : '' }}">
                    <i class="fas fa-ban"></i> Filter Kata
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Laporan
                </a>

                <p class="px-3 pt-4 pb-2 text-[11px] font-bold uppercase tracking-wider text-[#64748b]">Lainnya</p>

                <a href="{{ route('admin.password.edit') }}" class="nav-item {{ request()->routeIs('admin.password.*') ? 'active' : '' }}">
                    <i class="fas fa-key"></i> Ubah Password
                </a>
                <a href="{{ route('beranda') }}" target="_blank" class="nav-item">
                    <i class="fas fa-globe"></i> Lihat Website
                </a>
            </nav>
        </aside>

        <div class="overlay-sidebar lg:hidden" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- ===== CONTENT ===== -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Topbar -->
            <header class="admin-topbar sticky top-0 z-20 flex items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="lg:hidden text-[#94a3b8] hover:text-white text-xl bg-[#0f1724] w-10 h-10 rounded-xl" aria-label="Buka menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-lg font-bold text-white">@yield('title', $title ?? 'Dashboard')</h1>
                </div>

                <div class="user-menu">
                    <button onclick="toggleUserMenu(event)" class="flex items-center gap-3 hover:bg-[#0f1724] rounded-xl px-3 py-2 transition">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="hidden sm:block text-sm font-semibold text-white text-left leading-tight">
                            {{ auth()->user()->name }}
                            <span class="block text-xs font-normal text-[#94a3b8]">{{ auth()->user()->email }}</span>
                        </span>
                        <i class="fas fa-chevron-down text-xs text-[#64748b]"></i>
                    </button>

                    <div class="dropdown" id="userDropdown">
                        {{-- <a href="{{ route('beranda') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#e5e7eb] hover:bg-[#0f1724] transition no-underline">
                            <i class="fas fa-globe w-4 text-center text-[#94a3b8]"></i> Lihat Website
                        </a> --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-400 hover:bg-[#0f1724] transition">
                                <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="p-4 lg:p-8 flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        function toggleUserMenu(event) {
            event.preventDefault();
            event.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('open');
        }

        document.addEventListener('click', function (event) {
            var dropdown = document.getElementById('userDropdown');
            if (dropdown && dropdown.classList.contains('open') && !event.target.closest('.user-menu')) {
                dropdown.classList.remove('open');
            }
        });
    </script>

    @if (session('success'))
        <script>document.addEventListener('DOMContentLoaded', function () { showToast('success', @json(session('success'))); });</script>
    @endif
    @if (session('error'))
        <script>document.addEventListener('DOMContentLoaded', function () { showToast('error', @json(session('error'))); });</script>
    @endif

</body>
</html>