<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Data Keranjang</title>
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
            <p>Laporan Data Keranjang Terdaftar</p>
        </div>
    </div>

    <p style="margin-top: 65px;">
        <hr>
    </p>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th colspan="5">LAPORAN DATA KERANJANG</th>
                </tr>
                <tr>
                    <th style="width: 4%;">No.</th>
                    <th style="text-align: left;">Nama</th>
                    <th style="text-align: left;">Email</th>
                    <th style="text-align: left;">Telepon</th>
                    <th style="text-align: left;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($keranjangs as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="text-align: left;">{{ $data->name ?? '-' }}</td>
                        <td style="text-align: left;">{{ $data->email ?? '-' }}</td>
                        <td style="text-align: left;">{{ $data->telp ?? '-' }}</td>
                        <td style="text-align: left;">{{ $data->tanggal ?? '-' }}</td>
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
