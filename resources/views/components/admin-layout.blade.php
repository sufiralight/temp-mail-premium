@props(['title'])
@php($locale = str_replace('_', '-', app()->getLocale()))
<!doctype html>
<html lang="{{ $locale }}" dir="{{ in_array(strtok($locale, '-'), ['ar', 'fa', 'he', 'ur', 'ps', 'yi'], true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · Admin Console</title>
    <script src="{{ asset('appearance-init.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-100 text-zinc-950 dark:bg-zinc-950 dark:text-zinc-100">
    <a href="#admin-content" class="sr-only focus:not-sr-only">{{ __('Skip to content') }}</a>
    <header class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-300 px-4 py-2 dark:border-zinc-800">
        <span class="font-semibold">{{ __('Admin Console') }}</span>
        <div class="flex flex-wrap items-center gap-2">
            <x-appearance />
            <details class="relative">
                <summary class="flex min-h-11 cursor-pointer items-center rounded-lg border border-zinc-300 px-3 text-sm focus-visible:outline-2 dark:border-zinc-700">{{ __('Account') }}</summary>
                <div class="absolute end-0 z-10 mt-1 w-44 rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <a href="{{ route('dashboard') }}" class="block py-2 underline">{{ __('Your account') }}</a>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="min-h-11 underline">{{ __('Sign out') }}</button>
                    </form>
                </div>
            </details>
        </div>
    </header>
    <div class="mx-auto max-w-7xl md:grid md:grid-cols-[12rem_minmax(0,1fr)]">
        <aside class="border-b border-zinc-300 p-4 md:border-e md:border-b-0 dark:border-zinc-800">
            <nav aria-label="{{ __('Administration') }}">
                <details open>
                    <summary class="min-h-11 cursor-pointer py-2 text-sm font-semibold focus-visible:outline-2">{{ __('Workspace') }}</summary>
                    @foreach (['admin.overview' => __('Overview'), 'admin.users' => __('Users')] as $route => $label)
                        <a href="{{ route($route) }}" @if(request()->routeIs($route)) aria-current="page" @endif
                            class="flex min-h-11 items-center rounded-lg px-3 text-sm hover:bg-zinc-200 focus-visible:outline-2 aria-[current=page]:bg-zinc-200 dark:hover:bg-zinc-800 dark:aria-[current=page]:bg-zinc-800">{{ $label }}</a>
                    @endforeach
                </details>
            </nav>
        </aside>
        <main id="admin-content" class="min-w-0 p-4 sm:p-6">
            <h1 class="mb-5 text-2xl font-semibold">{{ $title }}</h1>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
