@extends('landing.setting.index')
@section('menuPelangganPesanan', 'active')

@section('content-pelanggan')
    <div class="card">
        <div class="card-body">
            <table class="table w-100">
                <thead>
                    <tr>
                        <th style="width: 4%">No.</th>
                        <th>Tanggal</th>
                        <th>Tot.Harga</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanans as $data)
                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $data->tgl_pesanan ? \Carbon\Carbon::parse($data->tgl_pesanan)->format('d-m-Y') : '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($data->tot_harga ?? 0, 0, ',', '.') }}
                            </td>

                            <td>{{ $data->alamat_pengiriman ?? '-' }}</td>

                            <td>{{ $data->telp ?? '-' }}</td>

                            <td>
                                @if ($data->status == 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($data->status == 'Diproses')
                                    <span class="badge bg-info">Diproses</span>
                                @elseif($data->status == 'Dikirim')
                                    <span class="badge bg-primary">Dikirim</span>
                                @elseif($data->status == 'Selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">{{ $data->status ?? '-' }}</span>
                                @endif
                            </td>

                            <td>

                                {{-- Bukti Pembayaran --}}
                                @if ($data->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $data->bukti_pembayaran) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       target="_blank">
                                        Bukti Bayar
                                    </a>
                                @endif

                                {{-- Detail Pesanan --}}
                                @if ($data->status == 'Selesai')
                                    <a href="{{ route('pelanggan-barang.detailpesananpdf', $data->id ?? '') }}"
                                       class="btn btn-sm btn-outline-success"
                                       target="_blank">
                                        Detail
                                    </a>
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
