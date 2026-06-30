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
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

                        <div class="card border-0 shadow-sm h-100 product-card">

                            <div class="position-relative">

                                <img src="{{ asset('storage/' . $barang->foto_barang) }}"
                                     class="card-img-top"
                                     style="height:280px;object-fit:cover;">

                                @if ($barang->total_stok > 0)
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                        Stok {{ $barang->total_stok }}
                                    </span>
                                @else
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                        Stok Habis
                                    </span>
                                @endif

                            </div>

                            <div class="card-body d-flex flex-column">

                                <h6 class="fw-bold mb-2">
                                    {{ $barang->nm_barang }}
                                </h6>

                                <small class="text-muted mb-2">
                                    {{ $barang->total_variasi }} Variasi
                                </small>

                                <div class="mb-3">

                                    @if ($barang->harga_min == $barang->harga_max)
                                        <span class="text-danger fw-bold">
                                            Rp {{ number_format($barang->harga_min, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-danger fw-bold">
                                            Rp {{ number_format($barang->harga_min, 0, ',', '.') }}
                                            -
                                            Rp {{ number_format($barang->harga_max, 0, ',', '.') }}
                                        </span>
                                    @endif

                                </div>

                                <a href="{{ route('pelanggan-barang.showbarang', $barang->id) }}"
                                   class="btn btn-primary mt-auto">

                                    <i class="fas fa-shopping-bag"></i>
                                    Lihat Detail

                                </a>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="text-center py-5">

                            <img src="{{ asset('images/foto-profile.png') }}"
                                 width="120"
                                 class="mb-3">

                            <h5>Produk Belum Tersedia</h5>

                            <p class="text-muted">
                                Saat ini belum ada produk yang ditampilkan.
                            </p>

                        </div>

                    </div>
                @endforelse

            </div>

        </div>
    </section>
@endsection
