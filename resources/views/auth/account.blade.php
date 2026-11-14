<x-auth-layout :title="__('Your account')">
    <p>{{ auth()->user()->name }}</p>
    @can('admin.access')
        <a href="{{ route('admin.overview') }}" class="mt-4 inline-block underline">{{ __('Admin Console') }}</a>
    @endcan
    <form method="post" action="/logout" class="mt-4">
        @csrf
        <button type="submit" class="underline">{{ __('Sign out') }}</button>
    </form>
</x-auth-layout>
