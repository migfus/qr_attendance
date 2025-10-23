<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{Auth, Hash, Mail};
use Inertia\{Inertia, Response};

use App\Models\User;

class AuthController extends Controller {
    public function login(): Response {
        return Inertia::render('auth/Login', ['page_title' => 'Login']);
    }

    public function loginSubmit(Request $req): RedirectResponse {
        $req->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
            'remember' => ['nullable']
        ]);

        $remember = $req->remember ? true : false; // Convert to boolean

        if (Auth::attempt(['email' => $req->string('email'), 'password' => $req->string('password')], $remember)) {
            $req->session()->regenerate();
            return to_route('dashboard.index')
                ->with('success', ['title' => 'Logged In Successfully!', 'content' => 'Welcome, ' . Auth::user()->name]);
        }

        return to_route('login')
            ->withErrors([
                'email' => 'Invalid Email & Password',
                'password' => 'Invalid Email & Password',
            ]);
    }

    public function forgot(): Response {
        return Inertia::render('auth/Forgot', ['page_title' => 'Forgot']);
    }

    public function forgotSubmit(Request $req): RedirectResponse {
        $req->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where('email', $req->email)->first();
        if (!$user) {
            return to_route('forgot')
                ->withErrors(['email' => "Email don't exist."]);
        }

        try {
            $code = $this->generateRandomString();
            User::whereId($user->id)->update([
                'recovery_code' => $code
            ]);

            Mail::to($user->email)
                ->send(new ResetPasswordMail($user->email, $user->name, $code));
        } catch (\Exception $e) {
            dd($e);
        }

        return to_route('forgot')
            ->with('success', ['title' => 'Email Link Sent!', 'content' => 'You can check your email for the password reset link.']);
    }

    public function forgotCode(string $code): RedirectResponse | Response {
        if ($code) {
            if (User::where('recovery_code', $code)->first()) {
                return Inertia::render('auth/ChangePassword', ['page_title' => 'Change Password']);
            }
        }

        return to_route('forgot')
            ->withErrors(['email' => 'Expired or invalid code. Please try again.']);
    }

    public function forgotChangePassword(Request $req): RedirectResponse {
        $req->validate([
            'password' => ['min:6', 'confirmed'],
            'password_confirmation' => ['min:6', 'required'],
            'code' => ['required'],
            'email' => ['']
        ]);

        User::query()
            ->where('recovery_code', $req->code)
            ->update([
                'recovery_code' => null,
                'password' => Hash::make($req->password),
            ]);

        return to_route('login', ['email' => $req->email])
            ->with('success', ['title' => 'Successfuly Updated the Password', 'content' => 'You can now login with the new password.']);
    }

    public function logout(): RedirectResponse {
        Auth::guard('web')->logout();

        return to_route('login')
            ->with(
                'success',
                ['title' => 'Logged Out', 'content' => 'Logged Out Successfully!']
            );
    }
}
