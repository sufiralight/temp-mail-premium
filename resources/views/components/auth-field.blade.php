@props(['name', 'label', 'type' => 'text', 'autocomplete' => 'off', 'value' => null])
<div>
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium">{{ $label }}</label>
    <div class="flex items-center gap-2">
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" autocomplete="{{ $autocomplete }}" required
            @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
            class="min-w-0 flex-1 rounded-md border border-zinc-400 bg-transparent px-3 py-2 focus-visible:outline-2 dark:border-zinc-600">
        @if ($type === 'password')
            <button type="button" hidden data-password-toggle data-show="{{ __('Show') }}" data-hide="{{ __('Hide') }}" aria-controls="{{ $name }}"
                aria-label="{{ __('Toggle visibility for :field', ['field' => $label]) }}" aria-pressed="false"
                class="min-h-11 rounded px-2 text-sm underline focus-visible:outline-2">{{ __('Show') }}</button>
        @endif
    </div>
    @error($name)
        <p id="{{ $name }}-error" class="mt-1 text-sm text-red-700 dark:text-red-300" role="alert">{{ $message }}</p>
    @enderror
</div>
