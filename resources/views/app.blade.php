<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $appName     = config('app.name', 'পাহাড়ি স্বাদ');
            $appUrl      = rtrim(config('app.url', 'https://pahadiswad.com'), '/');
            $pageTitle   = $appName . ' — খাঁটি পাহাড়ি স্বাদ, আপনার দরজায়';
            $description = 'পাহাড়ি স্বাদ — পাহাড় থেকে সংগ্রহ করা তাজা ও জৈব পণ্য সরাসরি আপনার দরজায়। Authentic hill-fresh organic products, natural spices, honey, and artisan foods delivered across Bangladesh.';
            $keywords    = 'পাহাড়ি স্বাদ, hill taste, organic food, পাহাড়ি পণ্য, মধু, মশলা, জৈব, hill products Bangladesh, pahadi swad, natural honey, organic spices, artisan food delivery';
            $ogImage     = $appUrl . '/og-image.svg';
            $canonical   = $appUrl . request()->getPathInfo();

            $jsonLd = [
                '@context' => 'https://schema.org',
                '@graph'   => [
                    [
                        '@type'        => 'OnlineStore',
                        '@id'          => $appUrl . '/#organization',
                        'name'         => $appName,
                        'alternateName'=> 'Pahadi Swad',
                        'url'          => $appUrl,
                        'logo'         => [
                            '@type'  => 'ImageObject',
                            'url'    => $appUrl . '/favicon.svg',
                            'width'  => 64,
                            'height' => 64,
                        ],
                        'image'        => $ogImage,
                        'description'  => $description,
                        'slogan'       => 'খাঁটি পাহাড়ি স্বাদ, আপনার দরজায়',
                        'inLanguage'   => ['bn-BD', 'en'],
                        'foundingDate' => '2024',
                        'areaServed'   => ['@type' => 'Country', 'name' => 'Bangladesh'],
                        'hasOfferCatalog' => [
                            '@type'          => 'OfferCatalog',
                            'name'           => 'পাহাড়ি পণ্য সমূহ',
                            'itemListElement'=> [
                                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Product', 'name' => 'Organic Hill Honey']],
                                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Product', 'name' => 'Natural Spices']],
                                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Product', 'name' => 'Artisan Hill Foods']],
                            ],
                        ],
                        'sameAs' => [],
                    ],
                    [
                        '@type'      => 'WebSite',
                        '@id'        => $appUrl . '/#website',
                        'url'        => $appUrl,
                        'name'       => $appName,
                        'description'=> $description,
                        'publisher'  => ['@id' => $appUrl . '/#organization'],
                        'inLanguage' => 'bn-BD',
                        'potentialAction' => [
                            '@type'       => 'SearchAction',
                            'target'      => [
                                '@type'       => 'EntryPoint',
                                'urlTemplate' => $appUrl . '/products?search={search_term_string}',
                            ],
                            'query-input' => 'required name=search_term_string',
                        ],
                    ],
                ],
            ];
        @endphp

        <!-- ═══════ Primary SEO ═══════ -->
        <title inertia>{{ $pageTitle }}</title>
        <meta name="description"        content="{{ $description }}">
        <meta name="keywords"           content="{{ $keywords }}">
        <meta name="author"             content="পাহাড়ি স্বাদ">
        <meta name="robots"             content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
        <meta name="revisit-after"      content="7 days">
        <meta name="language"           content="Bengali, bn-BD">
        <meta http-equiv="content-language" content="bn-BD">
        <meta name="rating"             content="general">
        <link rel="canonical"           href="{{ $canonical }}">

        <!-- ═══════ Open Graph (Facebook / WhatsApp / LinkedIn / Telegram) ═══════ -->
        <meta property="og:type"              content="website">
        <meta property="og:url"               content="{{ $canonical }}">
        <meta property="og:site_name"         content="{{ $appName }}">
        <meta property="og:title"             content="{{ $pageTitle }}">
        <meta property="og:description"       content="{{ $description }}">
        <meta property="og:image"             content="{{ $ogImage }}">
        <meta property="og:image:type"        content="image/svg+xml">
        <meta property="og:image:width"       content="1200">
        <meta property="og:image:height"      content="630">
        <meta property="og:image:alt"         content="পাহাড়ি স্বাদ — Authentic Hill Foods E-Commerce">
        <meta property="og:locale"            content="bn_BD">
        <meta property="og:locale:alternate"  content="en_US">

        <!-- ═══════ Twitter / X Card ═══════ -->
        <meta name="twitter:card"        content="summary_large_image">
        <meta name="twitter:title"       content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image"       content="{{ $ogImage }}">
        <meta name="twitter:image:alt"   content="পাহাড়ি স্বাদ — Authentic Hill Foods">

        <!-- ═══════ Favicon & App Icons ═══════ -->
        <link rel="icon"             type="image/svg+xml" href="/favicon.svg">
        <link rel="alternate icon"   href="/favicon.ico">
        <link rel="apple-touch-icon" href="/favicon.svg">
        <meta name="theme-color"     content="#0d5c2e">
        <meta name="msapplication-TileColor" content="#0d5c2e">

        <!-- ═══════ JSON-LD Structured Data ═══════ -->
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

        <!-- ═══════ Fonts ═══════ -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

        <!-- ═══════ Scripts ═══════ -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
