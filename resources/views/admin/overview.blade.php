<x-admin-layout :title="__('Overview')">
    <dl class="max-w-sm rounded-xl border border-zinc-300 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">
        <dt class="text-sm">{{ __('Total users') }}</dt>
        <dd class="mt-1 font-mono text-3xl font-semibold">{{ number_format($total) }}</dd>
    </dl>
    <a href="{{ route('admin.users') }}" class="mt-5 inline-flex min-h-11 items-center underline">{{ __('View users') }}</a>
</x-admin-layout>
