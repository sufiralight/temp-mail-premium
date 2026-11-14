<x-auth-layout :title="__('Your account')">
    <p>{{ auth()->user()->name }}</p>
    <form method="post" action="/logout" class="mt-4">
        @csrf
        <button type="submit" class="underline">{{ __('Sign out') }}</button>
    </form>
</x-auth-layout>
