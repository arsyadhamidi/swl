@extends('landing.layout.master')

@section('content')
    <section class="section py-5">

        <div class="container">

            <div class="row mb-5">

                <div class="col-lg-12 text-center">

                    <h2 class="fw-bold">
                        Kategori Produk Kami
                    </h2>

                    <p class="text-muted">
                        Temukan produk terbaik berdasarkan kategori.
                    </p>

                </div>

            </div>

            <div class="row">

                @forelse($kategoris as $kategori)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

                        <div class="card kategori-card h-100 shadow-sm border-0">

                            <div class="card-body text-center">

                                <div class="kategori-icon mb-3">

                                    <i class="fas fa-box"></i>

                                </div>

                                <h5 class="fw-bold">

                                    {{ $kategori->nm_kategori }}

                                </h5>

                                <p class="text-muted mb-3">
                                    {{ $kategori->total_barang ?? '0' }} Produk
                                </p>

                                <a href="#"
                                   class="btn btn-pink btn-sm">

                                    Lihat Produk

                                </a>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="alert alert-info text-center">

                            Belum ada kategori.

                        </div>

                    </div>
                @endforelse

            </div>

        </div>

    </section>
@endsection
