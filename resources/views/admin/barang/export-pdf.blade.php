<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .header {
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .logo {
            float: left;
            margin-right: 20px;
        }

        .text {
            float: left;
            font-size: 16px;
            line-height: 1.5;
        }

        .text h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .text p {
            margin-top: -5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead th {
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }

        tbody td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        tbody td:first-child {
            text-align: center;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('images/logo.png') }}"
                 width="100"
                 alt="Logo">
        </div>
        <div class="text"
             style="float: left;">
            <h1>SWL Collection</h1>
            <p>Sistem Penjualan Produk Berbasis E-Commerce</p>
            <p>Laporan Data Barang Terdaftar</p>
        </div>
    </div>

    <p style="margin-top: 65px;">
        <hr>
    </p>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th colspan="6">LAPORAN DATA BARANG</th>
                </tr>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Barang</th>
                    <th width="20%">Kategori</th>
                    <th width="10%">Variasi</th>
                    <th width="10%">Stok</th>
                    <th width="25%">Rentang Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td style="text-align:left">
                            {{ $data->nm_barang }}
                        </td>

                        <td style="text-align:left">
                            {{ $data->nm_kategori }}
                        </td>

                        <td>
                            {{ $data->total_variasi }}
                        </td>

                        <td>
                            {{ $data->total_stok }}
                        </td>

                        <td style="text-align:left">
                            @if ($data->harga_min == $data->harga_max)
                                Rp {{ number_format($data->harga_min, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($data->harga_min, 0, ',', '.') }}
                                -
                                Rp {{ number_format($data->harga_max, 0, ',', '.') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <p>
        Laporan ini dihasilkan secara otomatis oleh sistem. SWL Collection - Sistem Penjualan E-Commerce
    </p>
</body>

</html>
