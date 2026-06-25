<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $auth = Auth::user();

        if ($auth->level_id == '1') {

            $users = User::count();
            $keranjangs = Keranjang::count();
            $pesanans = Pesanan::count();
            $barangs = Barang::count();

            // Pesanan per bulan
            $pesananPerBulan = Pesanan::select(
                DB::raw('MONTH(tgl_pesanan) as bulan'),
                DB::raw('COUNT(*) as total')
            )
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get()
                ->map(function ($item) {

                    $namaBulan = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ];

                    $item->bulan = $namaBulan[$item->bulan];

                    return $item;
                });

            // Kategori paling banyak dipesan
            $kategoriTerlaris = DB::table('detail_pesanans')
                ->join('barang_variasis', 'detail_pesanans.barang_variasi_id', '=', 'barang_variasis.id')
                ->join('barangs', 'barang_variasis.barang_id', '=', 'barangs.id')
                ->join('kategoris', 'barangs.kategori_id', '=', 'kategoris.id')
                ->select(
                    'kategoris.nm_kategori',
                    DB::raw('SUM(detail_pesanans.jumlah) as total')
                )
                ->groupBy('kategoris.nm_kategori')
                ->get();

            return view('dashboard.main.index', compact(
                'users',
                'keranjangs',
                'pesanans',
                'barangs',
                'pesananPerBulan',
                'kategoriTerlaris'
            ));
        } else {

            $kategoris = Kategori::orderBy('id', 'desc')->get();

            $barangs = Barang::leftJoin(
                'barang_variasis',
                'barangs.id',
                '=',
                'barang_variasis.barang_id'
            )
                ->selectRaw('
            barangs.*,
            COUNT(barang_variasis.id) as total_variasi,
            COALESCE(SUM(barang_variasis.stok),0) as total_stok,
            MIN(barang_variasis.harga) as harga_min,
            MAX(barang_variasis.harga) as harga_max
        ')
                ->groupBy(
                    'barangs.id',
                    'barangs.nm_barang',
                    'barangs.kategori_id',
                    'barangs.foto_barang',
                    'barangs.ket_barang',
                    'barangs.created_at',
                    'barangs.updated_at'
                )
                ->orderByDesc('barangs.id')
                ->get();

            return view('landing.main.index', [
                'barangs' => $barangs,
                'kategoris' => $kategoris,
            ]);
        }
    }
}
