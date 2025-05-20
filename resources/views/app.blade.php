<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicons -->
    @include('partials.favicons')

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&family=Roboto:wght@400;500;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>

    <!-- Code de suivi Google Analytics 4 avec respect RGPD -->
    <script>
        // Vérifier les préférences de cookies avant d'activer Google Analytics
        const checkCookieConsent = function() {
            try {
                const consent = localStorage.getItem('nextconfig_cookie_consent');
                if (consent) {
                    const preferences = JSON.parse(consent);
                    window['ga-disable-G-5GLJQE505D'] = !preferences.analytics;
                } else {
                    // Par défaut, désactiver Google Analytics jusqu'à consentement
                    window['ga-disable-G-5GLJQE505D'] = true;
                }
            } catch (e) {
                window['ga-disable-G-5GLJQE505D'] = true;
            }
        };

        checkCookieConsent();

        // Configuration Google Analytics
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-5GLJQE505D', {
            'cookie_flags': 'SameSite=None;Secure'
        });
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5GLJQE505D"></script>
</head>

<body class="font-roboto bg-deep-black text-white">
    @inertia
</body>

</html>