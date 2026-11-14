@props(['title'])
<!doctype html>
@php($locale = str_replace('_', '-', app()->getLocale()))
<html lang="{{ $locale }}" dir="{{ in_array(strtok($locale, '-'), ['ar', 'fa', 'he', 'ur', 'ps', 'yi'], true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · Temp Mail</title>
    <script src="{{ asset('appearance-init.js') }}"></script>
    @if (is_file(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-zinc-100 text-zinc-950 dark:bg-zinc-900 dark:text-zinc-100">
    <header class="mx-auto flex max-w-6xl justify-end px-4 py-2">
        <x-appearance />
    </header>
    <main class="mx-auto max-w-xl px-4 pt-3 pb-8 sm:pt-5">
        <h1 class="mb-2 text-3xl font-semibold tracking-tight">{{ $title }}</h1>
        {{ $slot }}
    </main>
</body>
</html>
