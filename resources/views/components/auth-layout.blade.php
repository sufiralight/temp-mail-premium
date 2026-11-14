@props(['title'])
@php($locale = str_replace('_', '-', app()->getLocale()))
<!doctype html>
<html lang="{{ $locale }}" dir="{{ in_array(strtok($locale, '-'), ['ar', 'fa', 'he', 'ur', 'ps', 'yi'], true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <title>{{ $title }} · Temp Mail</title>
    <script src="{{ asset('appearance-init.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-100 text-zinc-950 dark:bg-zinc-950 dark:text-zinc-100">
    <header class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-4">
        <a href="/" class="font-semibold tracking-tight">Temp Mail</a>
        <x-appearance />
    </header>
    <main class="mx-auto max-w-md px-5 py-8 sm:py-16">
        <div class="rounded-2xl border border-zinc-300 bg-white p-6 shadow-xs sm:p-8 dark:border-zinc-800 dark:bg-zinc-900">
            <h1 class="mb-5 text-2xl font-semibold tracking-tight">{{ $title }}</h1>
            @if (session('status'))
                <p role="status" class="mb-4 rounded-md border border-zinc-300 p-3 text-sm dark:border-zinc-700">{{ session('status') }}</p>
            @endif
            {{ $slot }}
        </div>
    </main>
</body>
</html>
