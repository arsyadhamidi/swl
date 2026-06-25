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

                                                <div class="d-flex align-items-center">

                                                    <img src="{{ asset('storage/' . $item->foto_barang) }}"
                                                         width="80"
                                                         height="80"
                                                         class="rounded me-3"
                                                         style="object-fit:cover;">

                                                    <div>

                                                        <strong>
                                                            {{ $item->nm_barang }}
                                                        </strong>

                                                        <br>

                                                        <small class="text-muted">

                                                            Ukuran :
                                                            {{ $item->ukuran }}

                                                            <br>

                                                            Warna :
                                                            {{ $item->warna }}

                                                        </small>

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

                    <div class="card border-0 shadow-sm">

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

                                <div class="mb-3">

                                    <label>Total Pembayaran</label>

                                    <input type="text"
                                           class="form-control fw-bold text-success"
                                           value="Rp {{ number_format($total, 0, ',', '.') }}"
                                           readonly>

                                    <input type="hidden"
                                           name="tot_harga"
                                           value="{{ $total }}">

                                </div>

                                <div class="mb-3">

                                    <label>Alamat Pengiriman</label>

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

                                <div class="mb-3">

                                    <label>No Telepon</label>

                                    <input type="text"
                                           name="telp"
                                           class="form-control @error('telp') is-invalid @enderror"
                                           value="{{ old('telp') }}"
                                           required>

                                    @error('telp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="mb-3">

                                    <label>Bukti Pembayaran</label>

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
