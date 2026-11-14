<x-installer-layout title="Database Configuration">
    <p class="mb-6 text-sm">Step 1 of 4 · Connect an empty MySQL or MariaDB database.</p>
    @if ($errors->has('connection'))
        <p role="alert" class="mb-4 rounded-lg border border-zinc-400 p-3">{{ $errors->first('connection') }}</p>
    @endif
    <form method="post" action="{{ route('install.verify', absolute: false) }}" class="grid gap-4 rounded-xl border border-zinc-300 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
        @csrf
        @foreach (['host' => 'Database host', 'port' => 'Database port', 'database' => 'Database name', 'username' => 'Database username', 'password' => 'Database password'] as $field => $label)
            <div>
                <label class="mb-1 block text-sm font-medium" for="db-{{ $field }}">{{ $label }}</label>
                <input id="db-{{ $field }}" name="{{ $field }}" type="{{ $field === 'password' ? 'password' : ($field === 'port' ? 'number' : 'text') }}"
                    @if ($field !== 'password') value="{{ old($field, $field === 'host' ? $access['host'] : ($field === 'port' ? ($access['port'] ?? 3306) : '')) }}" required @endif
                    @if ($field === 'port') min="1" max="65535" @endif
                    @error($field) aria-invalid="true" aria-describedby="error-{{ $field }}" @enderror
                    autocomplete="{{ $field === 'password' ? 'new-password' : 'off' }}"
                    class="w-full rounded-md border border-zinc-400 bg-transparent px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2">
                @error($field)<p id="error-{{ $field }}" class="mt-1 text-sm" role="alert">{{ $message }}</p>@enderror
            </div>
        @endforeach
        <button class="rounded-md bg-zinc-900 px-4 py-2 font-medium text-white dark:bg-zinc-100 dark:text-zinc-900">Test Connection</button>
    </form>
</x-installer-layout>
