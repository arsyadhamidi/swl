@extends('dashboard.layout.master')
@section('title', 'Dashboard | SWL Collection')
@section('menuDashboard', 'active')

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $keranjangs ?? '0' }}</h3>

                    <p>Keranjang</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('admin-keranjang.index') }}"
                   class="small-box-footer">Baca Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $pesanans ?? '0' }}</h3>

                    <p>Pesanan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('admin-pesanan.index') }}"
                   class="small-box-footer">Baca Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $users ?? '0' }}</h3>

                    <p>User Registrasi</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('admin-user.index') }}"
                   class="small-box-footer">Baca Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $barangs ?? '0' }}</h3>

                    <p>Data Barang</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('admin-barang.index') }}"
                   class="small-box-footer">Baca Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <b>Grafik Pesanan Per Bulan</b>
                </div>
                <div class="card-body">
                    <canvas id="chartPesanan"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <b>Kategori Paling Banyak Dipesan</b>
                </div>
                <div class="card-body">
                    <canvas id="chartKategori"></canvas>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('custom-script')
    <script>
        let bulan = {!! json_encode($pesananPerBulan->pluck('bulan')) !!};
        let totalPesanan = {!! json_encode($pesananPerBulan->pluck('total')) !!};

        const ctx = document.getElementById('chartPesanan');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: bulan,
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: totalPesanan,
                    borderWidth: 2,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0,123,255,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true
            }
        });


        // kategori
        let kategori = {!! json_encode($kategoriTerlaris->pluck('nm_kategori')) !!};
        let totalKategori = {!! json_encode($kategoriTerlaris->pluck('total')) !!};

        const ctx2 = document.getElementById('chartKategori');

        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: kategori,
                datasets: [{
                    data: totalKategori,
                    backgroundColor: [
                        '#007bff',
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#17a2b8'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endpush
