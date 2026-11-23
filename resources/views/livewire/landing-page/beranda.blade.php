<!-- ===== HERO BANNER 1 ===== -->
<div>
    <section id="beranda" class="hero-banner text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 md:py-20 relative">
            <div class="hero-content grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-semibold flex items-center gap-2 text-white">
                            <i class="fas fa-star text-yellow-300"></i> @content('hero', 'badge_rating')
                        </span>
                        <span class="bg-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-semibold text-white">
                            <i class="fas fa-users mr-2"></i> @content('hero', 'badge_customers')
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-white">
                        @content('hero', 'title')
                        <span class="text-yellow-300 block mt-1">@content('hero', 'title_highlight')</span>
                    </h1>

                    <p class="text-base md:text-lg mt-4 text-white/90 max-w-xl leading-relaxed">
                        @content('hero', 'description')
                    </p>

                    <div class="flex flex-wrap gap-4 mt-6">
                        <a href="#form-order" class="bg-white text-[#4f46e5] px-8 py-3 rounded-xl font-bold hover:shadow-xl transition-all hover:scale-105 flex items-center gap-2">
                            <i class="fas fa-paper-plane"></i> @content('hero', 'cta_primary')
                        </a>
                        <a href="#layanan" class="bg-white/20 backdrop-blur-sm px-8 py-3 rounded-xl font-bold hover:bg-white/30 transition flex items-center gap-2 text-white">
                            <i class="fas fa-play-circle"></i> @content('hero', 'cta_secondary')
                        </a>
                    </div>
                </div>

                <!-- Right Content - Stats/Info -->
                <div class="lg:pl-8">
                    <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-white/20">
                        <div class="text-center mb-6">
                            <p class="text-sm text-white/70">@content('hero', 'card_label')</p>
                            <h2 class="text-3xl font-extrabold text-yellow-300">@content('hero', 'card_title')</h2>
                            <p class="text-white/80 text-sm">@content('hero', 'card_subtitle')</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/10 rounded-2xl p-4 text-center">
                                <p class="text-3xl font-bold text-yellow-300">@content('hero', 'card_stat_1_value')</p>
                                <p class="text-xs text-white/70">@content('hero', 'card_stat_1_label')</p>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-4 text-center">
                                <p class="text-3xl font-bold text-yellow-300">@content('hero', 'card_stat_2_value')</p>
                                <p class="text-xs text-white/70">@content('hero', 'card_stat_2_label')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== BANNER 2 - Stats Banner ===== -->
    <section class="stats-banner text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-16 relative z-10">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-extrabold text-white">
                    @content('stats', 'title') <span class="text-yellow-300">@content('stats', 'title_highlight')</span>
                </h2>
                <p class="text-white/70 text-sm mt-2">@content('stats', 'subtitle')</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/10 hover:bg-white/20 transition-all">
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-yellow-300/20 flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl text-yellow-300"></i>
                        </div>
                        <div>
                            <p class="text-3xl md:text-4xl font-extrabold text-white counter" data-target="@content('stats', 'transaksi_sukses', '135000')">0</p>
                            <p class="text-sm text-white/70">@content('stats', 'transaksi_sukses_label')</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/10 hover:bg-white/20 transition-all">
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-cyan-400/20 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-2xl text-cyan-400"></i>
                        </div>
                        <div>
                            <p class="text-3xl md:text-4xl font-extrabold text-white counter" data-target="@content('stats', 'tahun_pengalaman', '7')">0</p>
                            <p class="text-sm text-white/70">@content('stats', 'tahun_pengalaman_label')</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/10 hover:bg-white/20 transition-all">
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-purple-400/20 flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-2xl text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-3xl md:text-4xl font-extrabold text-white counter" data-target="@content('stats', 'saldo_terkirim', '400000')">0</p>
                            <p class="text-sm text-white/70">@content('stats', 'saldo_terkirim_label')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-12">

        <!-- ===== LAYANAN ===== -->
        <section id="layanan" class="mb-12 scroll-mt-20">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-white">
                    @content('layanan', 'title') <span class="gradient-text">@content('layanan', 'title_highlight')</span>
                </h2>
                <p class="text-gray-400 mt-2">@content('layanan', 'subtitle')</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 text-center hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="w-16 h-16 rounded-2xl bg-[#4f46e5]/10 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-arrow-up text-2xl text-[#4f46e5]"></i>
                    </div>
                    <h4 class="font-bold text-white">@content('layanan', 'item_1_title')</h4>
                    <p class="text-xs text-gray-400 mt-1">@content('layanan', 'item_1_desc')</p>
                </div>
                <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 text-center hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="w-16 h-16 rounded-2xl bg-[#06b6d4]/10 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-exchange-alt text-2xl text-[#06b6d4]"></i>
                    </div>
                    <h4 class="font-bold text-white">@content('layanan', 'item_2_title')</h4>
                    <p class="text-xs text-gray-400 mt-1">@content('layanan', 'item_2_desc')</p>
                </div>
                <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 text-center hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="w-16 h-16 rounded-2xl bg-[#f59e0b]/10 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-file-invoice text-2xl text-[#f59e0b]"></i>
                    </div>
                    <h4 class="font-bold text-white">@content('layanan', 'item_3_title')</h4>
                    <p class="text-xs text-gray-400 mt-1">@content('layanan', 'item_3_desc')</p>
                </div>
                <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 text-center hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-cart-shopping text-2xl text-emerald-400"></i>
                    </div>
                    <h4 class="font-bold text-white">{{ \App\Support\SiteContent::get('layanan', 'item_4_title', 'Jasa Bayar Online') }}</h4>
                    <p class="text-xs text-gray-400 mt-1">{{ \App\Support\SiteContent::get('layanan', 'item_4_desc', 'Bayar belanjaan di situs luar negeri') }}</p>
                </div>
            </div>
        </section>

        <!-- ===== CARA ORDER ===== -->
        <section id="cara-order" class="mb-12 scroll-mt-20">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-white">
                    @content('cara_order', 'title') <span class="gradient-text">@content('cara_order', 'title_highlight')</span>
                </h2>
                <p class="text-gray-400 mt-2">@content('cara_order', 'subtitle')</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 text-center relative">
                    <div class="absolute -top-3 -left-3 w-10 h-10 rounded-full bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] flex items-center justify-center text-white font-bold text-sm">1</div>
                    <div class="w-16 h-16 rounded-2xl bg-[#4f46e5]/10 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-edit text-2xl text-[#4f46e5]"></i>
                    </div>
                    <h4 class="font-bold text-white">@content('cara_order', 'step_1_title')</h4>
                    <p class="text-xs text-gray-400 mt-1">@content('cara_order', 'step_1_desc')</p>
                </div>
                <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 text-center relative">
                    <div class="absolute -top-3 -left-3 w-10 h-10 rounded-full bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] flex items-center justify-center text-white font-bold text-sm">2</div>
                    <div class="w-16 h-16 rounded-2xl bg-[#06b6d4]/10 flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-whatsapp text-2xl text-[#25d366]"></i>
                    </div>
                    <h4 class="font-bold text-white">@content('cara_order', 'step_2_title')</h4>
                    <p class="text-xs text-gray-400 mt-1">@content('cara_order', 'step_2_desc')</p>
                </div>
                <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 text-center relative">
                    <div class="absolute -top-3 -left-3 w-10 h-10 rounded-full bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] flex items-center justify-center text-white font-bold text-sm">3</div>
                    <div class="w-16 h-16 rounded-2xl bg-[#f59e0b]/10 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-check-circle text-2xl text-[#f59e0b]"></i>
                    </div>
                    <h4 class="font-bold text-white">@content('cara_order', 'step_3_title')</h4>
                    <p class="text-xs text-gray-400 mt-1">@content('cara_order', 'step_3_desc')</p>
                </div>
                <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 text-center relative">
                    <div class="absolute -top-3 -left-3 w-10 h-10 rounded-full bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] flex items-center justify-center text-white font-bold text-sm">4</div>
                    <div class="w-16 h-16 rounded-2xl bg-[#8b5cf6]/10 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-gem text-2xl text-[#8b5cf6]"></i>
                    </div>
                    <h4 class="font-bold text-white">@content('cara_order', 'step_4_title')</h4>
                    <p class="text-xs text-gray-400 mt-1">@content('cara_order', 'step_4_desc')</p>
                </div>
            </div>
        </section>

        <!-- ===== FAQ / PERTANYAAN UMUM ===== -->
        <livewire:landing-page.components.faq />
    </div>