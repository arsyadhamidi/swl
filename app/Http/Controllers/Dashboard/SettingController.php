<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $users = User::where('users.id', $auth->id)
            ->first();
        return view('dashboard.setting.index', [
            'users' => $users,
        ]);
    }

    public function updateprofil(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120',
            'telp' => 'required|max:20',
        ], [
            'name.required' => 'Nama Lengkap wajib diisi',
            'name.max' => 'Nama Lengkap maksimal 120 karakter',
            'telp.required' => 'Nomor telepon wajib diisi',
            'telp.max' => 'Nomor Telepon maksimal 20 karakter',
        ]);

        $users = Auth::user();
        User::where('id', $users->id)->update([
            'name' => $request->name,
            'telp' => $request->telp,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui data profile!');
    }

    public function updateemail(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users,email',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah tersedia',
        ]);

        $users = Auth::user();
        User::where('id', $users->id)->update([
            'email' => $request->email,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui alamat email!');
    }

    public function updatepassword(Request $request)
    {
        $request->validate([
            'password' => 'required|max:30',
        ], [
            'password.required' => 'Password wajib diisi',
            'password.max' => 'Password maksimal 30 karakter',
        ]);

        $users = Auth::user();
        User::where('id', $users->id)->update([
            'password' => bcrypt($request->password),
            'duplicate' => $request->password,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui kata sandi!');
    }

    public function updategambar(Request $request)
    {
        $request->validate(
            [
                'foto_profile' => 'required|max:10248',
            ],
            [
                'foto_profile.required' => 'Foto profil wajib diunggah.',
                'foto_profile.max'      => 'Ukuran foto profil maksimal 10 MB.',
            ]
        );

        $users = Auth::user();

        $fotoProfile = null;
        if($request->file('foto_profile')){
            $fotoProfile = $request->file('foto_profile')->store('foto_profile');
        }

        User::where('id', $users->id)->update([
            'foto_profile' => $fotoProfile,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui foto profile');
    }

    public function hapusgambar()
    {
        $users = Auth::user();

        if($users->foto_profile){
            Storage::delete($users->foto_profile);
        }

        User::where('id', $users->id)->update([
            'foto_profile' => null,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil menghapus foto profile');
    }
}
