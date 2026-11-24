@props(['class' => 'w-10 h-10 rounded-xl'])

<span {{ $attributes->class(['inline-flex items-center justify-center overflow-hidden shadow-lg shadow-indigo-950/30', $class]) }}>
    <img src="{{ \App\Support\Branding::logoUrl() }}" alt="{{ \App\Support\SiteContent::setting('general.site_name', 'Beli Dolar') }}" class="w-full h-full object-contain" />
</span>
