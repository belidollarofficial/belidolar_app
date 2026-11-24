<!-- ===== SYARAT & KETENTUAN ===== -->
<section id="syarat-ketentuan" class="mb-12 scroll-mt-20">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-white">{{ $title }}</h2>
            <p class="text-gray-400 mt-2">Ketentuan untuk layanan yang Anda pilih pada form order.</p>
        </div>

        <x-service-terms :service="$service" class="bg-[#1e293b] md:p-8" />
    </div>
</section>
