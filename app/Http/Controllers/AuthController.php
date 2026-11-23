<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->status === 'nonaktif') {
                Auth::logout();
                return back()->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi Admin.');
            }

            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'password' => Hash::make($validated['password']),
            'role' => 'pelanggan',
            'status' => 'aktif',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang di Studio Musik Positif.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function resetPasswordDirect(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'no_hp.required' => 'Nomor HP / WhatsApp wajib diisi untuk verifikasi akun.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = User::where('email', $validated['email'])
            ->where('no_hp', $validated['no_hp'])
            ->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'Kombinasi email dan nomor HP/WhatsApp tidak ditemukan.'])
                ->onlyInput('email', 'no_hp');
        }

        if ($user->status === 'nonaktif') {
            return back()->with('error', 'Akun Anda berstatus nonaktif. Silakan hubungi Admin.');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')
            ->with('success', 'Kata sandi berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}