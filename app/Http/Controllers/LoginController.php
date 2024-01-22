<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    // Login Manual
    public function authenticate(Request $request)
    {
        $credentials = $request->validate(
            [
                'id' => 'required',
                'password' => 'required',

            ],
            [
                'id.required' => 'NIP Wajib di Isi',
                'password.required' => 'Password Wajib di Isi',
            ]
        );
        // $findUser = User::where('id', strval($request->nip))->first();
        if (Auth::attempt($credentials)) {
            $findUser = User::where('id', $credentials['id'])->first();
            if ($findUser->role == 'PCL') {
                if ($findUser->kec_id == null) {
                    return redirect('/login')->with('success', 'Akun Telah terdaftar, Harap Hubungi Admin untuk pembagian wilayah tugas');
                }
                $url = '/entry';
            } elseif ($findUser->role == 'AD') {
                $url = '/validasi/dinas';
            } elseif ($findUser->role == 'PML') {
                $url = '/validasi/bps';
            }
            // if ($findUser && Hash::check($request->password, $findUser->password)) {
            $request->session()->regenerate();
            return redirect()->intended($url);
        }
        // Jika gagal
        return back()->with('loginError', 'Login Gagal!');
    }

    // Register
    public function register(Request $request)
    {
        # Validation
        $request->validate([
            'nip' => 'required|unique:users,id',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                Password::min(8)
                    // ->letters()
                    ->mixedCase()
                    ->numbers()
                // ->symbols()
                // ->uncompromised()
            ],
            'confirmPassword' => 'required|same:password',
        ],   [
            'required' => ':attribute Wajib di Isi',
            'email' => 'Alamat :attribute  Harus Valid',
            'same' => ':attribute dan :other  Harus Sama.',
            'unique' => ' :attribute sudah terdaftar',
            'min' => ':attribute harus minimal :min. Karakter',

        ]);
        User::create(
            [
                'id' => $request->nip,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'PCL',
            ]
        );
        return redirect('/login')->with('success', 'Akun Telah terdaftar, Harap Hubungi Admin untuk pembagian wilayah tugas');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
