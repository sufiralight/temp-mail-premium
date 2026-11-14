<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

final class AuthenticationController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email', 'max:254'], 'password' => ['required', 'string', 'max:72']]);
        $credentials['email'] = Str::lower($credentials['email']);
        if (strlen($credentials['password']) > 72 || ! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => __('The email or password is incorrect.')]);
        }

        $request->session()->regenerate();

        return redirect('/account');
    }

    public function register(Request $request): RedirectResponse
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => Str::lower($request->input('email'))]);
        }
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:254', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'max:72', 'confirmed'],
        ]);
        if (strlen($attributes['password']) > 72) {
            throw ValidationException::withMessages(['password' => __('Use at most 72 bytes for the password.')]);
        }

        User::create($attributes);

        return redirect('/login')->with('status', __('Your account is ready. Sign in to continue.'));
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:254']]);
        $data['email'] = Str::lower($data['email']);
        $mailer = config('mail.default');

        if (! in_array(config("mail.mailers.{$mailer}.transport"), ['smtp', 'sendmail', 'ses', 'ses-v2', 'mailgun', 'postmark', 'resend'], true)) {
            return back()->with('status', __('Password reset email is currently unavailable. Contact the site operator.'));
        }

        try {
            Password::sendResetLink($data);
        } catch (Throwable) {
            // Keep the response identical for known and unknown accounts.
        }

        return back()->with('status', __('If the address belongs to an account, check your inbox. Delivery depends on the configured mail service.'));
    }

    public function reset(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'token' => ['required', 'string', 'regex:/\A[a-f0-9]{64}\z/'],
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required', 'string', 'min:12', 'max:72', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);
        if (strlen($credentials['password']) > 72) {
            throw ValidationException::withMessages(['password' => __('Use at most 72 bytes for the password.')]);
        }

        $credentials['email'] = Str::lower($credentials['email']);
        $status = Password::reset($credentials, function (User $user, #[\SensitiveParameter] string $password): void {
            $user->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => __('The reset link is invalid or expired.')]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', __('Your password was reset. Sign in to continue.'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
