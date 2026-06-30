@extends('landing.layout.master')

@section('content')
    <section class="section py-5">
        <div class="container">
            <div class="row">

                {{-- KERANJANG --}}
                <div class="col-lg-8 mb-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">
                                <i class="fas fa-shopping-cart"></i>
                                Keranjang Belanja
                            </h5>

                        </div>

                        <div class="card-body p-0">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th width="70"></th>
                                        <th>Produk</th>
                                        <th width="120">Harga</th>
                                        <th width="100">Qty</th>
                                        <th width="150">Subtotal</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @php
                                        $total = 0;
                                    @endphp

                                    @forelse($detailKeranjangs as $item)
                                        @php
                                            $total += $item->subtotal;
                                        @endphp

                                        <tr>

                                            <td>

                                                <form action="{{ route('pelanggan-barang.destroydetailkeranjang', $item->id) }}"
                                                      method="POST">

                                                    @csrf

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Apakah anda yakin menghapus barang ini ?')">

                                                        <i class="fas fa-trash"></i>

                                                    </button>

                                                </form>

                                            </td>

                                            <td>

                                                <div class="d-flex">

                                                    <img src="{{ asset('storage/' . $item->foto_barang) }}"
                                                         width="90"
                                                         height="90"
                                                         class="rounded shadow-sm"
                                                         style="object-fit:cover;">

                                                    <div class="ms-3">

                                                        <h6 class="mb-1 fw-bold">

                                                            {{ $item->nm_barang }}

                                                        </h6>

                                                        <span class="badge bg-info">

                                                            {{ $item->ukuran }}

                                                        </span>

                                                        <span class="badge bg-secondary">

                                                            {{ $item->warna }}

                                                        </span>

                                                        <div class="text-success mt-2">

                                                            <strong>

                                                                Rp {{ number_format($item->harga, 0, ',', '.') }}

                                                            </strong>

                                                        </div>

                                                    </div>

                                                </div>

                                            </td>

                                            <td>

                                                Rp {{ number_format($item->harga, 0, ',', '.') }}

                                            </td>

                                            <td>

                                                <span class="badge bg-primary">

                                                    {{ $item->jumlah }}

                                                </span>

                                            </td>

                                            <td>

                                                <strong class="text-success">

                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}

                                                </strong>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="5"
                                                class="text-center p-5 text-muted">

                                                <i class="fas fa-shopping-cart fa-3x mb-3"></i>

                                                <br>

                                                Keranjang masih kosong

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                {{-- CHECKOUT --}}
                <div class="col-lg-4">

                    <div class="card shadow border-0">

                        <div class="card-header bg-primary text-white">

                            <h5 class="mb-0">
                                <i class="fas fa-receipt"></i>
                                Ringkasan Belanja
                            </h5>

                        </div>

                        <div class="card-body">

                            <form action="{{ route('pelanggan-barang.checkout') }}"
                                  method="POST"
                                  enctype="multipart/form-data">

                                @csrf

                                <div class="mb-3">

                                    <label class="fw-bold">
                                        Kota Tujuan
                                    </label>

                                    <select name="ongkir_id"
                                            id="selectedOngkir"
                                            class="form-control">

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

                                </div>

                                <div class="card bg-light mb-3">

                                    <div class="card-body">

                                        <table class="table table-borderless mb-0">

                                            <tr>

                                                <td>Total Barang</td>

                                                <td class="text-end">

                                                    <span id="subtotalBelanja">

                                                        Rp {{ number_format($total, 0, ',', '.') }}

                                                    </span>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>Ongkir</td>

                                                <td class="text-end">

                                                    <span id="ongkirBelanja">

                                                        Rp 0

                                                    </span>

                                                </td>

                                            </tr>

                                            <tr class="border-top">

                                                <td class="fw-bold">

                                                    Grand Total

                                                </td>

                                                <td class="text-end text-danger fw-bold">

                                                    <span id="grandTotal">

                                                        Rp {{ number_format($total, 0, ',', '.') }}

                                                    </span>

                                                </td>

                                            </tr>

                                        </table>

                                    </div>

                                </div>

                                <input type="hidden"
                                       name="tot_harga"
                                       value="{{ $total }}">

                                <input type="hidden"
                                       name="ongkir"
                                       id="ongkirInput">

                                <input type="hidden"
                                       name="grand_total"
                                       id="grandTotalInput">

                                <div class="mb-3">

                                    <label class="fw-bold">
                                        Alamat Pengiriman
                                    </label>

                                    <textarea name="alamat_pengiriman"
                                              rows="3"
                                              class="form-control">{{ old('alamat_pengiriman', Auth::user()->alamat) }}</textarea>

                                </div>

                                <div class="mb-3">

                                    <label class="fw-bold">

                                        Nomor Telepon

                                    </label>

                                    <input type="text"
                                           name="telp"
                                           class="form-control"
                                           value="{{ old('telp', Auth::user()->telp) }}">

                                </div>

                                <div class="mb-4">

                                    <label class="fw-bold">

                                        Bukti Pembayaran

                                    </label>

                                    <input type="file"
                                           name="bukti_pembayaran"
                                           class="form-control">

                                </div>

                                <div class="d-grid">

                                    <button class="btn btn-success btn-lg">

                                        <i class="fas fa-credit-card"></i>

                                        Checkout Sekarang

                                    </button>

                                </div>

                                <a href="{{ route('landing.produk') }}"
                                   class="btn btn-outline-primary w-100 mt-3">

                                    <i class="fas fa-shopping-bag"></i>

                                    Lanjut Belanja

                                </a>

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
        $('#selectedOngkir').change(function() {

            let ongkir = Number($(this).find(':selected').data('biaya')) || 0;

            let subtotal = {{ $total }};

            let grand = subtotal + ongkir;

            $('#ongkirBelanja').text(
                'Rp ' + ongkir.toLocaleString('id-ID')
            );

            $('#grandTotal').text(
                'Rp ' + grand.toLocaleString('id-ID')
            );

            $('#ongkirInput').val(ongkir);

            $('#grandTotalInput').val(grand);

        });
    </script>
@endpush
