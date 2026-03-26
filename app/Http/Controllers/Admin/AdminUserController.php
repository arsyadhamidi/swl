<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = User::orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('level_id') && !empty($request->level_id)) {
                $query->where('level_id', $request->level_id);
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-user.edit', $item->id ?? '');

                $item->aksi = '
        <a href="' . $editUrl . '" class="btn btn-outline-primary me-1">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button"
                class="btn btn-outline-danger btn-delete"
                data-resultid="' . e($resultid) . '">
            <i class="fas fa-trash-alt"></i>
        </button>
    ';

                return $item;
            });


            return response()->json([
                'draw' => $request->input('draw'), // Ambil nomor draw dari permintaan
                'recordsTotal' => $totalRecords, // Kirim jumlah total data
                'recordsFiltered' => $totalRecords, // Jumlah data yang difilter sama dengan jumlah total
                'data' => $dataWithActions, // Kirim data yang sesuai dengan halaman dan jumlah per halaman
            ]);
        }

        return view('admin.user.index');
    }

    public function generatepdf(Request $request)
    {
        $query = User::query();

        if ($request->has('level_id') && !empty($request->level_id)) {
            $query->where('level_id', $request->level_id);
        }

        $data = $query->orderBy('id', 'desc')->get();

        $pdf = PDF::loadView('admin.user.export-pdf', ['users' => $data])
            ->setPaper('A4', 'landscape');

        return $pdf->stream(Carbon::now()->format('YmdHis') . '-users.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = User::query();

        if ($request->has('level_id') && !empty($request->level_id)) {
            $query->where('level_id', $request->level_id);
        }

        $data = $query->orderBy('id', 'desc')->get();
        return Excel::download(
            new UserExport($data),
            Carbon::now()->format('YmdHis') . '-users.xlsx'
        );
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'level_id'    => 'required',
                'name'        => 'required|max:120',
                'email'       => 'required|unique:users,email|max:255',
                'telp'        => 'required|max:20',
            ],
            [
                'level_id.required'    => 'Level pengguna wajib dipilih.',

                'name.required'        => 'Nama pengguna wajib diisi.',
                'name.max'             => 'Nama pengguna maksimal 120 karakter.',

                'email.required'       => 'Email pengguna wajib diisi.',
                'email.unique'         => 'Email sudah digunakan.',
                'email.max'            => 'Email maksimal 255 karakter.',

                'telp.required'        => 'Nomor telepon wajib diisi.',
                'telp.max'             => 'Nomor telepon maksimal 20 karakter.',
            ]
        );

        User::create([
            'level_id' => $request->level_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('12345'),
            'duplicate' => '12345',
            'telp' => $request->telp,
        ]);

        return redirect()->route('admin-user.index')->with('success', 'Selamat ! Anda berhasil menambahkan data user registrasi');
    }

    public function edit($id)
    {
        $users = User::where('id', $id)->firstOrFail();
        return view('admin.user.edit', [
            'users' => $users,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'level_id'    => 'required',
                'name'        => 'required|max:120',
                'email'       => 'required|unique:users,email|max:255',
                'telp'        => 'required|max:20',
            ],
            [
                'level_id.required'    => 'Level pengguna wajib dipilih.',

                'name.required'        => 'Nama pengguna wajib diisi.',
                'name.max'             => 'Nama pengguna maksimal 120 karakter.',

                'email.required'       => 'Email pengguna wajib diisi.',
                'email.unique'         => 'Email sudah digunakan.',
                'email.max'            => 'Email maksimal 255 karakter.',

                'telp.required'        => 'Nomor telepon wajib diisi.',
                'telp.max'             => 'Nomor telepon maksimal 20 karakter.',
            ]
        );

        User::where('id', $id)->update([
            'level_id' => $request->level_id,
            'name' => $request->name,
            'email' => $request->email,
            'telp' => $request->telp,
        ]);

        return redirect()->route('admin-user.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data user registrasi');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data user registrasi',
        ]);
    }
}
