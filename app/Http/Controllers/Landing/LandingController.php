<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangVariasi;
use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class LandingController extends Controller
{
    public function index()
    {
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

    public function settingpelanggan()
    {
        $users = Auth::user();

        return view('landing.setting.akun.index', [
            'users' => $users,
        ]);
    }

    public function updateprofil(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120',
            'telp' => 'required|max:20',
        ], [
            'name.required' => 'Nama Lengkap wajib diisi',
            'name.max' => 'Nama Lengkap maksimal 120 karakter',
            'telp.required' => 'Nomor telepon wajib diisi',
            'telp.max' => 'Nomor Telepon maksimal 20 karakter',
        ]);

        $users = Auth::user();
        User::where('id', $users->id)->update([
            'name' => $request->name,
            'telp' => $request->telp,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui data profile!');
    }

    public function updateemail(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users,email',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah tersedia',
        ]);

        $users = Auth::user();
        User::where('id', $users->id)->update([
            'email' => $request->email,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui alamat email!');
    }

    public function updatepassword(Request $request)
    {
        $request->validate([
            'password' => 'required|max:30',
        ], [
            'password.required' => 'Password wajib diisi',
            'password.max' => 'Password maksimal 30 karakter',
        ]);

        $users = Auth::user();
        User::where('id', $users->id)->update([
            'password' => bcrypt($request->password),
            'duplicate' => $request->password,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui kata sandi!');
    }

    public function updategambar(Request $request)
    {
        $request->validate(
            [
                'foto_profile' => 'required|max:10248',
            ],
            [
                'foto_profile.required' => 'Foto profil wajib diunggah.',
                'foto_profile.max' => 'Ukuran foto profil maksimal 10 MB.',
            ]
        );

        $users = Auth::user();

        $fotoProfile = null;
        if ($request->file('foto_profile')) {
            $fotoProfile = $request->file('foto_profile')->store('foto_profile');
        }

        User::where('id', $users->id)->update([
            'foto_profile' => $fotoProfile,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui foto profile');
    }

    public function hapusgambar()
    {
        $users = Auth::user();

        if ($users->foto_profile) {
            Storage::delete($users->foto_profile);
        }

        User::where('id', $users->id)->update([
            'foto_profile' => null,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil menghapus foto profile');
    }

    public function showbarang($id)
    {
        $barangs = Barang::join(
            'kategoris',
            'barangs.kategori_id',
            '=',
            'kategoris.id'
        )
            ->select(
                'barangs.*',
                'kategoris.nm_kategori'
            )
            ->where('barangs.id', $id)
            ->firstOrFail();

        $variasis = BarangVariasi::where(
            'barang_id',
            $id
        )->get();

        return view('landing.barang.show', [
            'barangs' => $barangs,
            'variasis' => $variasis,
        ]);
    }

    public function storebarang(Request $request)
    {
        $request->validate([
            'barang_id' => 'required',
            'barang_variasi_id' => 'required',
            'jumlah' => 'required|integer|min:1',
        ], [
            'barang_variasi_id.required' => 'Silahkan pilih variasi produk terlebih dahulu.',
            'jumlah.required' => 'Jumlah pembelian wajib diisi.',
            'jumlah.min' => 'Jumlah pembelian minimal 1.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();
        $action = $request->action;

        $barangs = Barang::where(
            'id',
            $request->barang_id
        )->firstOrFail();

        $variasis = BarangVariasi::where(
            'id',
            $request->barang_variasi_id
        )->firstOrFail();

        // cek stok variasi
        if ($variasis->stok < $request->jumlah) {

            return back()->with(
                'error',
                'Maaf! Stok produk tidak mencukupi.'
            );

        }

        $harga = $variasis->harga;

        $totHarga = $harga * $request->jumlah;

        $buktiPembayaran = null;

        if ($request->hasFile('bukti_pembayaran')) {

            $buktiPembayaran = $request
                ->file('bukti_pembayaran')
                ->store('bukti_pembayaran');

        }

        /**
         * CHECKOUT LANGSUNG
         */
        if ($action == 'checkout') {

            $request->validate([
                'telp' => 'required|max:20',
                'alamat_pengiriman' => 'required',
                'bukti_pembayaran' => 'required',
            ], [
                'telp.required' => 'Nomor telepon wajib diisi.',
                'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi.',
                'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload.',
            ]);

            $pesanans = Pesanan::create([
                'users_id' => $users->id,
                'tgl_pesanan' => $carbons,
                'tot_harga' => $totHarga,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'telp' => $request->telp,
                'bukti_pembayaran' => $buktiPembayaran,
                'status' => 'Pending',
            ]);

            DetailPesanan::create([
                'pesanan_id' => $pesanans->id,
                'barang_variasi_id' => $variasis->id,
                'jumlah' => $request->jumlah,
                'harga' => $harga,
                'subtotal' => $totHarga,
            ]);

            // kurangi stok variasi
            $variasis->decrement(
                'stok',
                $request->jumlah
            );

            return redirect()
                ->route('pelanggan-barang.suksescheckout')
                ->with(
                    'success',
                    'Selamat! Anda berhasil melakukan pemesanan.'
                );
        }

        /**
         * MASUKKAN KE KERANJANG
         */
        $keranjangs = Keranjang::firstOrCreate(
            [
                'users_id' => $users->id,
            ],
            [
                'tanggal' => $carbons,
            ]
        );

        $detailKeranjang = KeranjangDetail::where(
            'keranjang_id',
            $keranjangs->id
        )
            ->where(
                'barang_variasi_id',
                $variasis->id
            )
            ->first();

        if ($detailKeranjang) {

            $jumlahBaru =
                $detailKeranjang->jumlah +
                $request->jumlah;

            if ($jumlahBaru > $variasis->stok) {

                return back()->with(
                    'error',
                    'Jumlah barang di keranjang melebihi stok tersedia.'
                );

            }

            $detailKeranjang->update([
                'jumlah' => $jumlahBaru,
                'subtotal' => $jumlahBaru * $detailKeranjang->harga,
            ]);

        } else {

            KeranjangDetail::create([
                'keranjang_id' => $keranjangs->id,
                'barang_variasi_id' => $variasis->id,
                'jumlah' => $request->jumlah,
                'harga' => $harga,
                'subtotal' => $totHarga,
            ]);

        }

        return redirect()
            ->route('landing.produk')
            ->with(
                'success',
                'Produk berhasil dimasukkan ke keranjang.'
            );
    }

    public function pesanan()
    {
        $users = Auth::user();

        $pesanans = DetailPesanan::join(
            'pesanans',
            'detail_pesanans.pesanan_id',
            '=',
            'pesanans.id'
        )
            ->join(
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
                'barang_variasis.warna',
                'pesanans.tgl_pesanan',
                'pesanans.status'
            )
            ->where('pesanans.users_id', $users->id)
            ->orderByDesc('detail_pesanans.id')
            ->get();

        return view('landing.setting.pesanan.index', [
            'users' => $users,
            'pesanans' => $pesanans,
        ]);
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
            'landing.setting.pesanan.detail-pdf',
            [
                'pesanans' => $pesanans,
                'detailPesanans' => $detailPesanans,
            ]
        );

        return $pdf->stream('detail-pesanan.pdf');
    }

    public function keranjang()
    {
        $users = Auth::user();

        $keranjangs = Keranjang::where(
            'users_id',
            $users->id
        )->first();

        if (! $keranjangs) {

            return view(
                'landing.keranjang.index',
                [
                    'keranjangs' => null,
                    'detailKeranjangs' => collect([]),
                ]
            );
        }

        $detailKeranjangs = KeranjangDetail::join(
            'barang_variasis',
            'keranjang_details.barang_variasi_id',
            '=',
            'barang_variasis.id'
        )
            ->join(
                'barangs',
                'barang_variasis.barang_id',
                '=',
                'barangs.id'
            )
            ->select([
                'keranjang_details.*',

                'barangs.nm_barang',
                'barangs.foto_barang',

                'barang_variasis.ukuran',
                'barang_variasis.warna',
            ])
            ->where(
                'keranjang_details.keranjang_id',
                $keranjangs->id
            )
            ->orderByDesc(
                'keranjang_details.id'
            )
            ->get();

        return view(
            'landing.keranjang.index',
            compact(
                'keranjangs',
                'detailKeranjangs'
            )
        );
    }

    public function suksescheckout()
    {
        return view('landing.sukses.index');
    }

    public function produk(Request $request)
    {
        $search = $request->search;

        $barangs = Barang::leftJoin(
            'barang_variasis',
            'barangs.id',
            '=',
            'barang_variasis.barang_id'
        )
            ->when($search, function ($query) use ($search) {
                $query->where('barangs.nm_barang', 'like', '%'.$search.'%');
            })
            ->selectRaw('
            barangs.*,
            COUNT(barang_variasis.id) as total_variasi,
            COALESCE(SUM(barang_variasis.stok),0) as total_stok,
            MIN(barang_variasis.harga) as harga_min,
            MAX(barang_variasis.harga) as harga_max
        ')
            ->groupBy(
                'barangs.id',
                'barangs.kategori_id',
                'barangs.nm_barang',
                'barangs.foto_barang',
                'barangs.ket_barang',
                'barangs.created_at',
                'barangs.updated_at'
            )
            ->orderByDesc('barangs.id')
            ->get();

        return view('landing.barang.index', [
            'barangs' => $barangs,
            'search' => $search,
        ]);
    }

    public function destroydetailkeranjang($id)
    {
        KeranjangDetail::where('id', $id)->delete();

        return back()->with('success', 'Selamat ! Anda berhasil menghapus data keranjang, silahkan pesan produk lainnya!');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|mimes:png,jpg,jpeg|max:10248',
            'telp' => 'required|max:20|regex:/^[0-9+]+$/',
            'alamat_pengiriman' => 'required',
            'tot_harga' => 'required|numeric',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.mimes' => 'Bukti pembayaran harus berupa file png, jpg, atau jpeg.',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 10 MB.',

            'telp.required' => 'Nomor telepon wajib diisi.',
            'telp.max' => 'Nomor telepon maksimal 20 karakter.',
            'telp.regex' => 'Nomor telepon hanya boleh berisi angka dan tanda +.',

            'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi.',

            'tot_harga.required' => 'Total harga tidak boleh kosong.',
            'tot_harga.numeric' => 'Total harga tidak valid.',
        ]);

        DB::beginTransaction();

        try {

            $users = Auth::user();

            $keranjangs = Keranjang::where(
                'users_id',
                $users->id
            )->first();

            if (! $keranjangs) {

                throw new \Exception(
                    'Keranjang kosong, silahkan pilih barang terlebih dahulu!'
                );
            }

            $detailKeranjangs = KeranjangDetail::where(
                'keranjang_id',
                $keranjangs->id
            )->get();

            if ($detailKeranjangs->isEmpty()) {

                throw new \Exception(
                    'Keranjang kosong, silahkan pilih barang terlebih dahulu!'
                );
            }

            // Upload bukti pembayaran
            $buktiPembayaran = null;

            if ($request->hasFile('bukti_pembayaran')) {

                $buktiPembayaran = $request
                    ->file('bukti_pembayaran')
                    ->store('bukti_pembayaran', 'public');
            }

            // Buat pesanan
            $pesanan = Pesanan::create([
                'users_id' => $users->id,
                'tgl_pesanan' => now(),
                'tot_harga' => $request->tot_harga,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'telp' => $request->telp,
                'bukti_pembayaran' => $buktiPembayaran,
                'status' => 'Pending',
            ]);

            foreach ($detailKeranjangs as $details) {

                $variasi = BarangVariasi::where(
                    'id',
                    $details->barang_variasi_id
                )->first();

                if (! $variasi) {

                    throw new \Exception(
                        'Variasi produk tidak ditemukan.'
                    );
                }

                // Cek stok
                if ($variasi->stok < $details->jumlah) {

                    throw new \Exception(
                        'Stok produk ukuran '.
                        $variasi->ukuran.
                        ' warna '.
                        $variasi->warna.
                        ' tidak mencukupi.'
                    );
                }

                // Simpan detail pesanan
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'barang_variasi_id' => $details->barang_variasi_id,
                    'jumlah' => $details->jumlah,
                    'harga' => $details->harga,
                    'subtotal' => $details->subtotal,
                ]);

                // Kurangi stok variasi
                $variasi->decrement(
                    'stok',
                    $details->jumlah
                );
            }

            // Hapus detail keranjang
            KeranjangDetail::where(
                'keranjang_id',
                $keranjangs->id
            )->delete();

            // Hapus keranjang
            $keranjangs->delete();

            DB::commit();

            return redirect()
                ->route('pelanggan-barang.suksescheckout')
                ->with(
                    'success',
                    'Selamat! Anda berhasil melakukan checkout.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}
