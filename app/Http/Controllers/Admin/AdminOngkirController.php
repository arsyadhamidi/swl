<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ongkir;
use Illuminate\Http\Request;

class AdminOngkirController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Ongkir::orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('kota', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-ongkir.edit', $item->id ?? '');

                $item->aksi = '
        <a href="'.$editUrl.'" class="btn btn-outline-primary me-1">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button"
                class="btn btn-outline-danger btn-delete"
                data-resultid="'.e($resultid).'">
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

        return view('admin.ongkir.index');
    }

    public function create()
    {
        return view('admin.ongkir.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'kota' => 'required|max:255',
                'biaya' => 'required|min:0',
            ],
            [
                'kota.required' => 'Kota tujuan wajib diisi.',
                'kota.max' => 'Nama kota maksimal 255 karakter.',

                'biaya.required' => 'Biaya ongkir wajib diisi.',
                'biaya.min' => 'Biaya ongkir tidak boleh kurang dari 0.',
            ]
        );

        Ongkir::create([
            'kota' => $request->kota,
            'biaya' => $request->biaya,
        ]);

        return redirect()->route('admin-ongkir.index')->with('success', 'Selamat ! Anda berhasil menambahkan data ongkir');
    }

    public function edit($id)
    {
        $ongkirs = Ongkir::where('id', $id)->firstOrFail();

        return view('admin.ongkir.edit', [
            'ongkirs' => $ongkirs,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'kota' => 'required|max:255',
                'biaya' => 'required|numeric|min:0',
            ],
            [
                'kota.required' => 'Kota tujuan wajib diisi.',
                'kota.max' => 'Nama kota maksimal 255 karakter.',

                'biaya.required' => 'Biaya ongkir wajib diisi.',
                'biaya.numeric' => 'Biaya ongkir harus berupa angka.',
                'biaya.min' => 'Biaya ongkir tidak boleh kurang dari 0.',
            ]
        );

        Ongkir::where('id', $id)->update([
            'kota' => $request->kota,
            'biaya' => $request->biaya,
        ]);

        return redirect()
            ->route('admin-ongkir.index')
            ->with('success', 'Selamat! Anda berhasil memperbarui data ongkir.');
    }

    public function destroy($id)
    {
        $ongkir = Ongkir::findOrFail($id);

        $ongkir->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data ongkir',
        ]);
    }
}
