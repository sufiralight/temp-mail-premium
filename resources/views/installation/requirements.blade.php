<x-installer-layout title="System Requirements">
    <p role="status" class="mb-6 text-sm">Step 2 of 4 · {{ $engine }} connection verified.</p>
    <ul class="divide-y divide-zinc-300 rounded-xl border border-zinc-300 px-4 dark:divide-zinc-700 dark:border-zinc-700">
        @foreach ($checks as $name => $passed)
            <li class="flex justify-between gap-4 py-3"><span>{{ $name }}</span><strong>{{ $passed ? 'Ready' : 'Action required' }}</strong></li>
        @endforeach
    </ul>
    <a href="{{ route('install.database', absolute: false) }}" class="mt-6 inline-block underline">Back to database configuration</a>
</x-installer-layout>
