<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>DFI Signal - get informed by signals on Telegram</title>
    <meta name="title" content="DFI Signal - Masternode Signals on Telegram"/>
    <meta name="description" content="Setup your DFI Signal and get informed on new minted blocks or if your masternodes follows the wrong fork."/>

    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ config('app.url') }}/"/>
    <meta property="og:title" content="DFI Signal - Masternode Signals on Telegram"/>
    <meta property="og:description" content="Setup your DFI Signal and get informed on new minted blocks or if your masternodes follows the wrong fork."/>
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}"/>

    <meta property="twitter:card" content="summary_large_image"/>
    <meta property="twitter:url" content="{{ config('app.url') }}"/>
    <meta property="twitter:title" content="DFI Signal - Masternode Signals on Telegram"/>
    <meta property="twitter:description" content="Setup your DFI Signal and get informed on new minted blocks or if your masternodes follows the wrong fork."/>
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}"/>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link href="{{ asset('assets/main.css') }}" rel="stylesheet"/>
</head>

<body>
<main>
    {{ $slot }}
    @include('partials.footer')
</main>
</body>
</html>
