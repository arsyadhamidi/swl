@extends('landing.setting.index')
@section('menuPelangganPesanan', 'active')

@section('content-pelanggan')
    <div class="card">
        <div class="card-body">
            <table class="table w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Variasi</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanans as $data)
                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($data->tgl_pesanan)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $data->nm_barang }}
                            </td>

                            <td>
                                {{ $data->ukuran }}
                                <br>
                                {{ $data->warna }}
                            </td>

                            <td>
                                {{ $data->jumlah }}
                            </td>

                            <td>
                                Rp {{ number_format($data->harga, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($data->subtotal, 0, ',', '.') }}
                            </td>

                            <td>
                                @if ($data->status == 'Pending')
                                    <span class="badge bg-warning">{{ $data->status }}</span>
                                @elseif($data->status == 'Diproses')
                                    <span class="badge bg-primary">{{ $data->status }}</span>
                                @elseif($data->status == 'Selesai')
                                    <span class="badge bg-success">{{ $data->status }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $data->status }}</span>
                                @endif
                            </td>

                        </tr>
                    @empty

                        <tr>
                            <td colspan="7"
                                class="text-center text-muted">
                                Belum ada pesanan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
