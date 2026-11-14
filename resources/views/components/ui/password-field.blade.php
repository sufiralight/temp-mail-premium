@props(['name', 'label', 'id' => null, 'autocomplete' => 'current-password', 'required' => true])
@php($id ??= $name)
<div>
    <label for="{{ $id }}" class="mb-1 block text-sm font-medium">{{ $label }}</label>
    <div class="relative">
        <input id="{{ $id }}" name="{{ $name }}" type="password" autocomplete="{{ $autocomplete }}" @required($required)
            @error($name) aria-invalid="true" aria-describedby="{{ $id }}-error" @enderror
            class="min-h-11 w-full rounded-md border border-zinc-400 bg-transparent ps-3 pe-12 focus-visible:outline-2 dark:border-zinc-600">
        <button type="button" hidden data-password-toggle aria-controls="{{ $id }}"
            aria-label="{{ __('Show :field', ['field' => $label]) }}"
            data-show="{{ __('Show :field', ['field' => $label]) }}" data-hide="{{ __('Hide :field', ['field' => $label]) }}"
            class="absolute inset-y-0 end-0 w-11 rounded-md focus-visible:outline-2">
            <svg data-password-eye aria-hidden="true" class="mx-auto size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            <svg data-password-eye-off aria-hidden="true" class="mx-auto size-5" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m2 2 20 20M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .698 10.75 10.75 0 0 1-1.444 2.49M6.479 6.479a10.75 10.75 0 0 0-4.417 5.171 1 1 0 0 0 0 .698 10.75 10.75 0 0 0 15.459 5.173M9.879 9.879a3 3 0 0 0 4.242 4.242"/>
            </svg>
        </button>
    </div>
    @error($name)<p id="{{ $id }}-error" class="mt-1 text-sm text-red-700 dark:text-red-300" role="alert">{{ $message }}</p>@enderror
</div>
