@extends('landing.layout.master')

@section('content')
    <section class="section py-5">
        <div class="container">

            <div class="row mb-4">
                <div class="col-lg-12 text-center">

                    <h2 class="fw-bold">
                        Produk Kami
                    </h2>

                    <p class="text-muted">
                        Temukan produk terbaik pilihan kami
                    </p>

                </div>
            </div>

            <div class="row mb-4">
                <div class="col-lg-6 mx-auto">

                    <form method="GET"
                          action="{{ url()->current() }}">

                        <div class="input-group shadow-sm">

                            <input type="text"
                                   name="search"
                                   value="{{ $search ?? '' }}"
                                   class="form-control"
                                   placeholder="Cari produk...">

                            <button class="btn btn-primary">

                                <i class="fas fa-search"></i>
                                Cari

                            </button>

                        </div>

                    </form>

                </div>
            </div>

            @if (!empty($search))
                <div class="text-center mb-4">

                    <span class="text-muted">
                        Hasil pencarian untuk:
                        <strong>"{{ $search }}"</strong>
                    </span>

                </div>
            @endif


            <div class="row">

                @forelse($barangs as $barang)
                    <div class="col-lg-3 col-md-4 col-6 mb-4">

                        <div class="card product-card h-100 border-0 shadow-sm">

                            {{-- FOTO PRODUK --}}
                            <div class="product-img">

                                <img src="{{ asset('storage/' . $barang->foto_barang) }}"
                                     class="card-img-top"
                                     alt="{{ $barang->nm_barang }}">

                            </div>


                            <div class="card-body">

                                {{-- NAMA PRODUK --}}
                                <h6 class="product-title">
                                    {{ $barang->nm_barang }}
                                </h6>


                                {{-- HARGA --}}
                                <div class="product-price mb-3">

                                    Rp {{ number_format($barang->harga, 0, ',', '.') }}

                                </div>


                                {{-- BUTTON --}}
                                <div class="d-grid">

                                    <a href="{{ route('pelanggan-barang.showbarang', $barang->id ?? '') }}"
                                       class="btn btn-outline-primary btn-sm">

                                        <i class="fas fa-eye"></i>
                                        Lihat Detail

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-lg-12 text-center">

                        <p class="text-muted">
                            Produk belum tersedia
                        </p>

                    </div>
                @endforelse

            </div>

        </div>
    </section>
@endsection
