<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KategoriExport;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminKategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Kategori::orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('nm_kategori', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-kategori.edit', $item->id ?? '');

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

        return view('admin.kategori.index');
    }

    public function generatepdf()
    {
        $query = Kategori::query();

        $data = $query->orderBy('id', 'desc')->get();

        $pdf = PDF::loadView('admin.kategori.export-pdf', ['kategoris' => $data])
            ->setPaper('A4', 'landscape');

        return $pdf->stream(Carbon::now()->format('YmdHis') . '-kategori.pdf');
    }

    public function generateexcel()
    {
        $query = Kategori::query();

        $data = $query->orderBy('id', 'desc')->get();

        return Excel::download(
            new KategoriExport($data),
            Carbon::now()->format('YmdHis') . '-kategori.xlsx'
        );
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nm_kategori'    => 'required|max:100',
            ],
            [

                'nm_kategori.required'        => 'Nama kategori wajib diisi.',
                'nm_kategori.max'             => 'Nama kategori maksimal 120 karakter.',
            ]
        );

        Kategori::create([
            'nm_kategori' => $request->nm_kategori,
        ]);

        return redirect()->route('admin-kategori.index')->with('success', 'Selamat ! Anda berhasil menambahkan data kategori');
    }

    public function edit($id)
    {
        $kategoris = Kategori::where('id', $id)->firstOrFail();
        return view('admin.kategori.edit', [
            'kategoris' => $kategoris,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'nm_kategori'    => 'required|max:100',
            ],
            [

                'nm_kategori.required'        => 'Nama kategori wajib diisi.',
                'nm_kategori.max'             => 'Nama kategori maksimal 120 karakter.',
            ]
        );

        Kategori::where('id', $id)->update([
            'nm_kategori' => $request->nm_kategori,
        ]);

        return redirect()->route('admin-kategori.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data kategori');
    }

    public function destroy($id)
    {
        Kategori::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data kategori',
        ]);
    }
}
