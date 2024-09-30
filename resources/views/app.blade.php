<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'NOFLAY - Plateforme de gestion immobilière') }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="NOFLAY est une plateforme complète et intuitive pour la gestion des entreprises immobilières. Simplifiez vos opérations quotidiennes et améliorez votre efficacité.">
    <meta name="keywords"
        content="gestion immobilière, NOFLAY, propriétés, contrats, facturation, mandats de gérance, rapports d'activité">
    <meta name="author" content="Ndiaga Ndiaye">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="NOFLAY - Gestion immobilière simplifiée">
    <meta property="og:description"
        content="Plateforme complète pour la gestion des entreprises immobilières. Optimisez vos opérations et améliorez votre efficacité.">
    <meta property="og:image" content="{{ asset('img/noflay-og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta property="twitter:title" content="NOFLAY - Gestion immobilière simplifiée">
    <meta property="twitter:description"
        content="Plateforme complète pour la gestion des entreprises immobilières. Optimisez vos opérations et améliorez votre efficacité.">
    <meta property="twitter:image" content="{{ asset('img/noflay-twitter-image.jpg') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/noflay.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('img/noflay.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-T9ZESN6FS2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-T9ZESN6FS2');
    </script>
</head>

<body class="font-sans antialiased">
    @inertia

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "NOFLAY",
      "applicationCategory": "BusinessApplication",
      "operatingSystem": "Web",
      "description": "Plateforme complète et intuitive pour la gestion des entreprises immobilières",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "XOF"
      },
      "author": {
        "@type": "Person",
        "name": "Ndiaga Ndiaye",
        "url": "https://ndiagandiaye.com"
      }
    }
    </script>
</body>

</html>
