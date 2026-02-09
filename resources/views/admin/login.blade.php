<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TTDN Đường thủy Sông Hậu</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    @include('partials.header_login')

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Quicksand", sans-serif;
        }

        html,
        body {
            height: 100%;
            width: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #111;
            overflow-x: hidden;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .ring {
            position: relative;
            width: 400px;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ring i {
            position: absolute;
            inset: 0;
            border: 2px solid #fff;
            transition: 0.5s;
        }

        .ring i:nth-child(1) {
            border-radius: 38% 62% 63% 37% / 41% 44% 56% 59%;
            animation: animate 6s linear infinite;
        }

        .ring i:nth-child(2) {
            border-radius: 41% 44% 56% 59%/38% 62% 63% 37%;
            animation: animate 4s linear infinite;
        }

        .ring i:nth-child(3) {
            border-radius: 41% 44% 56% 59%/38% 62% 63% 37%;
            animation: animate2 10s linear infinite;
        }

        .ring:hover i {
            border: 6px solid var(--clr);
            filter: drop-shadow(0 0 20px var(--clr));
        }

        @keyframes animate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes animate2 {
            0% {
                transform: rotate(360deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        .login {
            position: absolute;
            width: 280px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 18px;
        }

        .login h2 {
            font-size: 1.8em;
            color: #1d1c1c0a;
            font-weight: 700;
        }

        .login .inputBx {
            position: relative;
            width: 100%;
        }

        .login .inputBx input {
            position: relative;
            width: 100%;
            padding: 10px 18px;
            background: #0000004d;
            border: 2px solid #fff172;
            border-radius: 40px;
            font-size: 1em;
            color: #fff;
            box-shadow: none;
            outline: none;
        }

        .login .inputBx input:focus {
            border-color: #0078ff;
            box-shadow: 0 0 10px rgba(0, 120, 255, 0.5);
        }

        .login .inputBx input[type="submit"] {
            width: 100%;
            background: linear-gradient(45deg, #ff357a, #fff172);
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.3s;
        }

        .login .inputBx input[type="submit"]:hover {
            transform: scale(1.05);
        }

        .login .inputBx input::placeholder {
            color: rgba(255, 255, 255, 0.75);
        }

        .login .links {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .login .links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s;
            font-size: 0.9em;
        }

        .login .links a:hover {
            color: #0078ff;
        }

        /* Footer fix */
        footer {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .ring {
                width: 320px;
                height: 320px;
            }

            .login {
                width: 240px;
                gap: 15px;
            }

            .login h2 {
                font-size: 1.5em;
            }

            .login .inputBx input {
                font-size: 0.95em;
                padding: 9px 15px;
            }
        }
    </style>

    @include('partials.style')
</head>

<body>
    <div class="main-content">
        <!--ring div starts here-->
        <div class="ring">
            <i style="--clr:#00ff0a;"></i>
            <i style="--clr:#ff0057;"></i>
            <i style="--clr:#fffd44;"></i>
            <div class="login">
                <h2>Đăng nhập</h2>
                <form id="loginForm" action="{{ route('login') }}" method="post"
                    style="width: 100%; display: flex; flex-direction: column; gap: 18px;">
                    <div class="inputBx">
                        <input type="text" name="student_code" id="student_code" placeholder="Mã học viên" required>
                    </div>
                    <div class="inputBx">
                        <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
                    </div>
                    <div class="inputBx">
                        <input type="submit" value="Đăng nhập">
                    </div>
                    @csrf
                </form>
                {{-- <div class="links">
                    <a href="#">Quên mật khẩu</a>
                    <a href="#">Đăng ký</a>
                </div> --}}
            </div>
        </div>
        <!--ring div ends here-->
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>