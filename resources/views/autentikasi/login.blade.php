<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | SWL Kids</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
          rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ff9fb4, #ffd2dc);
        }

        /* Container */

        .login-container {
            display: flex;
            width: 900px;
            height: 520px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        /* Left Section */

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #ff6f91, #ff9fb4);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 40px;
        }

        .login-left img {
            width: 180px;
            margin-bottom: 20px;
        }

        .login-left h2 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-left p {
            font-size: 14px;
            opacity: 0.9;
            text-align: center;
        }

        /* Right Section */

        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-box {
            width: 100%;
            max-width: 320px;
        }

        .form-box h3 {
            margin-bottom: 25px;
            color: #ff6f91;
            text-align: center;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            font-size: 13px;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-top: 5px;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #ff6f91;
            outline: none;
            box-shadow: 0 0 5px rgba(255, 111, 145, 0.3);
        }

        /* Button */

        .btn-login {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #ff6f91;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #ff4f79;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 111, 145, 0.4);
        }

        .footer {
            margin-top: 15px;
            font-size: 13px;
            text-align: center;
        }

        .footer a {
            color: #ff6f91;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media(max-width:900px) {

            .login-container {
                flex-direction: column;
                height: auto;
                width: 90%;
            }

            .login-left {
                padding: 20px;
            }

        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="login-left">

            <img src="{{ asset('images/logo.png') }}"
                 alt="logo">

            <h2>SWL Kids</h2>

            <p>E-Commerce Fashion Store</p>

        </div>

        <div class="login-right">

            <div class="form-box">

                <h3>Login Account</h3>

                <form method="POST"
                      action="{{ route('login.authenticate') }}">
                    @csrf

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               required
                               value="{{ old('email') }}"
                               placeholder="Masukan alamat email">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required
                               placeholder="********">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button class="btn-login">Login</button>

                    <div class="footer">
                        Belum punya akun? <a href="{{ route('registrasi.index') }}">Register</a>
                    </div>

                </form>

            </div>

        </div>

    </div>

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

</body>

</html>
