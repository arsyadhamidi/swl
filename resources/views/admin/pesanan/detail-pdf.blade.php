<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* HEADER */
        .header {
            border-bottom: 3px solid #ff6f91;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 70px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #ff6f91;
        }

        .sub-title {
            font-size: 12px;
            color: #777;
        }

        /* INFO PESANAN */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 4px;
        }

        /* TABEL PRODUK */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th {
            background: #ff6f91;
            color: white;
            padding: 8px;
            text-align: left;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 7px;
        }

        /* TOTAL */
        .total-box {
            margin-top: 15px;
            width: 100%;
        }

        .total-box td {
            padding: 6px;
        }

        .total-harga {
            font-size: 16px;
            font-weight: bold;
            color: #ff6f91;
        }

        /* FOOTER */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }
    </style>

</head>

<body>

    <!-- HEADER -->
    <table width="100%"
           class="header">
        <tr>

            <td width="80">
                <img src="{{ public_path('images/logo.png') }}"
                     class="logo">
            </td>

            <td>
                <div class="title">SWL Collection</div>
                <div class="sub-title">Laporan Detail Pesanan Pelanggan</div>
            </td>

            <td align="right">
                <strong>Tanggal Cetak</strong><br>
                {{ date('d-m-Y') }}
            </td>

        </tr>
    </table>


    <!-- INFO PESANAN -->
    <table class="info-table">

        <tr>
            <td width="120"><strong>No Pesanan</strong></td>
            <td>: SWL00{{ $pesanans->id ?? '1' }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>: {{ \Carbon\Carbon::parse($pesanans->tgl_pesanan)->format('d-m-Y') }}</td>
        </tr>

        <tr>
            <td><strong>Nama</strong></td>
            <td>: {{ $pesanans->name ?? '-' }}</td>
        </tr>

        <tr>
            <td><strong>Telp</strong></td>
            <td>: {{ $pesanans->telp ?? '-' }}</td>
        </tr>

        <tr>
            <td><strong>Alamat</strong></td>
            <td>: {{ $pesanans->alamat_pengiriman ?? '-' }}</td>
        </tr>

        <tr>
            <td><strong>Status</strong></td>
            <td>

                :

                @if ($pesanans->status == 'Pending')
                    Menunggu Konfirmasi
                @elseif($pesanans->status == 'Diproses')
                    Sedang Diproses
                @elseif($pesanans->status == 'Selesai')
                    Pesanan Selesai
                @else
                    Dibatalkan
                @endif

            </td>
        </tr>

    </table>


    <!-- TABEL PRODUK -->
    <table class="table" style="width: 100%">

        <thead>
            <tr>
                <th width="40">No</th>
                <th>Nama Barang</th>
                <th>Variasi</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>

            @php
                $total = 0;
            @endphp

            @foreach ($detailPesanans as $item)
                @php
                    $subtotal = $item->jumlah * $item->harga;
                    $total += $subtotal;
                @endphp

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        <strong>{{ $item->nm_barang }}</strong>
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
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>


    <!-- TOTAL -->
    <table class="total-box">

        <tr>
            <td align="right"><strong>Total Pembayaran</strong></td>
            <td width="150"
                class="total-harga">
                Rp {{ number_format($total, 0, ',', '.') }}
            </td>
        </tr>

    </table>


    <!-- FOOTER -->
    <div class="footer">

        Terima kasih telah berbelanja di <strong>SWL Collection</strong> <br>
        Dokumen ini dicetak secara otomatis oleh sistem.

    </div>


</body>

</html>
