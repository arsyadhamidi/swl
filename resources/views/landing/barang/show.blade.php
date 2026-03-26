@extends('landing.layout.master')

@section('content')
    <section class="section">
        <div class="container">

            <div class="row">

                {{-- Foto Produk --}}
                <div class="col-md-5">

                    <div class="card product-card">

                        <img src="{{ asset('storage/' . $barangs->foto_barang) }}"
                             class="img-fluid"
                             alt="{{ $barangs->nm_barang }}">

                    </div>

                </div>

                {{-- Detail Produk --}}
                <div class="col-md-7">

                    <h2 class="mb-3">{{ $barangs->nm_barang }}</h2>

                    <p class="text-muted">
                        Kategori :
                        <b>{{ $barangs->nm_kategori ?? '-' }}</b>
                    </p>

                    <h3 class="text-danger mb-3">
                        Rp {{ number_format($barangs->harga, 0, ',', '.') }}
                    </h3>

                    <p>
                        Stok :
                        <span class="badge bg-success">
                            {{ $barangs->stok }}
                        </span>
                    </p>

                    <hr>

                    <p>
                        {{ $barangs->ket_barang ?? 'Tidak ada deskripsi produk.' }}
                    </p>

                    <hr>

                    {{-- Form Pesan --}}
                    <form action="{{ route('pelanggan-barang.storebarang') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <input type="hidden"
                               name="barang_id"
                               value="{{ $barangs->id }}">

                        <div class="row">

                            <div class="col-md-4">

                                <label>Jumlah</label>

                                <input type="number"
                                       name="jumlah"
                                       class="form-control"
                                       min="1"
                                       max="{{ $barangs->stok ?? '1' }}"
                                       value="1"
                                       required>

                            </div>

                        </div>

                        {{-- FORM CHECKOUT --}}
                        <div id="checkout-form"
                             style="display:none;"
                             class="mt-4">

                            <div class="mb-3">
                                <label>No Telp</label>
                                <input type="text"
                                       name="telp"
                                       class="form-control @error('telp') is-invalid @enderror"
                                       placeholder="Contoh: 08123456789" value="{{ old('telp') }}">
                                @error('telp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Alamat Pengiriman</label>
                                <textarea name="alamat_pengiriman"
                                          class="form-control @error('alamat_pengiriman') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Masukkan alamat lengkap">{{ old('alamat_pengiriman') }}</textarea>
                                @error('alamat_pengiriman')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Bukti Pembayaran</label>
                                <input type="file"
                                       name="bukti_pembayaran"
                                       class="form-control @error('bukti_pembayaran') is-invalid @enderror">
                                @error('bukti_pembayaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4 d-flex gap-2">

                            {{-- Tombol Keranjang --}}
                            <button type="submit"
                                    name="action"
                                    value="cart"
                                    class="btn btn-warning px-4">
                                🛒 Masukkan Keranjang
                            </button>

                            {{-- Tombol Pesan Sekarang --}}
                            <button type="submit"
                                    name="action"
                                    value="checkout"
                                    class="btn btn-danger px-4">
                                ⚡ Pesan Sekarang
                            </button>

                            <a href="{{ url('/') }}"
                               class="btn btn-outline-secondary px-4">
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </section>
@endsection
@push('custom-script')
    <script>
        let checkoutClicked = false;

        document.querySelector('button[value="checkout"]').addEventListener('click', function(e) {

            if (!checkoutClicked) {

                e.preventDefault(); // hentikan submit

                document.getElementById('checkout-form').style.display = 'block';

                checkoutClicked = true;

            }

        });

        document.querySelector('button[value="cart"]').addEventListener('click', function() {

            document.getElementById('checkout-form').style.display = 'none';

        });
    </script>
@endpush
