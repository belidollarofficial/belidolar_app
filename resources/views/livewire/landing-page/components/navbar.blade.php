@php($home = route('beranda'))
<!-- ===== NAVBAR ===== -->
<div>
    <nav class="bg-[#1e293b] border-b border-[#334155] shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ $home }}" class="flex items-center gap-3 hover:opacity-80 transition">
                    <x-brand-mark class="w-10 h-10 rounded-xl" />
                    <div>
                        <span class="text-2xl font-extrabold tracking-tight">
                            <span class="text-[#818cf8]">Beli</span>
                            <span class="text-[#22d3ee]">Dolar</span>
                        </span>
                        <p class="text-[10px] text-gray-400 -mt-0.5">@setting('general.site_name') Terpercaya</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-5 text-sm font-medium">
                    <a href="{{ $home }}#beranda" class="nav-link" data-section="beranda">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>
                    <a href="{{ $home }}#layanan" class="nav-link" data-section="layanan">
                        <i class="fas fa-cogs mr-1"></i> Layanan
                    </a>
                    <a href="{{ $home }}#cara-order" class="nav-link" data-section="cara-order">
                        <i class="fas fa-list-ol mr-1"></i> Cara Order
                    </a>
                    <a href="{{ $home }}#form-order" class="nav-link" data-section="form-order">
                        <i class="fas fa-clipboard-list mr-1"></i> Form Order
                    </a>
                    <a href="{{ $home }}#testimoni" class="nav-link" data-section="testimoni">
                        <i class="fas fa-star mr-1"></i> Testimoni
                    </a>
                    <a href="{{ $home }}#faq" class="nav-link" data-section="faq">
                        <i class="fas fa-circle-question mr-1"></i> FAQ
                    </a>
                    <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper mr-1"></i> Blog
                    </a>
                    <a href="{{ $home }}#riwayat" class="nav-link" data-section="riwayat">
                        <i class="fas fa-history mr-1"></i> Riwayat
                    </a>
                </div>

                <!-- Right Section -->
                <div class="flex items-center gap-3">
                    <!-- Hamburger Button -->
                    <button class="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== MOBILE MENU ===== -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="max-w-md mx-auto space-y-1">
            <a href="{{ $home }}#beranda" data-section="beranda" onclick="closeMobileMenu()">
                <i class="fas fa-home"></i> Beranda
            </a>
            <a href="{{ $home }}#layanan" data-section="layanan" onclick="closeMobileMenu()">
                <i class="fas fa-cogs"></i> Layanan
            </a>
            <a href="{{ $home }}#cara-order" data-section="cara-order" onclick="closeMobileMenu()">
                <i class="fas fa-list-ol"></i> Cara Order
            </a>
            <a href="{{ $home }}#form-order" data-section="form-order" onclick="closeMobileMenu()">
                <i class="fas fa-clipboard-list"></i> Form Order
            </a>
            <a href="{{ $home }}#testimoni" data-section="testimoni" onclick="closeMobileMenu()">
                <i class="fas fa-star"></i> Testimoni
            </a>
            <a href="{{ $home }}#faq" data-section="faq" onclick="closeMobileMenu()">
                <i class="fas fa-circle-question"></i> FAQ
            </a>
            <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}" onclick="closeMobileMenu()">
                <i class="fas fa-newspaper"></i> Blog
            </a>
            <a href="{{ $home }}#riwayat" data-section="riwayat" onclick="closeMobileMenu()">
                <i class="fas fa-history"></i> Riwayat
            </a>
        </div>
    </div>
</div>
