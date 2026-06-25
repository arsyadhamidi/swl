<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PesananExport;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminPesananController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Pesanan::join('users', 'pesanans.users_id', 'users.id')
                ->select([
                    'pesanans.*',
                    'users.name',
                    'users.email',
                    'users.telp',
                ])
                ->orderBy('pesanans.id', 'desc');

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
                $query->whereBetween('pesanans.tgl_pesanan', [$start_date, $end_date]);
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $buktiUrl = asset('storage/'.$item->bukti_pembayaran);
                $receiptUrl = route('admin-pesanan.detailpesananpdf', $item->id);

                $item->aksi = '
                <a href="'.$buktiUrl.'" class="btn btn-outline-warning" target="_blank">
                    <i class="fas fa-image"></i>
                </a>
                <button type="button"
                        class="btn btn-outline-primary btn-detail mx-2"
                        data-id="'.e($resultid).'">
                    <i class="fas fa-edit"></i>
                </button>
                <a href="'.$receiptUrl.'" class="btn btn-outline-danger" target="_blank">
                    <i class="fas fa-receipt"></i>
                </a>
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

        return view('admin.pesanan.index');
    }

    public function detailpesananpdf($id)
    {
        $pesanans = Pesanan::join(
            'users',
            'pesanans.users_id',
            '=',
            'users.id'
        )
            ->select(
                'pesanans.*',
                'users.name'
            )
            ->where('pesanans.id', $id)
            ->firstOrFail();

        $detailPesanans = DetailPesanan::join(
            'barang_variasis',
            'detail_pesanans.barang_variasi_id',
            '=',
            'barang_variasis.id'
        )
            ->join(
                'barangs',
                'barang_variasis.barang_id',
                '=',
                'barangs.id'
            )
            ->select(
                'detail_pesanans.*',
                'barangs.nm_barang',
                'barang_variasis.ukuran',
                'barang_variasis.warna'
            )
            ->where('detail_pesanans.pesanan_id', $id)
            ->orderByDesc('detail_pesanans.id')
            ->get();

        $pdf = PDF::loadView(
            'admin.pesanan.detail-pdf',
            [
                'pesanans' => $pesanans,
                'detailPesanans' => $detailPesanans,
            ]
        );

        return $pdf->stream(
            'detail-pesanan-'.$pesanans->id.'.pdf'
        );
    }

    public function generatepdf(Request $request)
    {
        $query = Pesanan::join('users', 'pesanans.users_id', 'users.id')
            ->select([
                'pesanans.*',
                'users.name',
                'users.email',
                'users.telp',
            ]);

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('pesanans.tgl_pesanan', [$start_date, $end_date]);
        }

        $data = $query->orderBy('pesanans.id', 'desc')->get();

        $pdf = PDF::loadView('admin.pesanan.export-pdf', ['pesanans' => $data])
            ->setPaper('A4', 'landscape');

        return $pdf->stream(Carbon::now()->format('YmdHis').'-pesanan.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = Pesanan::join('users', 'pesanans.users_id', 'users.id')
            ->select([
                'pesanans.*',
                'users.name',
                'users.email',
                'users.telp',
            ]);

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('pesanans.tgl_pesanan', [$start_date, $end_date]);
        }

        $data = $query->orderBy('pesanans.id', 'desc')->get();

        return Excel::download(
            new PesananExport($data),
            Carbon::now()->format('YmdHis').'-pesanan.xlsx'
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        // Jika status dibatalkan
        if ($request->status == 'Dibatalkan') {

            $detailPesanans = DetailPesanan::where('pesanan_id', $pesanan->id)->get();

            foreach ($detailPesanans as $detail) {

                $barang = Barang::find($detail->barang_id);

                if ($barang) {
                    $barang->stok += $detail->jumlah; // kembalikan stok
                    $barang->save();
                }
            }
        }

        $pesanan->status = $request->status;
        $pesanan->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate',
        ]);
    }
}
