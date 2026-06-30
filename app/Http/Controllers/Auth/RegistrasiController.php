<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistrasiController extends Controller
{
    public function index()
    {
        return view('autentikasi.registrasi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120',
            'email' => 'required|max:255|unique:users,email',
            'password' => 'required|min:5',
            'telp' => 'required|max:20',
        ], [

            // NAME
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 120 karakter.',

            // EMAIL
            'email.required' => 'Email wajib diisi.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',

            // PASSWORD
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 5 karakter.',

            // TELEPON
            'telp.required' => 'Nomor telepon wajib diisi.',
            'telp.max' => 'Nomor telepon maksimal 20 karakter.',
        ]);

        $carbons = Carbon::now();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'duplicate' => $request->password,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'email_verified_at' => $carbons,
            'level_id' => '2',
        ]);

        return redirect()->route('login')->with('success', 'Selamat ! Anda berhasil melakukan registrasi akun di SWL Collection');
    }
}
