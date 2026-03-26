<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class LandingController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('id', 'desc')->get();
        $barangs = Barang::orderBy('id', 'desc')->get();
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
                'foto_profile.max'      => 'Ukuran foto profil maksimal 10 MB.',
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
        $barangs = Barang::join('kategoris', 'barangs.kategori_id', 'kategoris.id')
            ->select([
                'barangs.*',
                'kategoris.nm_kategori',
            ])
            ->where('barangs.id', $id)
            ->first();

        return view('landing.barang.show', [
            'barangs' => $barangs,
        ]);
    }

    public function storebarang(Request $request)
    {

        $carbons = Carbon::now();
        $users = Auth::user();
        $action = $request->action;
        $buktiPembayaran = null;
        $barangs = Barang::where('id', $request->barang_id)->firstOrFail();
        $stokBarangs = $barangs->stok;

        if (!$barangs) {
            return back()->with('error', 'Maaf ! Data barang tidak ditemukan!');
        }

        if ($stokBarangs < $request->jumlah) {
            return back()->with('error', 'Maaf ! Stok barang tidak mencukupi');
        }

        if ($request->file('bukti_pembayaran')) {
            $buktiPembayaran = $request->file('bukti_pembayaran')->store('bukti_pembayaran');
        }

        $totHarga = $barangs->harga * $request->jumlah;

        if ($action == 'checkout') {
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
                'barang_id' => $barangs->id,
                'jumlah' => $request->jumlah,
                'harga' => $barangs->harga,
                'subtotal' => $totHarga,
            ]);

            // KURANGI STOK
            $barangs->stok -= $request->jumlah;
            $barangs->save();

            return redirect()->route('pelanggan-barang.suksescheckout')->with('success', 'Selamat ! Anda berhasil melakukan pemesanan!');
        } else {

            $keranjangs = Keranjang::firstOrCreate([
                'users_id' => $users->id
            ], [
                'tanggal' => $carbons
            ]);

            KeranjangDetail::create([
                'keranjang_id' => $keranjangs->id,
                'barang_id' => $barangs->id,
                'jumlah' => $request->jumlah,
                'harga' => $barangs->harga,
                'subtotal' => $totHarga,
            ]);

            return redirect()->route('landing.produk')->with('success', 'Selamat ! Anda berhasil memasukan keranjang!');
        }
    }

    public function pesanan()
    {
        $users = Auth::user();
        $pesanans = Pesanan::where('users_id', $users->id)->orderBy('id', 'desc')->get();
        return view('landing.setting.pesanan.index', [
            'users' => $users,
            'pesanans' => $pesanans,
        ]);
    }

    public function detailpesananpdf($id)
    {
        $pesanans = Pesanan::join('users', 'pesanans.users_id', 'users.id')
            ->select([
                'pesanans.*',
                'users.name',
            ])
            ->where('pesanans.id', $id)->firstOrFail();
        $detailPesanans = DetailPesanan::join('barangs', 'detail_pesanans.barang_id', 'barangs.id')
            ->select([
                'detail_pesanans.*',
                'barangs.nm_barang',
            ])
            ->where('detail_pesanans.pesanan_id', $id)->orderBy('detail_pesanans.id', 'desc')->get();

        $pdf = PDF::loadview('landing.setting.pesanan.detail-pdf', [
            'pesanans' => $pesanans,
            'detailPesanans' => $detailPesanans,
        ]);
        // return $pdf->download('detail-pesanan.pdf');
        return $pdf->stream('detail-pesanan.pdf');
    }

    public function keranjang()
    {
        $users = Auth::user();

        $keranjangs = Keranjang::where('users_id', $users->id)->first();

        if (!$keranjangs) {
            return view('landing.keranjang.index', [
                'keranjangs' => null,
                'detailKeranjangs' => collect([])
            ]);
        }

        $detailKeranjangs = KeranjangDetail::join('barangs', 'keranjang_details.barang_id', '=', 'barangs.id')
            ->select([
                'keranjang_details.*',
                'barangs.nm_barang',
                'barangs.harga'
            ])
            ->where('keranjang_details.keranjang_id', $keranjangs->id)
            ->orderBy('keranjang_details.id', 'desc')
            ->get();

        return view('landing.keranjang.index', compact('keranjangs', 'detailKeranjangs'));
    }

    public function suksescheckout()
    {
        return view('landing.sukses.index');
    }

    public function produk(Request $request)
    {
        $search = $request->search;

        $barangs = Barang::when($search, function ($query) use ($search) {
            $query->where('nm_barang', 'like', '%' . $search . '%');
        })
            ->orderBy('id', 'desc')
            ->get();

        return view('landing.barang.index', [
            'barangs' => $barangs,
            'search' => $search
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
            'telp' => 'required|max:20',
            'alamat_pengiriman' => 'required',
            'tot_harga' => 'required',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.mimes' => 'Bukti pembayaran harus berupa file: png, jpg, atau jpeg.',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 10MB.',

            'telp.required' => 'Nomor telepon wajib diisi.',
            'telp.max' => 'Nomor telepon maksimal 20 karakter.',

            'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi.',

            'tot_harga.required' => 'Total harga tidak boleh kosong.',
        ]);

        $carbons = Carbon::now();
        $users = Auth::user();
        $totHarga = str_replace(['Rp', '.', ' '], '', $request->tot_harga);

        // Ambil keranjang
        $keranjangs = Keranjang::where('users_id', $users->id)->first();
        if (!$keranjangs) {
            return back()->with('error', 'Keranjang kosong, silahkan pilih barang terlebih dahulu!');
        }

        $detailKeranjangs = KeranjangDetail::where('keranjang_id', $keranjangs->id)->get();
        if ($detailKeranjangs->isEmpty()) {
            return back()->with('error', 'Keranjang kosong, silahkan pilih barang terlebih dahulu!');
        }

        // Upload bukti pembayaran jika ada
        $buktiPembayaran = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $buktiPembayaran = $file->store('bukti_pembayaran', 'public');
        }

        // Buat pesanan
        $pesanan = Pesanan::create([
            'users_id' => $users->id,
            'tgl_pesanan' => $carbons,
            'tot_harga' => $totHarga,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'telp' => $request->telp,
            'bukti_pembayaran' => $buktiPembayaran,
            'status' => 'Pending',
        ]);

        // Simpan detail pesanan & kurangi stok
        foreach ($detailKeranjangs as $details) {
            $barang = Barang::findOrFail($details->barang_id);

            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'barang_id' => $barang->id,
                'jumlah' => $details->jumlah,          // gunakan jumlah di detail
                'harga' => $barang->harga,
                'subtotal' => $barang->harga * $details->jumlah,
            ]);

            // Kurangi stok barang
            $barang->decrement('stok', $details->jumlah);
        }

        KeranjangDetail::where('keranjang_id', $keranjangs->id)->delete();

        // Hapus keranjang
        Keranjang::where('users_id', $users->id)->delete();

        return redirect()->route('pelanggan-barang.suksescheckout')
            ->with('success', 'Selamat! Anda berhasil melakukan checkout.');
    }
}
