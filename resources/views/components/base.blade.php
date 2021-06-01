<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>DFI Signal - Masternode Signals on Telegram</title>
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

    <link href="{{ asset('assets/main.css') }}" rel="stylesheet"/>
</head>

<body>
<main>
    {{ $slot }}
    @include('partials.footer')
</main>
</body>
</html>
