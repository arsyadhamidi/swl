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

            $length = $request->input('length', 10);
            $start = $request->input('start', 0);
            $draw = $request->input('draw');

            $search = $request->input('search.value');

            $query = Pesanan::join('users', 'pesanans.users_id', '=', 'users.id')
                ->leftJoin('ongkirs', 'pesanans.ongkir_id', '=', 'ongkirs.id')
                ->select(
                    'pesanans.*',
                    'users.name',
                    'users.email',
                    'users.telp',
                    'ongkirs.kota',
                    'ongkirs.biaya'
                );

            // Total seluruh data
            $recordsTotal = (clone $query)->count();

            // Filter pencarian
            if (! empty($search)) {

                $query->where(function ($q) use ($search) {

                    $q->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%")
                        ->orWhere('users.telp', 'like', "%{$search}%")
                        ->orWhere('ongkirs.kota', 'like', "%{$search}%");

                });

            }

            // Filter tanggal
            if ($request->filled('start_date') && $request->filled('end_date')) {

                $query->whereBetween('pesanans.tgl_pesanan', [
                    $request->start_date.' 00:00:00',
                    $request->end_date.' 23:59:59',
                ]);

            }

            // Total setelah filter
            $recordsFiltered = (clone $query)->count();

            // Paging
            $data = $query
                ->orderByDesc('pesanans.id')
                ->offset($start)
                ->limit($length)
                ->get();

            foreach ($data as $item) {

                $buktiUrl = asset('storage/'.$item->bukti_pembayaran);

                $receiptUrl = route(
                    'admin-pesanan.detailpesananpdf',
                    $item->id
                );

                $item->aksi = '
                <a href="'.$buktiUrl.'" target="_blank" class="btn btn-outline-warning">
                    <i class="fas fa-image"></i>
                </a>

                <button
                    class="btn btn-outline-primary btn-detail mx-1"
                    data-id="'.$item->id.'">
                    <i class="fas fa-edit"></i>
                </button>

                <a href="'.$receiptUrl.'" target="_blank" class="btn btn-outline-danger">
                    <i class="fas fa-receipt"></i>
                </a>
            ';
            }

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
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

        if ($request->filled('start_date') && $request->filled('end_date')) {

            $query->whereBetween('pesanans.tgl_pesanan', [
                $request->start_date.' 00:00:00',
                $request->end_date.' 23:59:59',
            ]);

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

        if ($request->filled('start_date') && $request->filled('end_date')) {

            $query->whereBetween('pesanans.tgl_pesanan', [
                $request->start_date.' 00:00:00',
                $request->end_date.' 23:59:59',
            ]);

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
