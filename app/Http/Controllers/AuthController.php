<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $fullPhone = $request->input('country_code', '+62') . $validated['phone_number'];

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $fullPhone,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/login')->with('success', 'Register berhasil. Silakan login.');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withInput()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        // Simpan email login ke session supaya cart bisa dipisah per user
        $request->session()->put('user_email', $user->email);

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Selamat datang Admin!');
        }

        return redirect('/home')->with('success', 'Login berhasil');


    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_email');
        return redirect('/login')->with('success', 'Logout berhasil.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = $request->session()->get('user_email');
        if (!$email) {
            return redirect()->back()->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil direset.');
    }
}

