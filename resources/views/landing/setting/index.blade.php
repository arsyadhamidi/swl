@extends('landing.layout.master')
@section('content')
    <section class="section">
        <div class="container">

            {{--  Detail Akun Pelanggan  --}}
            <div class="row mb-4">

                <div class="col-lg-12">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <div class="row align-items-center">

                                {{-- Foto Profile --}}
                                <div class="col-md-2 text-center">

                                    <img src="{{ Auth::user()->foto_profile ? asset('storage/' . Auth::user()->foto_profile) : asset('images/foto-profile.png') }}"
                                         class="profile-img">

                                </div>


                                {{-- Info User --}}
                                <div class="col-md-7">

                                    <h5 class="mb-1 fw-bold">
                                        {{ Auth::user()->name }}
                                    </h5>

                                    <p class="mb-1 text-muted">
                                        {{ Auth::user()->email }}
                                    </p>

                                    <p class="mb-0 text-muted">
                                        {{ Auth::user()->telp ?? '-' }}
                                    </p>

                                </div>


                                {{-- Tombol --}}
                                <div class="col-md-3 text-md-end">

                                    <a href="#"
                                       class="btn btn-shop">

                                        Pelanggan
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-header">
                                Fitur Pelanggan
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <a href="{{ route('pelanggan-barang.settingpelanggan') }}"
                                       class="list-group-item list-group-item-action @yield('menuPelangganSetting')"
                                       aria-current="true">
                                        Setting Account
                                    </a>
                                    <a href="{{ route('pelanggan-barang.pesanan') }}"
                                       class="list-group-item list-group-item-action @yield('menuPelangganPesanan')"
                                       aria-current="true">
                                        Pesanan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="mb-3">
                        @yield('content-pelanggan')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
