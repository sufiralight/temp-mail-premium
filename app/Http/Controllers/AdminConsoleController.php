<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

final class AdminConsoleController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.overview', [
            'total' => $this->read(fn () => User::count()),
        ]);
    }

    public function users(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'role' => ['nullable', 'in:member,admin,super_admin'],
        ]);
        $query = User::query()->select('id', 'name', 'email', 'role');
        if (filled($filters['q'] ?? null)) {
            $search = '%'.addcslashes($filters['q'], '%_\\').'%';
            $query->where(fn ($query) => $query->whereLike('name', $search)->orWhereLike('email', $search));
        }
        if (filled($filters['role'] ?? null)) {
            $query->where('role', $filters['role']);
        }

        return view('admin.users', [
            'users' => $this->read(fn () => $query->orderBy('id')->paginate(20)->appends($filters)),
            'filters' => $filters,
        ]);
    }

    private function read(\Closure $query): mixed
    {
        try {
            if (! Schema::hasColumns('users', ['id', 'name', 'email', 'role'])) {
                throw new HttpResponseException(response()->view('admin.unavailable', ['guidance' => 'Account schema is incomplete. Ask the deployment operator to review and apply the workspace role migration.'], 503)->header('Cache-Control', 'no-store'));
            }

            return $query();
        } catch (QueryException) {
            throw new HttpResponseException(response()->view('admin.unavailable', ['guidance' => 'Account data is unavailable. Ask the deployment operator to check the database connection and read permissions.'], 503)->header('Cache-Control', 'no-store'));
        }
    }
}
