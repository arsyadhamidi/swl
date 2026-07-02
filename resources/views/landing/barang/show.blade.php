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
                                {!! $barangs->ket_barang ?? 'Tidak ada deskripsi produk.' !!}
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

                                    <label class="fw-bold mb-3 d-block">
                                        Pilih Variasi
                                    </label>

                                    <div class="row g-3">

                                        @foreach ($variasis as $variasi)
                                            <div class="col-lg-6">

                                                <div class="card variasi-card shadow-sm border"
                                                     data-id="{{ $variasi->id }}"
                                                     data-harga="{{ $variasi->harga }}"
                                                     data-stok="{{ $variasi->stok }}">

                                                    <div class="card-body">

                                                        <div class="d-flex justify-content-between align-items-center mb-2">

                                                            <h5 class="fw-bold text-primary mb-0">
                                                                Ukuran {{ $variasi->ukuran }}
                                                            </h5>

                                                            <span class="badge bg-success">
                                                                Stok {{ $variasi->stok }}
                                                            </span>

                                                        </div>

                                                        <table class="table table-sm table-borderless mb-2">

                                                            <tr>
                                                                <td width="45%">
                                                                    Lingkar Dada
                                                                </td>

                                                                <td>
                                                                    :
                                                                </td>

                                                                <td>
                                                                    {{ $variasi->lingkar_dada }} cm
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    Panjang Baju
                                                                </td>

                                                                <td>
                                                                    :
                                                                </td>

                                                                <td>
                                                                    {{ $variasi->panjang_baju }} cm
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    Panjang Lengan
                                                                </td>

                                                                <td>
                                                                    :
                                                                </td>

                                                                <td>
                                                                    {{ $variasi->panjang_lengan ?? '-' }} cm
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    Warna
                                                                </td>

                                                                <td>
                                                                    :
                                                                </td>

                                                                <td>
                                                                    {{ $variasi->warna }}
                                                                </td>
                                                            </tr>

                                                        </table>

                                                        <hr>

                                                        <h5 class="text-danger fw-bold mb-0">

                                                            Rp {{ number_format($variasi->harga, 0, ',', '.') }}

                                                        </h5>

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

                                        <label>Kota Tujuan</label>

                                        <select name="ongkir_id"
                                                id="selectedOngkir"
                                                class="form-control @error('ongkir_id') is-invalid @enderror">

                                            <option value="">
                                                Pilih Kota
                                            </option>

                                            @foreach ($ongkirs as $ongkir)
                                                <option value="{{ $ongkir->id }}"
                                                        data-biaya="{{ $ongkir->biaya }}">

                                                    {{ $ongkir->kota }}

                                                </option>
                                            @endforeach

                                        </select>

                                        @error('ongkir_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    <div class="card bg-light mb-3">

                                        <div class="card-body">

                                            <table class="table table-borderless mb-0">

                                                <tr>

                                                    <td>Harga Barang</td>

                                                    <td class="text-end">

                                                        <span id="hargaCheckout">

                                                            Rp 0

                                                        </span>

                                                    </td>

                                                </tr>

                                                <tr>

                                                    <td>Ongkir</td>

                                                    <td class="text-end">

                                                        <span id="ongkirCheckout">

                                                            Rp 0

                                                        </span>

                                                    </td>

                                                </tr>

                                                <tr class="fw-bold">

                                                    <td>Total Bayar</td>

                                                    <td class="text-end text-danger">

                                                        <span id="totalCheckout">

                                                            Rp 0

                                                        </span>

                                                    </td>

                                                </tr>

                                            </table>

                                        </div>

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

                                    {{-- REKENING TUJUAN --}}
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <i class="fas fa-university me-2"></i>
                                            Rekening Tujuan Pembayaran
                                        </div>

                                        <div class="card-body">

                                            <div class="row align-items-center">

                                                <div class="col-md-8">
                                                    <h5 class="fw-bold mb-1">Bank BRI</h5>

                                                    <p class="mb-1">
                                                        <strong>No. Rekening</strong>
                                                    </p>

                                                    <h4 class="text-danger fw-bold mb-2"
                                                        id="nomorRekening">
                                                        533501015678533
                                                    </h4>

                                                    <p class="mb-0">
                                                        A.N <strong>Sri Wahyuni</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                                    <button type="button"
                                                            class="btn btn-outline-primary"
                                                            id="copyRekening">
                                                        <i class="fas fa-copy"></i>
                                                        Salin Rekening
                                                    </button>
                                                </div>

                                            </div>

                                            <hr>

                                            <small class="text-muted">
                                                Silakan transfer sesuai dengan total pembayaran, kemudian upload bukti transfer di bawah ini.
                                            </small>

                                        </div>
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
        $('#jumlahBarang').on('keyup change', function() {

            hitungTotal();

        });

        $('#copyRekening').click(function() {

            const rekening = '533501015678533';

            navigator.clipboard.writeText(rekening).then(() => {

                $(this).html('<i class="fas fa-check"></i> Berhasil Disalin');

                setTimeout(() => {

                    $(this).html('<i class="fas fa-copy"></i> Salin Rekening');

                }, 2000);

            });

        });
    </script>
    <script>
        let hargaBarang = 0;

        $('.variasi-card').click(function() {

            hargaBarang = Number($(this).data('harga'));

            $('#hargaCheckout').text(
                "Rp " + hargaBarang.toLocaleString('id-ID')
            );

            hitungTotal();

        });

        $('#selectedOngkir').change(function() {

            hitungTotal();

        });

        function hitungTotal() {

            let ongkir = Number(
                $('#selectedOngkir option:selected').data('biaya')
            ) || 0;

            $('#ongkirCheckout').text(
                "Rp " + ongkir.toLocaleString('id-ID')
            );

            let qty = Number($('#jumlahBarang').val());

            let total = (hargaBarang * qty) + ongkir;

            $('#totalCheckout').text(
                "Rp " + total.toLocaleString('id-ID')
            );

        }
    </script>
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
