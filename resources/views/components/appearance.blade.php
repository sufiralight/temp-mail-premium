<div role="group" aria-label="{{ __('Appearance') }}" class="inline-flex rounded-lg border border-zinc-300 p-0.5 dark:border-zinc-700">
    @foreach (['light' => __('Light'), 'dark' => __('Dark'), 'system' => __('System')] as $mode => $label)
        <button type="button" data-appearance-mode="{{ $mode }}" aria-label="{{ $label }}" title="{{ $label }}" aria-pressed="{{ $mode === 'system' ? 'true' : 'false' }}"
            class="grid size-11 place-items-center rounded-md focus-visible:outline-2 aria-pressed:bg-zinc-200 dark:aria-pressed:bg-zinc-700">
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
        </button>
    @endforeach
</div>
