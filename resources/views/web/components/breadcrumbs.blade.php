@php
    $currentLocale = app()->getLocale();
    $isArabic = $currentLocale === 'ar';
@endphp

@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-transparent p-0 mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('home.' . $currentLocale) }}" class="text-decoration-none">
                <i class="las la-home"></i> {{ $isArabic ? 'الرئيسية' : 'Home' }}
            </a>
        </li>
        @foreach($breadcrumbs as $index => $breadcrumb)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] ?? '#' }}" class="text-decoration-none">{{ $breadcrumb['name'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>

@push('structured-data')
<script type="application/ld+json">
@json(\App\Services\SEO\SEOService::breadcrumbSchema(array_merge(
    [['name' => $isArabic ? 'الرئيسية' : 'Home', 'url' => route('home.' . $currentLocale)]],
    $breadcrumbs
)))
</script>
@endpush
@endif

