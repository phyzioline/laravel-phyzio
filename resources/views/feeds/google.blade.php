<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
<channel>
@foreach($products as $product)
<item>
    <g:id>{{ $product->sku ?? $product->id }}</g:id>
    <g:title><![CDATA[{{ $lang === 'ar' ? $product->product_name_ar : $product->product_name_en }}]]></g:title>
    <g:description><![CDATA[{{ strip_tags($lang === 'ar' ? ($product->short_description_ar ?: $product->long_description_ar) : ($product->short_description_en ?: $product->long_description_en)) }}]]></g:description>
    @php
        // Localized product URL
        $productUrl = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($lang, route('product.show.' . $lang, $product->id));
        
        // Image Logic: Ensure absolute URL and fallback
        $imgRaw = $product->image_url;
        if (!$imgRaw) {
             $image = asset('web/assets/images/logo.png');
        } elseif (filter_var($imgRaw, FILTER_VALIDATE_URL)) {
             $image = $imgRaw;
        } else {
             // If it's a relative path stored in DB (e.g. uploads/products/xyz.jpg)
             // Product accessor now returns asset(...) correctly.
             // If we are dealing with raw string here for some reason:
             if (strpos($imgRaw, 'http') === 0) {
                 $image = $imgRaw;
             } else {
                 // Assume it is relative to public root directly as per user feedback
                 $image = asset($imgRaw); 
             }
        }

        $price = number_format($product->product_price ?? 0, 2, '.', '');
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
