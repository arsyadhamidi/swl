<?php

use App\Http\Controllers\Admin\AdminBarangController;
use App\Http\Controllers\Admin\AdminKategoriController;
use App\Http\Controllers\Admin\AdminKeranjangController;
use App\Http\Controllers\Admin\AdminOngkirController;
use App\Http\Controllers\Admin\AdminPesananController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrasiController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Landing\LandingController;
use App\Http\Middleware\CekLevel;
use Illuminate\Support\Facades\Route;











/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/produk', [LandingController::class, 'produk'])->name('landing.produk');
Route::get('/kategori', [LandingController::class, 'kategori'])->name('landing.kategori');
Route::get('/kategori/show/{id}', [LandingController::class, 'showkategori'])->name('landing.showkategori');

// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/login/logout', [LoginController::class, 'logout'])->name('login.logout');
Route::post('/login/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');

// Registrasi
Route::get('/registrasi', [RegistrasiController::class, 'index'])->name('registrasi.index');
Route::post('/registrasi/store', [RegistrasiController::class, 'store'])->name('registrasi.store');

// Dashboard
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Setting
    Route::get('/setting/index', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/setting/updateprofil', [SettingController::class, 'updateprofil'])->name('setting.updateprofil');
    Route::post('/setting/updateemail', [SettingController::class, 'updateemail'])->name('setting.updateemail');
    Route::post('/setting/updatepassword', [SettingController::class, 'updatepassword'])->name('setting.updatepassword');
    Route::post('/setting/updategambar', [SettingController::class, 'updategambar'])->name('setting.updategambar');
    Route::post('/setting/hapusgambar', [SettingController::class, 'hapusgambar'])->name('setting.hapusgambar');

    // Admin
    Route::group(['middleware' => [CekLevel::class . ':1']], function () {

        // Pesanan
        Route::get('/admin-pesanan/index', [AdminPesananController::class, 'index'])->name('admin-pesanan.index');
        Route::get('/admin-pesanan/generatepdf', [AdminPesananController::class, 'generatepdf'])->name('admin-pesanan.generatepdf');
        Route::get('/admin-pesanan/generateexcel', [AdminPesananController::class, 'generateexcel'])->name('admin-pesanan.generateexcel');
        Route::get('/admin-pesanan/detailpesananpdf/{id}', [AdminPesananController::class, 'detailpesananpdf'])->name('admin-pesanan.detailpesananpdf');
        Route::post('/admin-pesanan/update/{id}', [AdminPesananController::class, 'update'])->name('admin-pesanan.update');

        // Keranjang
        Route::get('/admin-keranjang/index', [AdminKeranjangController::class, 'index'])->name('admin-keranjang.index');
        Route::get('/admin-keranjang/generatepdf', [AdminKeranjangController::class, 'generatepdf'])->name('admin-keranjang.generatepdf');
        Route::get('/admin-keranjang/generateexcel', [AdminKeranjangController::class, 'generateexcel'])->name('admin-keranjang.generateexcel');
        Route::get('/admin-keranjang/keranjangdetail/{id}', [AdminKeranjangController::class, 'keranjangdetail'])->name('admin-keranjang.keranjangdetail');

        // Barang
        Route::get('/admin-barang/index', [AdminBarangController::class, 'index'])->name('admin-barang.index');
        Route::get('/admin-barang/create', [AdminBarangController::class, 'create'])->name('admin-barang.create');
        Route::get('/admin-barang/generatepdf', [AdminBarangController::class, 'generatepdf'])->name('admin-barang.generatepdf');
        Route::get('/admin-barang/generateexcel', [AdminBarangController::class, 'generateexcel'])->name('admin-barang.generateexcel');
        Route::get('/admin-barang/edit/{id}', [AdminBarangController::class, 'edit'])->name('admin-barang.edit');
        Route::post('/admin-barang/store', [AdminBarangController::class, 'store'])->name('admin-barang.store');
        Route::post('/admin-barang/update/{id}', [AdminBarangController::class, 'update'])->name('admin-barang.update');
        Route::post('/admin-barang/destroy/{id}', [AdminBarangController::class, 'destroy'])->name('admin-barang.destroy');

        // Ongkir
        Route::get('/admin-ongkir/index', [AdminOngkirController::class, 'index'])->name('admin-ongkir.index');
        Route::get('/admin-ongkir/create', [AdminOngkirController::class, 'create'])->name('admin-ongkir.create');
        Route::get('/admin-ongkir/edit/{id}', [AdminOngkirController::class, 'edit'])->name('admin-ongkir.edit');
        Route::post('/admin-ongkir/store', [AdminOngkirController::class, 'store'])->name('admin-ongkir.store');
        Route::post('/admin-ongkir/update/{id}', [AdminOngkirController::class, 'update'])->name('admin-ongkir.update');
        Route::post('/admin-ongkir/destroy/{id}', [AdminOngkirController::class, 'destroy'])->name('admin-ongkir.destroy');

        // Kategori
        Route::get('/admin-kategori/index', [AdminKategoriController::class, 'index'])->name('admin-kategori.index');
        Route::get('/admin-kategori/generatepdf', [AdminKategoriController::class, 'generatepdf'])->name('admin-kategori.generatepdf');
        Route::get('/admin-kategori/generateexcel', [AdminKategoriController::class, 'generateexcel'])->name('admin-kategori.generateexcel');
        Route::get('/admin-kategori/create', [AdminKategoriController::class, 'create'])->name('admin-kategori.create');
        Route::get('/admin-kategori/edit/{id}', [AdminKategoriController::class, 'edit'])->name('admin-kategori.edit');
        Route::post('/admin-kategori/store', [AdminKategoriController::class, 'store'])->name('admin-kategori.store');
        Route::post('/admin-kategori/update/{id}', [AdminKategoriController::class, 'update'])->name('admin-kategori.update');
        Route::post('/admin-kategori/destroy/{id}', [AdminKategoriController::class, 'destroy'])->name('admin-kategori.destroy');

        // User
        Route::get('/admin-user/index', [AdminUserController::class, 'index'])->name('admin-user.index');
        Route::get('/admin-user/create', [AdminUserController::class, 'create'])->name('admin-user.create');
        Route::get('/admin-user/generatepdf', [AdminUserController::class, 'generatepdf'])->name('admin-user.generatepdf');
        Route::get('/admin-user/generateexcel', [AdminUserController::class, 'generateexcel'])->name('admin-user.generateexcel');
        Route::get('/admin-user/edit/{id}', [AdminUserController::class, 'edit'])->name('admin-user.edit');
        Route::post('/admin-user/store', [AdminUserController::class, 'store'])->name('admin-user.store');
        Route::post('/admin-user/update/{id}', [AdminUserController::class, 'update'])->name('admin-user.update');
        Route::post('/admin-user/destroy/{id}', [AdminUserController::class, 'destroy'])->name('admin-user.destroy');
    });

    // Pelanggan
    Route::group(['middleware' => [CekLevel::class . ':2']], function () {

        // Sukses
        Route::get('/pelanggan-barang/suksescheckout', [LandingController::class, 'suksescheckout'])->name('pelanggan-barang.suksescheckout');

        // Barang
        Route::get('/pelanggan-barang/showbarang/{id}', [LandingController::class, 'showbarang'])->name('pelanggan-barang.showbarang');
        Route::get('/pelanggan-barang/detailpesananpdf/{id}', [LandingController::class, 'detailpesananpdf'])->name('pelanggan-barang.detailpesananpdf');
        Route::get('/pelanggan-barang/settingpelanggan', [LandingController::class, 'settingpelanggan'])->name('pelanggan-barang.settingpelanggan');
        Route::get('/pelanggan-barang/pesanan', [LandingController::class, 'pesanan'])->name('pelanggan-barang.pesanan');
        Route::get('/pelanggan-barang/keranjang', [LandingController::class, 'keranjang'])->name('pelanggan-barang.keranjang');
        Route::post('/pelanggan-barang/storebarang', [LandingController::class, 'storebarang'])->name('pelanggan-barang.storebarang');
        Route::post('/pelanggan-barang/updateprofil', [LandingController::class, 'updateprofil'])->name('pelanggan-barang.updateprofil');
        Route::post('/pelanggan-barang/updateemail', [LandingController::class, 'updateemail'])->name('pelanggan-barang.updateemail');
        Route::post('/pelanggan-barang/updatepassword', [LandingController::class, 'updatepassword'])->name('pelanggan-barang.updatepassword');
        Route::post('/pelanggan-barang/updategambar', [LandingController::class, 'updategambar'])->name('pelanggan-barang.updategambar');
        Route::post('/pelanggan-barang/hapusgambar', [LandingController::class, 'hapusgambar'])->name('pelanggan-barang.hapusgambar');
        Route::post('/pelanggan-barang/destroydetailkeranjang/{id}', [LandingController::class, 'destroydetailkeranjang'])->name('pelanggan-barang.destroydetailkeranjang');
        Route::post('/pelanggan-barang/checkout', [LandingController::class, 'checkout'])->name('pelanggan-barang.checkout');
    });
});
