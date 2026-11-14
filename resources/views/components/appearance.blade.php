<details data-appearance-dropdown class="relative z-10">
    <summary class="flex min-h-11 cursor-pointer list-none items-center gap-2 rounded-lg border border-zinc-300 px-3 text-sm focus-visible:outline-2 dark:border-zinc-700 [&::-webkit-details-marker]:hidden">
        <span class="sr-only">{{ __('Appearance') }}</span>
        <span data-appearance-current class="inline-flex items-center gap-2">{{ __('System') }}</span>
        <svg aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
    </summary>
    <div role="group" aria-label="{{ __('Appearance') }}" class="absolute end-0 top-full mt-1 grid w-40 rounded-lg border border-zinc-300 bg-white p-1 shadow-lg dark:border-zinc-700 dark:bg-zinc-800">
    @foreach (['light' => __('Light'), 'dark' => __('Dark'), 'system' => __('System')] as $mode => $label)
        <button type="button" data-appearance-mode="{{ $mode }}" aria-label="{{ $label }}" title="{{ $label }}" aria-pressed="{{ $mode === 'system' ? 'true' : 'false' }}"
            class="flex min-h-11 items-center gap-2 rounded-md px-2 text-sm hover:bg-zinc-100 focus-visible:outline-2 dark:hover:bg-zinc-700">
            <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                @switch($mode)
                    @case('light')
                        <circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/>
                        @break
                    @case('dark')
                        <path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6.5 6.5 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"/>
                        @break
                    @default
                        <rect width="20" height="14" x="2" y="3" rx="2"/><path d="M12 17v4m-4 0h8"/>
                @endswitch
            </svg>
            <span>{{ $label }}</span>
            <svg data-appearance-check aria-hidden="true" @if($mode !== 'system') hidden @endif class="ms-auto size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg>
        </button>
    @endforeach
    </div>
</details>
