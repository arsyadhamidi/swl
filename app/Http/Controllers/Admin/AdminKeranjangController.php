<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KeranjangExport;
use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminKeranjangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Keranjang::join('users', 'keranjangs.users_id', 'users.id')
                ->select([
                    'keranjangs.*',
                    'users.name',
                    'users.email',
                    'users.telp',
                ])
                ->orderBy('keranjangs.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('users.name', 'LIKE', "%{$search}%")
                        ->orWhere('users.telp', 'LIKE', "%{$search}%")
                        ->orWhere('users.email', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('keranjangs.tanggal', [$start_date, $end_date]);
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';

                $item->aksi = '
<button type="button"
        class="btn btn-outline-primary btn-detail"
        data-id="' . e($resultid) . '">
    <i class="fas fa-eye"></i>
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

        return view('admin.keranjang.index');
    }

    public function generatepdf(Request $request)
    {
        $query = Keranjang::join('users', 'keranjangs.users_id', 'users.id')
            ->select([
                'keranjangs.*',
                'users.name',
                'users.email',
                'users.telp',
            ]);

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('keranjangs.tanggal', [$start_date, $end_date]);
        }

        $data = $query->orderBy('id', 'desc')->get();

        $pdf = PDF::loadView('admin.keranjang.export-pdf', ['keranjangs' => $data])
            ->setPaper('A4', 'landscape');

        return $pdf->stream(Carbon::now()->format('YmdHis') . '-keranjag.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = Keranjang::join('users', 'keranjangs.users_id', 'users.id')
            ->select([
                'keranjangs.*',
                'users.name',
                'users.email',
                'users.telp',
            ])
            ->orderBy('keranjangs.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('keranjangs.tanggal', [$start_date, $end_date]);
        }

        $data = $query->orderBy('keranjangs.id', 'desc')->get();

        return Excel::download(
            new KeranjangExport($data),
            Carbon::now()->format('YmdHis') . '-keranjang.xlsx'
        );
    }

    public function keranjangdetail($id)
    {
        $details = KeranjangDetail::join('barangs', 'keranjang_details.barang_id', '=', 'barangs.id')
            ->select(
                'barangs.nm_barang',
                'keranjang_details.jumlah',
                'keranjang_details.harga',
                'keranjang_details.subtotal'
            )
            ->where('keranjang_details.keranjang_id', $id)
            ->get();

        return response()->json($details);
    }
}
