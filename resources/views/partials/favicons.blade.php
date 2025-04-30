{{-- Basic Favicon avec tailles spécifiées --}}
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48 32x32 16x16">

{{-- Apple Touch Icon --}}
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

{{-- Favicons pour différentes tailles - plus haute priorité que favicon.ico --}}
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
{{-- Ajout d'une version plus grande pour meilleure qualité --}}
<link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">

{{-- Site Manifest --}}
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

{{-- Pour Microsoft Edge et IE --}}
<meta name="msapplication-TileColor" content="#000000">
<meta name="msapplication-TileImage" content="{{ asset('mstile-144x144.png') }}">

{{-- Theme Color --}}
<meta name="theme-color" content="#000000">