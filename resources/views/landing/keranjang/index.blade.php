@extends('landing.layout.master')

@section('content')
    <section class="section">
        <div class="container mt-5 mb-5">

            <div class="row">

                {{-- LIST PRODUK DI KERANJANG --}}
                <div class="col-lg-8">

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-shopping-cart"></i>
                                Keranjang Belanja
                            </h5>
                        </div>

                        <div class="card-body p-0">

                            <table class="table table-hover mb-0">

                                <thead class="table-light">
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Produk</th>
                                        <th width="120">Harga</th>
                                        <th width="100">Jumlah</th>
                                        <th width="150">Subtotal</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @php
                                        $total = 0;
                                    @endphp

                                    @forelse($detailKeranjangs as $item)
                                        @php
                                            $subtotal = $item->harga * $item->jumlah;
                                            $total += $subtotal;
                                        @endphp

                                        <tr>

                                            <td>
                                                <form action="{{ route('pelanggan-barang.destroydetailkeranjang', $item->id ?? '') }}"
                                                      method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-outline-danger"
                                                            onclick="return confirm('Apakah anda yakin menghapus barang ini ?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>

                                            <td>
                                                <strong>{{ $item->nm_barang }}</strong>
                                            </td>

                                            <td>
                                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                {{ $item->jumlah }}
                                            </td>

                                            <td>
                                                <strong class="text-success">
                                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                                </strong>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="5"
                                                class="text-center text-muted p-4">
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

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                Checkout
                            </h5>
                        </div>

                        <div class="card-body">

                            <form action="{{ route('pelanggan-barang.checkout') }}"
                                  method="POST"
                                  enctype="multipart/form-data">

                                @csrf

                                {{-- TOTAL --}}
                                <div class="mb-3">

                                    <label class="form-label">Total Pembayaran</label>

                                    <input type="text"
                                           name="tot_harga"
                                           class="form-control fw-bold text-success @error('tot_harga') is-invalid @enderror"
                                           value="Rp {{ number_format($total, 0, ',', '.') }}"
                                           readonly>
                                    @error('tot_harga')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- ALAMAT --}}
                                <div class="mb-3">

                                    <label class="form-label">Alamat Pengiriman</label>

                                    <textarea name="alamat_pengiriman"
                                              class="form-control @error('alamat_pengiriman') is-invalid @enderror"
                                              rows="3"
                                              required>{{ old('alamat_pengiriman') }}</textarea>
                                    @error('alamat_pengiriman')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                {{-- TELP --}}
                                <div class="mb-3">

                                    <label class="form-label">No Telp</label>

                                    <input type="text"
                                           name="telp"
                                           class="form-control @error('telp') is-invalid @enderror"
                                           required>
                                    @error('telp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- BUKTI PEMBAYARAN --}}
                                <div class="mb-3">

                                    <label class="form-label">
                                        Upload Bukti Pembayaran
                                    </label>

                                    <input type="file"
                                           name="bukti_pembayaran"
                                           class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                                           required>

                                    @error('bukti_pembayaran')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- BUTTON --}}
                                <div class="d-grid">
                                    <button type="submit"
                                            class="btn btn-success">

                                        <i class="fas fa-credit-card"></i>
                                        Checkout Sekarang

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>
@endsection
