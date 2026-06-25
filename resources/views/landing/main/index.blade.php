@extends('landing.layout.master')

@section('content')
    <!-- Hero Section -->

    <section class="hero text-center">

        <div class="container">

            <h1>Selamat Datang di SWL Kids</h1>

            <p class="mt-3">
                Temukan berbagai produk fashion terbaik dengan kualitas premium dan harga terjangkau
            </p>

            <a href="#produk"
               class="btn btn-shop mt-3">
                Belanja Sekarang
            </a>

        </div>

    </section>

    <!-- Tentang -->

    <section class="section"
             id="tentang">

        <div class="container text-center">

            <h2>Tentang Toko</h2>

            <p class="mt-3">
                SWL Kids adalah toko fashion yang menyediakan berbagai produk berkualitas
                dengan desain modern dan harga terjangkau untuk semua kalangan.
            </p>

        </div>

    </section>

    {{--  Kategori  --}}
    <section class="section bg-light"
             id="kategori">

        <div class="container">

            <h2 class="text-center mb-5">Kategori Produk</h2>

            <div class="row">

                @forelse ($kategoris as $kategori)
                    <div class="col-md-3 col-6 mb-4">

                        <a href="#"
                           style="text-decoration:none">

                            <div class="card kategori-card text-center p-4 h-100 text-dark">

                                <div class="kategori-icon mb-3">
                                    <i class="fas fa-tags"></i>
                                </div>

                                <h6 class="fw-semibold">
                                    {{ $kategori->nm_kategori ?? '-' }}
                                </h6>

                                @if (isset($kategori->barangs_count))
                                    <small class="text-muted">
                                        {{ $kategori->barangs_count }} Produk
                                    </small>
                                @endif

                            </div>

                        </a>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="text-center py-5">

                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png"
                                 width="120"
                                 class="mb-3">

                            <h5>Kategori Belum Tersedia</h5>

                            <p class="text-muted">
                                Saat ini belum ada kategori produk yang ditampilkan.
                            </p>

                        </div>

                    </div>
                @endforelse

            </div>

        </div>

    </section>

    {{--  Barang  --}}
    <section class="section py-5"
             id="produk">

        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold">Produk Terbaru</h2>
                <p class="text-muted">
                    Koleksi terbaik SWL Collection
                </p>
            </div>

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

    <!-- Keunggulan -->

    <section class="section bg-light">

        <div class="container">

            <h2 class="text-center mb-5">Keunggulan Toko</h2>

            <div class="row text-center">

                <div class="col-md-4">

                    <div class="icon-box">⭐</div>

                    <h5>Kualitas Premium</h5>

                </div>

                <div class="col-md-4">

                    <div class="icon-box">🚚</div>

                    <h5>Pengiriman Cepat</h5>

                </div>

                <div class="col-md-4">

                    <div class="icon-box">💰</div>

                    <h5>Harga Terjangkau</h5>

                </div>

            </div>

        </div>

    </section>

    <!-- Cara Pemesanan -->

    <section class="section">

        <div class="container">

            <h2 class="text-center mb-5">Cara Pemesanan</h2>

            <div class="row text-center">

                <div class="col-md-4">

                    <h5>1. Pilih Produk</h5>

                    <p>Pilih produk yang kamu inginkan.</p>

                </div>

                <div class="col-md-4">

                    <h5>2. Checkout</h5>

                    <p>Masukkan ke keranjang lalu checkout.</p>

                </div>

                <div class="col-md-4">

                    <h5>3. Pembayaran</h5>

                    <p>Lakukan pembayaran dan pesanan diproses.</p>

                </div>

            </div>

        </div>

    </section>

    <!-- Kontak -->

    <section class="section bg-light"
             id="kontak">

        <div class="container text-center">

            <h2>Kontak</h2>

            <p class="mt-3">
                📞 08123456789
            </p>

            <p>
                📧 swlkids@gmail.com
            </p>

            <p>
                📍 Padang, Sumatera Barat
            </p>

        </div>

    </section>
@endsection
