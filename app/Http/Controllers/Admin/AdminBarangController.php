<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BarangExport;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminBarangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Barang::join('kategoris', 'barangs.kategori_id', 'kategoris.id')
                ->select([
                    'barangs.*',
                    'kategoris.nm_kategori',
                ])
                ->orderBy('barangs.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('kategoris.nm_kategori', 'LIKE', "%{$search}%")
                        ->orWhere('barangs.nm_barang', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('kategori_id') && !empty($request->kategori_id)) {
                $query->where('barangs.kategori_id', $request->kategori_id);
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-barang.edit', $item->id ?? '');
                $imageUrl = asset('storage/' . $item->foto_barang);

                $item->aksi = '
        <a href="' . $editUrl . '" class="btn btn-outline-primary">
            <i class="fas fa-edit"></i>
        </a>
        <a href="' . $imageUrl . '" class="btn btn-outline-warning mx-2" target="_blank">
            <i class="fas fa-image"></i>
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

        $kategoris = Kategori::get();

        return view('admin.barang.index', [
            'kategoris' => $kategoris,
        ]);
    }

    public function create()
    {
        $kategoris = Kategori::get();
        return view('admin.barang.create', [
            'kategoris' => $kategoris,
        ]);
    }

    public function generatepdf(Request $request)
    {
        $query = Barang::join('kategoris', 'barangs.kategori_id', 'kategoris.id')
            ->select([
                'barangs.*',
                'kategoris.nm_kategori',
            ]);

        if ($request->has('kategori_id') && !empty($request->kategori_id)) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $data = $query->orderBy('barangs.id', 'desc')->get();

        $pdf = PDF::loadView('admin.barang.export-pdf', ['barangs' => $data])
            ->setPaper('A4', 'landscape');

        return $pdf->stream(Carbon::now()->format('YmdHis') . '-barang.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = Barang::join('kategoris', 'barangs.kategori_id', 'kategoris.id')
            ->select([
                'barangs.*',
                'kategoris.nm_kategori',
            ]);

        if ($request->has('kategori_id') && !empty($request->kategori_id)) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $data = $query->orderBy('id', 'desc')->get();
        return Excel::download(
            new BarangExport($data),
            Carbon::now()->format('YmdHis') . '-barang.xlsx'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_barang' => 'required|max:100',
            'harga' => 'required',
            'stok' => 'required',
            'kategori_id' => 'required',
            'foto_barang' => 'required|max:10248|mimes:jpg,png,jpeg',
        ], [

            // Nama Barang
            'nm_barang.required' => 'Nama barang wajib diisi.',
            'nm_barang.max' => 'Nama barang maksimal 100 karakter.',

            // Harga
            'harga.required' => 'Harga barang wajib diisi.',

            // Stok
            'stok.required' => 'Stok barang wajib diisi.',

            // Kategori
            'kategori_id.required' => 'Kategori barang wajib dipilih.',

            // Foto
            'foto_barang.required' => 'Foto barang wajib diupload.',
            'foto_barang.max' => 'Ukuran foto maksimal 10MB.',
            'foto_barang.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
        ]);

        $fotoBarang = null;
        if ($request->file('foto_barang')) {
            $fotoBarang = $request->file('foto_barang')->store('foto_barang');
        }

        Barang::create([
            'nm_barang' => $request->nm_barang,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
            'ket_barang' => $request->ket_barang,
            'foto_barang' => $fotoBarang,
        ]);

        return redirect()->route('admin-barang.index')->with('success', 'Selamat ! Anda berhasil membuat data barang!');
    }

    public function edit($id)
    {
        $kategoris = Kategori::get();
        $barangs = Barang::where('id', $id)->firstOrFail();
        return view('admin.barang.edit', [
            'kategoris' => $kategoris,
            'barangs' => $barangs,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nm_barang' => 'required|max:100',
            'harga' => 'required',
            'stok' => 'required',
            'kategori_id' => 'required',
            'foto_barang' => 'nullable|max:10248|mimes:jpg,png,jpeg',
        ], [

            // Nama Barang
            'nm_barang.required' => 'Nama barang wajib diisi.',
            'nm_barang.max' => 'Nama barang maksimal 100 karakter.',

            // Harga
            'harga.required' => 'Harga barang wajib diisi.',

            // Stok
            'stok.required' => 'Stok barang wajib diisi.',

            // Kategori
            'kategori_id.required' => 'Kategori barang wajib dipilih.',

            // Foto
            'foto_barang.max' => 'Ukuran foto maksimal 10MB.',
            'foto_barang.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
        ]);

        $barangs = Barang::where('id', $id)->firstOrFail();

        $fotoBarang = null;
        if ($request->file('foto_barang')) {
            if ($barangs->foto_barang) {
                Storage::delete($barangs->foto_barang);
            }
            $fotoBarang = $request->file('foto_barang')->store('foto_barang');
        } else {
            $fotoBarang = $barangs->foto_barang;
        }

        $barangs->update([
            'nm_barang' => $request->nm_barang,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
            'ket_barang' => $request->ket_barang,
            'foto_barang' => $fotoBarang,
        ]);

        return redirect()->route('admin-barang.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data barang!');
    }

    public function destroy($id)
    {
        $barangs = Barang::where('id', $id)->firstOrFail();
        if ($barangs->foto_barang) {
            Storage::delete($barangs->foto_barang);
        }
        $barangs->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data barang',
        ]);
    }
}
