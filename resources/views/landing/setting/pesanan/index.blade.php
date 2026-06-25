@extends('landing.setting.index')
@section('menuPelangganPesanan', 'active')

@section('content-pelanggan')
    <div class="card">
        <div class="card-body">
            <table class="table w-100">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Nama Barang</th>
                        <th width="12%">Variasi</th>
                        <th width="7%">Qty</th>
                        <th width="12%">Harga</th>
                        <th width="12%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanans as $data)
                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->nm_barang }}
                            </td>

                            <td>
                                {{ $item->ukuran }}
                                <br>
                                {{ $item->warna }}
                            </td>

                            <td>
                                {{ $item->jumlah }}
                            </td>

                            <td>
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
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
