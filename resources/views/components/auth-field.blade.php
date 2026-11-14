@props(['name', 'label', 'type' => 'text', 'autocomplete' => 'off', 'value' => null])
<div>
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium">{{ $label }}</label>
    <div class="flex items-center gap-2">
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" autocomplete="{{ $autocomplete }}" required
            value="{{ old($name, $value) }}"
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
            class="min-w-0 flex-1 rounded-md border border-zinc-400 bg-transparent px-3 py-2 focus-visible:outline-2 dark:border-zinc-600">
    </div>
    @error($name)
        <p id="{{ $name }}-error" class="mt-1 text-sm text-red-700 dark:text-red-300" role="alert">{{ $message }}</p>
    @enderror
</div>
