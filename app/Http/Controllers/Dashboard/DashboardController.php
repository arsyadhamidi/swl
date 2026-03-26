<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
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
                ->join('barangs', 'detail_pesanans.barang_id', '=', 'barangs.id')
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
            $barangs = Barang::orderBy('id', 'desc')->get();

            return view('landing.main.index', [
                'barangs' => $barangs,
                'kategoris' => $kategoris,
            ]);
        }
    }
}
