<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token"
          content="{{ csrf_token() }}">
    <title>Landing | SWL Kids</title>

    <link rel="stylesheet"
          href="{{ asset('plugins/bootstrap-5.2.3/css/bootstrap.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('css/landing-style.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">

</head>

<body>

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <a class="navbar-brand"
               href="#">SWL Kids</a>

            <button class="navbar-toggler bg-light"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse"
                 id="navbarNav">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('landing.index') }}">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="#tentang">Tentang</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="#kategori">Kategori</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('landing.produk') }}">Produk</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="#kontak">Kontak</a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2"
                               href="{{ route('pelanggan-barang.settingpelanggan') }}">

                                <img src="{{ Auth::user()->foto_profile ? asset('storage/' . Auth::user()->foto_profile) : asset('images/foto-profile.png') }}"
                                     alt="Foto Profile"
                                     class="nav-profile">

                                <span>{{ Auth::user()->name }}</span>

                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('login.logout') }}">
                                Logout
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link position-relative"
                               href="{{ route('pelanggan-barang.keranjang') }}">

                                <i class="fas fa-shopping-cart fs-5"></i>

                                @php
                                    $keranjangCounts = \App\Models\KeranjangDetail::join('keranjangs', 'keranjang_details.keranjang_id', 'keranjangs.id')
                                        ->where('keranjangs.users_id', Auth::user()->id)
                                        ->count();
                                @endphp

                                @if (($keranjangCounts ?? 0) > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $keranjangCounts ?? '0' }}
                                    </span>
                                @endif

                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('login') }}">
                                Login / Register
                            </a>
                        </li>

                    @endauth
                </ul>

            </div>

        </div>
    </nav>

    @yield('content')

    <!-- Footer -->

    <footer class="text-center">

        <div class="container">

            <p>© 2026 SWL Kids. All Rights Reserved.</p>

        </div>

    </footer>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    <script src="{{ asset('plugins/bootstrap-5.2.3/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            @if (Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
            @endif

            @if (Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @endif

        });
    </script>
    @stack('custom-script')

</body>

</html>
