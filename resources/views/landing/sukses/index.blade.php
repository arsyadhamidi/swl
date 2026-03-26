<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Checkout Berhasil</title>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('plugins/bootstrap-5.2.3/css/bootstrap.min.css') }}">

    <style>
        body {
            background: #f5f6fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        .success-card {
            background: white;
            padding: 50px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .check-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            margin-bottom: 20px;
        }

        .check-icon i {
            font-size: 45px;
            color: white;
        }

        .countdown {
            font-weight: bold;
            color: #28a745;
            font-size: 20px;
        }

        .btn-home {
            margin-top: 20px;
        }
    </style>

</head>

<body>

    <div class="success-card">

        <div class="check-icon">
            <i class="fas fa-check"></i>
        </div>

        <h3 class="mb-3">
            Checkout Berhasil!
        </h3>

        <p class="text-muted">
            Terima kasih telah melakukan pemesanan.<br>
            Pesanan Anda sedang kami proses.
        </p>

        <p>
            Anda akan diarahkan ke halaman utama dalam
            <span class="countdown"
                  id="countdown">5</span> detik
        </p>

        <a href="/"
           class="btn btn-success btn-home">
            Kembali ke Beranda
        </a>

    </div>


    <script>
        let time = 5;

        let countdown = document.getElementById("countdown");

        let timer = setInterval(function() {

            time--;

            countdown.innerText = time;

            if (time <= 0) {

                clearInterval(timer);

                window.location.href = "/";

            }

        }, 1000);
    </script>

</body>

</html>
