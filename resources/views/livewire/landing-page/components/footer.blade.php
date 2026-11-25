<!-- ===== FOOTER ===== -->
<div>
    <footer class="bg-[#1e293b] border-t border-[#334155]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-8">
                <div>
                    <div class="flex items-center mb-3">
                        <x-brand-mark class="h-12 w-48 rounded-xl" />
                    </div>
                    <p class="text-sm text-gray-400">@content('footer', 'description')</p>
                    @php
                        $socialLinks = [
                            ['key' => 'social_facebook_url', 'icon' => 'fab fa-facebook', 'label' => 'Facebook'],
                            ['key' => 'social_instagram_url', 'icon' => 'fab fa-instagram', 'label' => 'Instagram'],
                            ['key' => 'social_twitter_url', 'icon' => 'fab fa-twitter', 'label' => 'X / Twitter'],
                            ['key' => 'social_youtube_url', 'icon' => 'fab fa-youtube', 'label' => 'YouTube'],
                        ];
                    @endphp
                    <div class="flex gap-3 mt-3">
                        @foreach ($socialLinks as $social)
                            @php
                                $socialUrl = trim((string) \App\Support\SiteContent::get('footer', $social['key'], ''));
                                $socialScheme = strtolower((string) parse_url($socialUrl, PHP_URL_SCHEME));
                                $isValidSocialUrl = filter_var($socialUrl, FILTER_VALIDATE_URL)
                                    && in_array($socialScheme, ['http', 'https'], true);
                            @endphp
                            @if ($isValidSocialUrl)
                                <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer"
                                    aria-label="{{ $social['label'] }}"
                                    class="text-gray-400 hover:text-[#4f46e5] transition">
                                    <i class="{{ $social['icon'] }}"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div>
                    <h5 class="font-bold text-white mb-3">Layanan</h5>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('beranda') }}?service=topup#form-order" class="hover:text-[#4f46e5] transition">@content('footer', 'layanan_link_1')</a></li>
                        <li><a href="{{ route('beranda') }}?service=convert_usd#form-order" class="hover:text-[#4f46e5] transition">@content('footer', 'layanan_link_2')</a></li>
                        <li><a href="{{ route('beranda') }}?service=invoice#form-order" class="hover:text-[#4f46e5] transition">@content('footer', 'layanan_link_3')</a></li>
                        <li><a href="{{ route('beranda') }}?service=pay_online#form-order" class="hover:text-[#4f46e5] transition">{{ \App\Support\SiteContent::get('footer', 'layanan_link_4', 'Jasa Bayar Online') }}</a></li>
                        <li><a href="{{ route('beranda') }}?service=wd_akun_180#form-order" class="hover:text-[#4f46e5] transition">{{ \App\Support\SiteContent::get('footer', 'layanan_link_5', 'WD Akun Permanen 180 Hari') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-bold text-white mb-3">Informasi</h5>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('legal.about') }}" class="hover:text-[#4f46e5] transition">@content('footer', 'informasi_link_1')</a></li>
                        <li><a href="{{ route('legal.terms') }}" class="hover:text-[#4f46e5] transition">@content('footer', 'informasi_link_2')</a></li>
                        <li><a href="{{ route('legal.privacy') }}" class="hover:text-[#4f46e5] transition">@content('footer', 'informasi_link_3')</a></li>
                        <li><a href="{{ route('legal.how-to-order') }}" class="hover:text-[#4f46e5] transition">@content('footer', 'informasi_link_4')</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-bold text-white mb-3">Kontak</h5>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><i class="fab fa-whatsapp text-green-500 mr-2"></i> @content('footer', 'kontak_phone')</li>
                        <li><i class="far fa-envelope mr-2"></i> @content('footer', 'kontak_email')</li>
                        <li><i class="fas fa-clock mr-2"></i> @content('footer', 'kontak_hours')</li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-bold text-white mb-3">Jam Operasional</h5>
                    <div class="space-y-1 text-sm text-gray-400">
                        <p><span class="font-semibold text-white">@content('footer', 'jam_operasional')</span></p>
                        <p>@content('footer', 'jam_value')</p>
                        <p class="text-xs text-green-400 mt-2"><i class="fas fa-circle text-[8px] mr-1"></i> @content('footer', 'jam_status')</p>
                    </div>
                </div>
            </div>

            @php
                $footerDisclaimer = \App\Support\SiteContent::get(
                    'footer',
                    'disclaimer',
                    \App\Support\ContentDefaults::value('footer', 'disclaimer')
                );
            @endphp
            @if (filled($footerDisclaimer))
                <p class="text-xs text-gray-500 mt-8 max-w-4xl mx-auto leading-relaxed text-center">{{ $footerDisclaimer }}</p>
            @endif

            <div class="border-t border-[#334155] mt-6 pt-6 text-center text-sm text-gray-400">
                <p>@content('footer', 'copyright_lhs') <i class="fas fa-heart text-[#4f46e5]"></i> @content('footer', 'copyright_rhs')</p>
            </div>
        </div>
    </footer>
</div>
