<!-- ===== SYARAT & KETENTUAN ===== -->
<section id="syarat-ketentuan" class="mb-12 scroll-mt-20">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-white">{{ $title }}</h2>
            <p class="text-gray-400 mt-2">Ketentuan penggunaan layanan {{ \App\Support\SiteContent::setting('general.site_name', 'Beli Dolar') }}.</p>
        </div>

        <x-terms-content />
    </div>
</section>
