<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @php
        $siteName = \App\Support\SiteContent::setting('general.site_name', 'Beli Dolar');
        $routeName = request()->route()?->getName();
        $pageTitles = [
            'beranda' => 'Jasa Top Up & Pembayaran Digital Terpercaya',
            'legal.about' => 'Tentang Kami',
            'legal.how-to-order' => 'Cara Order',
            'legal.terms' => 'Syarat & Ketentuan',
            'legal.privacy' => 'Kebijakan Privasi',
            'blog.index' => 'Blog & Artikel',
            'blog.show' => 'Artikel',
        ];
        $seoTitle = $seoTitle ?? null;
        $seoDescription = $seoDescription ?? null;
        $seoImage = $seoImage ?? null;
        $seoType = $seoType ?? 'website';

        $pageTitle = $seoTitle ?? $pageTitles[$routeName] ?? $siteName;
        $fullTitle = $routeName === 'beranda' && $seoTitle === null
            ? $siteName.' - '.$pageTitle
            : $pageTitle.' - '.$siteName;
        $metaDescription = $seoDescription
            ?? \App\Support\SiteContent::get('hero', 'description')
            ?: 'Beli Dolar melayani top up saldo PayPal, convert USD ke IDR, dan pembayaran invoice PayPal dengan proses cepat, mudah, dan aman.';
        $canonical = url()->current();
        $ogImage = $seoImage ?? \App\Support\SiteContent::setting('seo.og_image');
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => url('/'),
            'description' => $metaDescription,
        ];
    @endphp

    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}" />
    <meta name="robots" content="index, follow" />
    <meta name="theme-color" content="#0f1724" />
    <link rel="canonical" href="{{ $canonical }}" />
    <link rel="icon" href="{{ \App\Support\Branding::faviconUrl() }}" />
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}" sizes="any" />

    <!-- Open Graph -->
    <meta property="og:type" content="{{ $seoType }}" />
    <meta property="og:site_name" content="{{ $siteName }}" />
    <meta property="og:title" content="{{ $fullTitle }}" />
    <meta property="og:description" content="{{ $metaDescription }}" />
    <meta property="og:url" content="{{ $canonical }}" />
    <meta property="og:locale" content="id_ID" />
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}" />
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $ogImage ? 'summary_large_image' : 'summary' }}" />
    <meta name="twitter:title" content="{{ $fullTitle }}" />
    <meta name="twitter:description" content="{{ $metaDescription }}" />
    @if ($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}" />
    @endif

    <script type="application/ld+json">@json($structuredData)</script>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --gradient-main: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            --gradient-secondary: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            --gradient-hero: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4f46e5 60%, #06b6d4 100%);
        }
        
        body {
            background: #0f1724;
            color: #e5e7eb;
        }
        
        .bg-white { background: #1e293b !important; }
        .bg-gray-50 { background: #0f1724 !important; }
        .bg-gray-100 { background: #1e293b !important; }
        .text-gray-600 { color: #94a3b8 !important; }
        .text-gray-700 { color: #cbd5e1 !important; }
        .text-gray-500 { color: #94a3b8 !important; }
        .text-gray-400 { color: #94a3b8 !important; }
        .border-gray-200 { border-color: #334155 !important; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.3); }
        .bg-white\/10 { background: rgba(255,255,255,0.05) !important; }
        .bg-white\/20 { background: rgba(255,255,255,0.08) !important; }
        
        .text-white { color: #ffffff !important; }
        .text-white\/70 { color: rgba(255,255,255,0.7) !important; }
        .text-white\/80 { color: rgba(255,255,255,0.8) !important; }
        .text-white\/90 { color: rgba(255,255,255,0.9) !important; }
        
        .gradient-text {
            background: var(--gradient-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .card-shadow {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        /* Rich text (artikel blog, syarat & ketentuan) */
        .article-content { color: #cbd5e1; font-size: 15px; line-height: 1.8; }
        .article-content > * + * { margin-top: 1rem; }
        .article-content h2 { color: #ffffff; font-size: 1.5rem; font-weight: 800; margin-top: 2rem; line-height: 1.3; }
        .article-content h3 { color: #ffffff; font-size: 1.25rem; font-weight: 700; margin-top: 1.75rem; line-height: 1.35; }
        .article-content h4 { color: #ffffff; font-size: 1.1rem; font-weight: 700; margin-top: 1.5rem; }
        .article-content p { margin-top: 1rem; }
        .article-content strong, .article-content b { color: #ffffff; font-weight: 700; }
        .article-content a { color: #22d3ee; text-decoration: underline; text-underline-offset: 3px; }
        .article-content ul { list-style: disc; padding-left: 1.5rem; }
        .article-content ol { list-style: decimal; padding-left: 1.5rem; }
        .article-content li { margin-top: 0.35rem; }
        .article-content blockquote { border-left: 4px solid #4f46e5; background: rgba(79,70,229,0.08); padding: 0.85rem 1.25rem; border-radius: 0 12px 12px 0; color: #e2e8f0; }
        .article-content img { border-radius: 14px; max-width: 100%; height: auto; }
        .article-content pre { background: #0f1724; border: 1px solid #334155; border-radius: 12px; padding: 1rem; overflow-x: auto; }
        .article-content code { background: #0f1724; border: 1px solid #334155; border-radius: 6px; padding: 0.1rem 0.35rem; font-size: 0.9em; }
        .article-content pre code { border: 0; padding: 0; }
        .article-content hr { border-color: #334155; margin: 2rem 0; }
        .article-content table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .article-content th, .article-content td { border: 1px solid #334155; padding: 0.5rem 0.75rem; text-align: left; }
        .article-content th { background: #0f1724; color: #ffffff; }
        
        .btn-primary {
            background: var(--gradient-main);
            color: white;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(79, 70, 229, 0.4);
        }
        
        .btn-secondary {
            background: var(--gradient-secondary);
            color: white;
            transition: all 0.3s;
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(245, 158, 11, 0.4);
        }
        
        .tab-active {
            background: var(--gradient-main);
            color: white;
        }
        .tab-inactive {
            background: #334155;
            color: #94a3b8;
        }
        
        /* Toast Notification */
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
        .toast-item:hover { box-shadow: 0 12px 40px rgba(0,0,0,0.45); }
        .toast-item.border-success { border-left-color: #22c55e; }
        .toast-item.border-warning { border-left-color: #f59e0b; }
        .toast-item.border-error   { border-left-color: #ef4444; }
        .toast-item.border-info    { border-left-color: #4f46e5; }
        .toast-item.toast-out {
            opacity: 0;
            transform: translateX(-100%);
        }

        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* Floating WhatsApp */
        .floating-wa {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9998;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #25d366;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        .floating-wa:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 30px rgba(37, 211, 102, 0.5);
        }
        
        /* Modal WA */
        .modal-wa {
            display: none;
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 380px;
            max-width: 90vw;
            background: #1e293b;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            z-index: 9997;
            overflow: hidden;
        }
        .modal-wa.active {
            display: block;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Counter Animation */
        .counter-card {
            transition: all 0.3s;
        }
        .counter-card:hover {
            transform: translateY(-4px);
        }
        
        /* Hero Banner Styles */
        .hero-banner {
            background: var(--gradient-hero);
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: rgba(6, 182, 212, 0.1);
            border-radius: 50%;
            filter: blur(60px);
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -5%;
            width: 500px;
            height: 500px;
            background: rgba(79, 70, 229, 0.1);
            border-radius: 50%;
            filter: blur(60px);
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        /* Stats Banner */
        .stats-banner {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4f46e5 100%);
            position: relative;
            overflow: hidden;
        }
        .stats-banner::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(6, 182, 212, 0.08);
            border-radius: 50%;
        }
        .stats-banner::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(245, 158, 11, 0.05);
            border-radius: 50%;
        }
        
        /* Hero text fixes */
        .hero-banner .text-white { color: #ffffff !important; }
        .hero-banner .text-white\/70 { color: rgba(255,255,255,0.7) !important; }
        .hero-banner .text-white\/80 { color: rgba(255,255,255,0.8) !important; }
        .hero-banner .text-white\/90 { color: rgba(255,255,255,0.9) !important; }
        .hero-banner .text-yellow-300 { color: #fcd34d !important; }
        
        /* Stats banner text */
        .stats-banner .text-white { color: #ffffff !important; }
        .stats-banner .text-white\/70 { color: rgba(255,255,255,0.7) !important; }
        .stats-banner .text-yellow-300 { color: #fcd34d !important; }
        
        /* Navbar Menu Styles */
        .nav-link {
            position: relative;
            color: #94a3b8;
            font-weight: 500;
            padding: 8px 4px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0%;
            height: 2px;
            background: var(--gradient-main);
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        .nav-link:hover {
            color: #ffffff;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .nav-link.active {
            color: #ffffff;
        }
        .nav-link.active::after {
            width: 100%;
        }
        
        /* Mobile Menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 64px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 36, 0.95);
            backdrop-filter: blur(10px);
            z-index: 40;
            padding: 20px;
            overflow-y: auto;
        }
        .mobile-menu.active {
            display: block;
        }
        .mobile-menu a {
            display: block;
            padding: 12px 16px;
            color: #94a3b8;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s;
            font-size: 16px;
            text-decoration: none;
        }
        .mobile-menu a:hover {
            background: rgba(79, 70, 229, 0.2);
            color: #ffffff;
        }
        .mobile-menu a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        .mobile-menu a.active {
            background: rgba(79, 70, 229, 0.15);
            color: #ffffff;
        }
        
        .hamburger-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s;
            display: none;
        }
        .hamburger-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        @media (max-width: 1023px) {
            .hamburger-btn {
                display: block;
            }
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .modal-wa {
                right: 10px;
                width: calc(100% - 20px);
                bottom: 85px;
            }
            #toast-container {
                left: 10px;
                right: 10px;
                max-width: none;
                width: auto;
                bottom: 100px;
            }
        }
    </style>
</head>
<body>

    <livewire:landing-page.components.navbar>

    <main>
        {{ $slot }}

        @if (request()->routeIs('beranda'))
            <livewire:landing-page.components.order-form />

            <livewire:landing-page.components.transaction-tracking />

            <livewire:landing-page.components.terms />

            <livewire:landing-page.components.testimonials />

            <livewire:landing-page.components.testimonial-form />
        @endif
    </main>

    <livewire:landing-page.components.footer>

    <!-- ===== TOAST CONTAINER ===== -->
    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

    <!-- ===== FLOATING WHATSAPP BUTTON ===== -->
    <button class="floating-wa" onclick="toggleWAModal()">
        <i class="fab fa-whatsapp"></i>
    </button>

    <livewire:landing-page.components.whatsapp-modal />

    <!-- ===== OVERLAY ===== -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="toggleWAModal()"></div>

    <script>
        // ===== ACTIVE NAV LINK =====
        function updateActiveNav() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            const mobileLinks = document.querySelectorAll('.mobile-menu a[data-section]');
            
            let currentSection = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                if (window.scrollY >= sectionTop) {
                    currentSection = section.getAttribute('id');
                }
            });

            // Update desktop nav
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.dataset.section === currentSection) {
                    link.classList.add('active');
                }
            });

            // Update mobile nav
            mobileLinks.forEach(link => {
                link.classList.remove('active');
                if (link.dataset.section === currentSection) {
                    link.classList.add('active');
                }
            });
        }

        // Throttle scroll event
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            if (scrollTimeout) return;
            scrollTimeout = setTimeout(() => {
                updateActiveNav();
                scrollTimeout = null;
            }, 50);
        });

        // Initial update
        setTimeout(updateActiveNav, 100);

        // ===== MOBILE MENU =====
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('active');
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
        }

        function closeMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(updateActiveNav, 100);
        }

        // Close mobile menu on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeMobileMenu();
            }
        });

        // Close mobile menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                setTimeout(updateActiveNav, 100);
            });
        });

        // ===== COUNTER ANIMATION =====
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 60;
            const isLarge = target >= 1000;
            const locale = element.dataset.format || undefined;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                if (isLarge) {
                    element.textContent = Math.floor(current).toLocaleString(locale);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 20);
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseFloat(counter.dataset.target);
                    animateCounter(counter, target);
                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.3 });

        document.querySelectorAll('.counter').forEach(counter => {
            observer.observe(counter);
        });

        // ===== WHATSAPP MODAL =====
        function toggleWAModal() {
            const modal = document.getElementById('waModal');
            const overlay = document.getElementById('overlay');
            if (!modal) return;

            const willOpen = !modal.classList.contains('active');
            modal.classList.toggle('active', willOpen);
            if (overlay) overlay.classList.toggle('hidden', !willOpen);

            if (willOpen) {
                const input = document.getElementById('waMessageInput');
                if (input) setTimeout(() => input.focus(), 50);
            }
        }

        function setWAMessage(button) {
            const input = document.getElementById('waMessageInput');
            if (input && button) {
                input.value = button.dataset.message || button.textContent.trim();
                input.focus();
            }
        }

        function sendWAModal() {
            const modal = document.getElementById('waModal');
            const input = document.getElementById('waMessageInput');
            if (!modal) return;

            const number = (modal.dataset.waNumber || '').replace(/\D+/g, '');
            let text = input && input.value ? input.value.trim() : '';

            if (!text) {
                text = 'Halo, saya ingin bertanya tentang layanan Beli Dolar.';
            }

            if (!number) {
                if (typeof showToast === 'function') {
                    showToast('warning', 'Nomor WhatsApp admin belum diatur.');
                }
                return;
            }

            window.open('https://wa.me/' + number + '?text=' + encodeURIComponent(text), '_blank');

            if (input) input.value = '';
            toggleWAModal();
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('waModal');
                if (modal && modal.classList.contains('active')) {
                    toggleWAModal();
                }
            }
        });

        // setTimeout(() => {
        //     showToast('success', '✅ Selamat datang di Beli Dolar! Silahkan order melalui form.');
        // }, 1000);
    </script>

</body>
</html>
