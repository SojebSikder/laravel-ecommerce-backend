<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ env('CLIENT_APP_URL') }}</loc>
        <priority>1</priority>
    </url>
    {{-- products --}}
    @foreach ($products as $product)
    <url>
        <loc>{{ env('CLIENT_APP_URL') }}/products/{{ $product->id }}/{{ $product->slug }}</loc>
        <lastmod>{{ $product->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach ($products as $product)
    <url>
        <loc>{{ env('CLIENT_APP_URL') }}/products?c={{ $product->category->id }}@if(isset($product->category->sublink))&amp;menu={{ $product->category->sublink->id }}@endif</loc>
        <priority>0.8</priority>
    </url>
    @endforeach
    {{-- pages --}}
    @foreach ($pages as $page)
    <url>
        <loc>{{ env('CLIENT_APP_URL') }}/{{ $page->slug }}</loc>
        <lastmod>{{ $page->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    {{-- footer_items --}}
    @foreach ($footer_items as $footer_list)
    <url>
        <loc>{{ env('CLIENT_APP_URL') }}{{ $footer_list->link }}</loc>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>