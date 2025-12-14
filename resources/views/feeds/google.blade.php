<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
<channel>
@foreach($products as $product)
<item>
    <g:id>{{ $product->sku ?? $product->id }}</g:id>
    <g:title><![CDATA[{{ $lang === 'ar' ? $product->product_name_ar : $product->product_name_en }}]]></g:title>
    <g:description><![CDATA[{{ $lang === 'ar' ? ($product->short_description_ar ?: $product->long_description_ar) : ($product->short_description_en ?: $product->long_description_en) }}]]></g:description>
    @php
        // Localized product URL (with locale segment)
        $productUrl = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($lang, route('product.show', $product->id));
        $image = $product->image_url;
        $price = number_format($product->product_price ?? 0, 2);
        $availability = ($product->amount ?? 0) > 0 ? 'in stock' : 'out of stock';
    @endphp
    <g:link>{{ $productUrl }}</g:link>
    <g:image_link>{{ $image }}</g:image_link>
    <g:price>{{ $price }} EGP</g:price>
    <g:availability>{{ $availability }}</g:availability>
    <g:condition>new</g:condition>
    <g:brand>{{ $product->vendor?->name ?? 'Phyzioline' }}</g:brand>
    <g:mpn>{{ $product->sku }}</g:mpn>
</item>
@endforeach
</channel>
</rss>
