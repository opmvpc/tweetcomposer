<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Tweet Composer') }}</title>

    <!-- Social card -->
    <meta property="og:title" content="Tweet Composer" />
    <meta name="description"
        content="Looking to up your Twitter game? Look no further than Tweet Composer! This free and open source tool allows you to craft the perfect tweet and schedule it to be sent at the optimal time.">
    <meta property="og:description"
        content="Looking to up your Twitter game? Look no further than Tweet Composer! This free and open source tool allows you to craft the perfect tweet and schedule it to be sent at the optimal time." />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="en" />
    <meta property="og:site_name" content="Tweet Composer" />
    <meta property="og:url" content="https://tweet.tsix.be/" />
    <meta property="og:author" content="opmvpc" />
    <meta property="og:section" content="Entertainment" />
    <meta property="og:image" content="{{ Vite::asset('resources/img/logo.jpg') }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="theme-color" content="#A5B4FC" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
