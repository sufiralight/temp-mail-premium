<x-admin-layout :title="__('Users')">
    <form method="get" action="{{ route('admin.users') }}" class="mb-5 flex flex-wrap items-end gap-3">
        <label class="grid min-w-0 gap-1 text-sm">{{ __('Search by name or email') }}
            <input name="q" value="{{ $filters['q'] ?? '' }}" maxlength="120" class="min-h-11 min-w-0 rounded-md border border-zinc-400 px-3">
        </label>
        <label class="grid gap-1 text-sm">{{ __('Role') }}
            <select name="role" class="min-h-11 rounded-md border border-zinc-400 px-3">
                <option value="">{{ __('All roles') }}</option>
                @foreach (['member' => __('Member'), 'admin' => __('Admin'), 'super_admin' => __('Super Admin')] as $role => $label)
                    <option value="{{ $role }}" @selected(($filters['role'] ?? null) === $role)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <button class="min-h-11 rounded-md bg-zinc-900 px-4 text-white dark:bg-zinc-100 dark:text-zinc-950">{{ __('Apply filters') }}</button>
        @foreach ($errors->all() as $error)
            <p role="alert" class="w-full text-sm">{{ $error }}</p>
        @endforeach
    </form>
    <div class="overflow-x-auto rounded-lg border border-zinc-300 dark:border-zinc-800">
        <table class="w-full text-start text-sm">
            <thead><tr><th scope="col" class="p-3 text-start">{{ __('Name') }}</th><th scope="col" class="p-3 text-start">{{ __('Email') }}</th><th scope="col" class="p-3 text-start">{{ __('Role') }}</th></tr></thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-t border-zinc-300 dark:border-zinc-800">
                        <td class="p-3 wrap-anywhere">{{ $user->name }}</td>
                        <td class="p-3 wrap-anywhere"><bdi>{{ $user->email }}</bdi></td>
                        <td class="p-3">{{ match($user->role) { 'member' => __('Member'), 'admin' => __('Admin'), 'super_admin' => __('Super Admin'), default => __('Unknown') } }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="p-3">{{ __('No users match your filters.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</x-admin-layout>
