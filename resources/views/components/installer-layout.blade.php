@props(['title'])
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · Temp Mail</title>
    @if (is_file(public_path('build/manifest.json')))
        @vite('resources/css/app.css')
    @endif
</head>
<body class="min-h-screen bg-zinc-100 text-zinc-950 dark:bg-zinc-900 dark:text-zinc-100">
    <main class="mx-auto max-w-xl px-5 py-12 sm:py-20">
        <h1 class="mb-6 text-3xl font-semibold tracking-tight">{{ $title }}</h1>
        {{ $slot }}
    </main>
</body>
</html>
