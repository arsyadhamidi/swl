<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BarangExport;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangVariasi;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

            if ($request->has('kategori_id') && ! empty($request->kategori_id)) {
                $query->where('barangs.kategori_id', $request->kategori_id);
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-barang.edit', $item->id ?? '');
                $imageUrl = asset('storage/'.$item->foto_barang);

                $item->aksi = '
        <a href="'.$editUrl.'" class="btn btn-outline-primary">
            <i class="fas fa-edit"></i>
        </a>
        <a href="'.$imageUrl.'" class="btn btn-outline-warning mx-2" target="_blank">
            <i class="fas fa-image"></i>
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
        $query = Barang::join(
            'kategoris',
            'barangs.kategori_id',
            '=',
            'kategoris.id'
        )
            ->leftJoin(
                'barang_variasis',
                'barangs.id',
                '=',
                'barang_variasis.barang_id'
            )
            ->selectRaw('
            barangs.id,
            barangs.nm_barang,
            kategoris.nm_kategori,
            COUNT(barang_variasis.id) as total_variasi,
            COALESCE(SUM(barang_variasis.stok),0) as total_stok,
            MIN(barang_variasis.harga) as harga_min,
            MAX(barang_variasis.harga) as harga_max
        ')
            ->groupBy(
                'barangs.id',
                'barangs.nm_barang',
                'kategoris.nm_kategori'
            );

        if ($request->filled('kategori_id')) {
            $query->where(
                'barangs.kategori_id',
                $request->kategori_id
            );
        }

        $data = $query
            ->orderByDesc('barangs.id')
            ->get();

        $pdf = PDF::loadView(
            'admin.barang.export-pdf',
            [
                'barangs' => $data,
            ]
        )->setPaper('A4', 'landscape');

        return $pdf->stream(
            Carbon::now()->format('YmdHis').'-barang.pdf'
        );
    }

    public function generateexcel(Request $request)
    {
        $query = Barang::join(
            'kategoris',
            'barangs.kategori_id',
            '=',
            'kategoris.id'
        )
            ->leftJoin(
                'barang_variasis',
                'barangs.id',
                '=',
                'barang_variasis.barang_id'
            )
            ->selectRaw('
            barangs.id,
            barangs.nm_barang,
            barangs.ket_barang,
            kategoris.nm_kategori,
            COUNT(barang_variasis.id) as total_variasi,
            COALESCE(SUM(barang_variasis.stok),0) as total_stok,
            MIN(barang_variasis.harga) as harga_min,
            MAX(barang_variasis.harga) as harga_max
        ')
            ->groupBy(
                'barangs.id',
                'barangs.nm_barang',
                'barangs.ket_barang',
                'kategoris.nm_kategori'
            );

        if ($request->filled('kategori_id')) {
            $query->where(
                'barangs.kategori_id',
                $request->kategori_id
            );
        }

        $data = $query
            ->orderByDesc('barangs.id')
            ->get();

        return Excel::download(
            new BarangExport($data),
            Carbon::now()->format('YmdHis').'-barang.xlsx'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_barang' => 'required|max:100',
            'kategori_id' => 'required',
            'foto_barang' => 'required|max:10248|mimes:jpg,png,jpeg',

            'ukuran.*' => 'required|max:10',
            'warna.*' => 'required|max:50',
            'harga.*' => 'required|numeric|min:0',
            'stok.*' => 'required|integer|min:0',
        ], [

            'nm_barang.required' => 'Nama barang wajib diisi.',
            'nm_barang.max' => 'Nama barang maksimal 100 karakter.',

            'kategori_id.required' => 'Kategori barang wajib dipilih.',

            'foto_barang.required' => 'Foto barang wajib diupload.',
            'foto_barang.max' => 'Ukuran foto maksimal 10MB.',
            'foto_barang.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',

            'ukuran.*.required' => 'Ukuran wajib diisi.',
            'warna.*.required' => 'Warna wajib diisi.',
            'harga.*.required' => 'Harga wajib diisi.',
            'stok.*.required' => 'Stok wajib diisi.',
        ]);

        DB::beginTransaction();

        try {

            $fotoBarang = null;

            if ($request->hasFile('foto_barang')) {
                $fotoBarang = $request->file('foto_barang')
                    ->store('foto_barang');
            }

            $barang = Barang::create([
                'nm_barang' => $request->nm_barang,
                'kategori_id' => $request->kategori_id,
                'ket_barang' => $request->ket_barang,
                'foto_barang' => $fotoBarang,
            ]);

            if ($request->filled('ukuran')) {

                foreach ($request->ukuran as $key => $ukuran) {

                    BarangVariasi::create([
                        'barang_id' => $barang->id,
                        'ukuran' => $ukuran,
                        'warna' => $request->warna[$key],
                        'harga' => $request->harga[$key],
                        'stok' => $request->stok[$key],
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin-barang.index')
                ->with('success', 'Selamat! Anda berhasil membuat data barang.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $kategoris = Kategori::get();

        $barang = Barang::where('id', $id)
            ->firstOrFail();

        $variasis = BarangVariasi::where('barang_id', $id)
            ->get();

        return view('admin.barang.edit', [
            'kategoris' => $kategoris,
            'barang' => $barang,
            'variasis' => $variasis,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nm_barang' => 'required|max:100',
            'kategori_id' => 'required',
            'foto_barang' => 'nullable|max:10248|mimes:jpg,png,jpeg',

            'ukuran.*' => 'required|max:10',
            'warna.*' => 'required|max:50',
            'harga.*' => 'required|numeric|min:0',
            'stok.*' => 'required|integer|min:0',
        ], [

            'nm_barang.required' => 'Nama barang wajib diisi.',
            'nm_barang.max' => 'Nama barang maksimal 100 karakter.',

            'kategori_id.required' => 'Kategori barang wajib dipilih.',

            'foto_barang.max' => 'Ukuran foto maksimal 10MB.',
            'foto_barang.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',

            'ukuran.*.required' => 'Ukuran wajib diisi.',
            'warna.*.required' => 'Warna wajib diisi.',
            'harga.*.required' => 'Harga wajib diisi.',
            'stok.*.required' => 'Stok wajib diisi.',
        ]);

        DB::beginTransaction();

        try {

            $barang = Barang::findOrFail($id);

            $fotoBarang = $barang->foto_barang;

            if ($request->hasFile('foto_barang')) {

                if ($barang->foto_barang) {
                    Storage::delete($barang->foto_barang);
                }

                $fotoBarang = $request->file('foto_barang')
                    ->store('foto_barang');
            }

            $barang->update([
                'nm_barang' => $request->nm_barang,
                'kategori_id' => $request->kategori_id,
                'ket_barang' => $request->ket_barang,
                'foto_barang' => $fotoBarang,
            ]);

            /*
            |--------------------------------------------------
            | Hapus seluruh variasi lama
            |--------------------------------------------------
            */
            BarangVariasi::where('barang_id', $barang->id)
                ->delete();

            /*
            |--------------------------------------------------
            | Simpan variasi baru
            |--------------------------------------------------
            */
            if ($request->filled('ukuran')) {

                foreach ($request->ukuran as $key => $ukuran) {

                    BarangVariasi::create([
                        'barang_id' => $barang->id,
                        'ukuran' => $ukuran,
                        'warna' => $request->warna[$key],
                        'harga' => $request->harga[$key],
                        'stok' => $request->stok[$key],
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin-barang.index')
                ->with('success', 'Selamat! Anda berhasil memperbaharui data barang.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', $e->getMessage());
        }
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
