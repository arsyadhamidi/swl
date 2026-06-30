@extends('landing.layout.master')

@section('content')
    <section class="section py-5">
        <div class="container">

            <div class="row g-4">

                {{-- FOTO PRODUK --}}
                <div class="col-lg-5">

                    <div class="card border-0 shadow-sm">

                        <img src="{{ asset('storage/' . $barangs->foto_barang) }}"
                             class="img-fluid rounded"
                             alt="{{ $barangs->nm_barang }}"
                             style="width:100%;height:500px;object-fit:cover;">

                    </div>

                </div>

                {{-- DETAIL PRODUK --}}
                <div class="col-lg-7">

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">

                            <h2 class="fw-bold">
                                {{ $barangs->nm_barang }}
                            </h2>

                            <p class="text-muted mb-2">
                                Kategori :
                                <strong>{{ $barangs->nm_kategori }}</strong>
                            </p>

                            <h3 class="text-danger fw-bold mb-3"
                                id="hargaBarang">
                                Pilih Variasi
                            </h3>

                            <p>
                                Stok :
                                <span class="badge bg-success"
                                      id="stokBarang">
                                    -
                                </span>
                            </p>

                            <hr>

                            <p>
                                {{ $barangs->ket_barang ?? 'Tidak ada deskripsi produk.' }}
                            </p>

                            <hr>

                            <form action="{{ route('pelanggan-barang.storebarang') }}"
                                  method="POST"
                                  enctype="multipart/form-data">

                                @csrf

                                <input type="hidden"
                                       name="barang_id"
                                       value="{{ $barangs->id }}">

                                <input type="hidden"
                                       name="barang_variasi_id"
                                       id="barang_variasi_id">

                                {{-- VARIASI --}}
                                <div class="mb-4">

                                    <label class="fw-bold mb-2 d-block">
                                        Pilih Variasi
                                    </label>

                                    <div class="row g-3">

                                        @foreach ($variasis as $variasi)
                                            <div class="col-md-6">

                                                <div class="card variasi-card h-100"
                                                     data-id="{{ $variasi->id }}"
                                                     data-harga="{{ $variasi->harga }}"
                                                     data-stok="{{ $variasi->stok }}">

                                                    <div class="card-body">

                                                        <h6 class="fw-bold mb-2">
                                                            {{ $variasi->ukuran }}
                                                        </h6>

                                                        <p class="mb-1">
                                                            🎨 {{ $variasi->warna }}
                                                        </p>

                                                        <h6 class="text-danger mb-1">
                                                            Rp {{ number_format($variasi->harga, 0, ',', '.') }}
                                                        </h6>

                                                        <small class="text-success">
                                                            Stok :
                                                            {{ $variasi->stok }}
                                                        </small>

                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach

                                    </div>

                                </div>

                                {{-- JUMLAH --}}
                                <div class="mb-3">

                                    <label>Jumlah</label>

                                    <input type="number"
                                           name="jumlah"
                                           id="jumlahBarang"
                                           class="form-control"
                                           min="1"
                                           value="1"
                                           max="1"
                                           required>

                                </div>

                                {{-- CHECKOUT --}}
                                <div id="checkout-form"
                                     style="display:none;">

                                    <div class="mb-3">

                                        <label>No Telp</label>

                                        <input type="text"
                                               name="telp"
                                               class="form-control @error('telp') is-invalid @enderror"
                                               value="{{ old('telp', Auth::user()->telp ?? '0') }}">

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
                                                  rows="3">{{ old('alamat_pengiriman', Auth::user()->alamat ?? '-') }}</textarea>

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

                                <div class="d-flex gap-2 mt-4">

                                    <button type="submit"
                                            name="action"
                                            value="cart"
                                            class="btn btn-warning">

                                        🛒 Masukkan Keranjang

                                    </button>

                                    <button type="submit"
                                            name="action"
                                            value="checkout"
                                            class="btn btn-danger">

                                        ⚡ Pesan Sekarang

                                    </button>

                                    <a href="{{ url('/') }}"
                                       class="btn btn-outline-secondary">

                                        Kembali

                                    </a>

                                </div>

                            </form>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>
@endsection

@push('custom-script')
    <script>
        $('.variasi-card').click(function() {

            $('.variasi-card').removeClass('active');

            $(this).addClass('active');

            let id = $(this).data('id');
            let harga = $(this).data('harga');
            let stok = $(this).data('stok');

            $('#barang_variasi_id').val(id);

            $('#hargaBarang').text(
                "Rp " + Number(harga).toLocaleString('id-ID')
            );

            $('#stokBarang').text(stok);

            $('#jumlahBarang').attr('max', stok);

        });

        $('form').submit(function() {

            if ($('#barang_variasi_id').val() == '') {
                alert('Silakan pilih variasi terlebih dahulu');
                return false;
            }

        });
    </script>
    <script>
        let checkoutClicked = false;

        $('button[value="checkout"]').click(function(e) {

            if (!checkoutClicked) {

                e.preventDefault();

                $('#checkout-form').slideDown();

                checkoutClicked = true;
            }

        });

        $('button[value="cart"]').click(function() {

            $('#checkout-form').hide();

        });
    </script>
@endpush
