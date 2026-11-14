@php
    $title = match ($screen) {
        'register' => __('Create account'),
        'forgot' => __('Forgot password'),
        'reset' => __('Reset password'),
        default => __('Sign in'),
    };
    $action = match ($screen) {
        'register' => '/register',
        'forgot' => '/forgot-password',
        'reset' => '/reset-password',
        default => '/login',
    };
@endphp
<x-auth-layout :title="$title">
    <form method="post" action="{{ $action }}" data-auth-form class="grid gap-4">
        @csrf
        @error('token')<p role="alert">{{ $message }}</p>@enderror
        @if ($screen === 'reset')
            <input type="hidden" name="token" value="{{ $token }}">
        @endif
        @if ($screen === 'register')
            <x-auth-field name="name" :label="__('Full name')" autocomplete="name" />
        @endif
        <x-auth-field name="email" :label="__('Email address')" type="email" autocomplete="email" :value="$screen === 'reset' ? (is_string(request()->query('email')) ? request()->query('email') : null) : null" />
        @unless ($screen === 'forgot')
            <x-ui.password-field name="password" :label="__('Password')" :autocomplete="$screen === 'login' ? 'current-password' : 'new-password'" />
        @endunless
        @if (in_array($screen, ['register', 'reset'], true))
            <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Use at least 12 characters and at most 72 bytes.') }}</p>
            <x-ui.password-field name="password_confirmation" :label="__('Confirm password')" autocomplete="new-password" />
        @endif
        @if ($screen === 'login')
            <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="remember" value="1"> {{ __('Remember me') }}</label>
                <a class="underline" href="/forgot-password">{{ __('Forgot password?') }}</a>
            </div>
        @endif
        <button type="submit" data-pending="{{ __('Please wait…') }}" class="min-h-11 rounded-md bg-zinc-900 px-4 font-medium text-white focus-visible:outline-2 focus-visible:outline-offset-2 disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-950">
            {{ $screen === 'forgot' ? __('Request reset link') : $title }}
        </button>
    </form>
    <p class="mt-5 text-sm"><a class="underline" href="{{ $screen === 'login' ? '/register' : '/login' }}">{{ $screen === 'login' ? __('Create account') : __('Sign in') }}</a></p>
</x-auth-layout>
