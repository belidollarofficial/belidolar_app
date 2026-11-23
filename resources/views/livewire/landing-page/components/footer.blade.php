<!-- ===== FOOTER ===== -->
<div>
    <footer class="bg-[#1e293b] border-t border-[#334155]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <x-brand-mark class="w-10 h-10 rounded-xl" />
                        <span class="text-xl font-extrabold">
                            <span class="text-[#818cf8]">Beli</span>
                            <span class="text-[#22d3ee]">Dolar</span>
                        </span>
                    </div>
                    <p class="text-sm text-gray-400">@content('footer', 'description')</p>
                    <div class="flex gap-3 mt-3">
                        <a href="#" class="text-gray-400 hover:text-[#4f46e5] transition"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-[#4f46e5] transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-[#4f46e5] transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-[#4f46e5] transition"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div>
                    <h5 class="font-bold text-white mb-3">Layanan</h5>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('beranda') }}?service=topup#form-order" class="hover:text-[#4f46e5] transition">@content('footer', 'layanan_link_1')</a></li>
                        <li><a href="{{ route('beranda') }}?service=convert_usd#form-order" class="hover:text-[#4f46e5] transition">@content('footer', 'layanan_link_2')</a></li>
                        <li><a href="{{ route('beranda') }}?service=invoice#form-order" class="hover:text-[#4f46e5] transition">@content('footer', 'layanan_link_3')</a></li>
                        <li><a href="{{ route('beranda') }}?service=pay_online#form-order" class="hover:text-[#4f46e5] transition">{{ \App\Support\SiteContent::get('footer', 'layanan_link_4', 'Jasa Bayar Online') }}</a></li>
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

            <div class="border-t border-[#334155] mt-8 pt-6 text-center text-sm text-gray-400">
                <p>@content('footer', 'copyright_lhs') <i class="fas fa-heart text-[#4f46e5]"></i> @content('footer', 'copyright_rhs')</p>
            </div>
        </div>
    </footer>
</div>