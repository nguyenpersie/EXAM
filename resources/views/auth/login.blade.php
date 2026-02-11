<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TTDN Đường thủy Sông Hậu</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    @include('partials.header_login')

    <style>
        :root {
            --main-bg-color: #ecf0f3;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            background-color: var(--main-bg-color);
            overflow-x: hidden;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
        }

        .container {
            margin: auto;
            padding: 1.5rem;
            border-radius: 2.5rem;
            background-color: var(--main-bg-color);
            box-shadow: 13px 13px 20px #cbced1,
                -13px -13px 20px #ffffff;
            max-width: 400px;
        }

        .logo {
            display: flex;
            width: 100%;
            margin-bottom: 3rem;
        }

        .logo__circle {
            margin: auto;
            width: 6.25rem;
            height: 6.25rem;
            display: flex;
            border-radius: 9999px;
            background-color: #ffffffff;
            box-shadow:
                0px 0px 2px #5F5F5F,
                0px 0px 0px 5px #ECF0F3,
                8px 8px 15px #A7AAAF,
                -8px -8px 15px #FFFFFF;
        }

        .logo__circle img {
            width: 100%;
            height: 100%;
            opacity: 1;
            object-fit: cover;
            border-radius: 9999px;
        }

        .form__group {
            width: 18rem;
            margin: 0 auto 2rem;
            position: relative;
        }

        .form__icon {
            position: absolute;
            left: 0;
            height: 100%;
            display: flex;
            width: 3rem;
            z-index: 1;
        }

        .form__icon i {
            margin: auto;
            font-size: 0.875rem;
            opacity: 0.35;
        }

        .form__control {
            appearance: none;
            border: none;
            background-color: transparent;
            font-size: 0.875rem;
            padding: 1rem;
            padding-left: 2.5rem;
            padding-right: 2.5rem;
            width: 100%;
            border-radius: 1.5rem;
            box-shadow:
                inset 8px 8px 8px #CBCED1,
                inset -8px -8px 8px #FFFFFF;
        }

        .form__control:focus {
            outline: none;
            box-shadow:
                inset 8px 8px 8px #c5c5c5,
                inset -8px -8px 8px #FFFFFF;
        }

        .form__control:focus::placeholder {
            color: #d3d3d3;
            letter-spacing: 0.15em;
        }

        .form__control::placeholder {
            color: #CCCCCC;
        }

        .form__button {
            text-transform: uppercase;
            letter-spacing: 0.15em;
            border: none;
            font-size: 0.875rem;
            color: #FFFFFF;
            background-color: #0056b3;
            width: 100%;
            display: block;
            padding: 0.875rem 1rem;
            border-radius: 1.5rem;
            box-shadow:
                3px 3px 8px #B1B1B1,
                -3px -3px 8px #FFFFFF;
            cursor: pointer;
            transition: opacity 0.3s;
            width: 18rem;
            margin: 0 auto;
        }

        .form__button:focus {
            outline: none;
            box-shadow:
                3px 3px 20px #B1B1B1,
                -3px -3px 20px #FFFFFF;
        }

        .form__button:hover {
            opacity: 0.85;
        }

        /* Toggle password icon */
        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 0.875rem;
            opacity: 0.35;
            z-index: 1;
            transition: opacity 0.3s;
        }

        .toggle-password:hover {
            opacity: 0.6;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .container {
                padding: 1.5rem;
            }

            .form__group {
                width: 16rem;
            }
        }
    </style>

    @include('partials.style')

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-M6L9Y7EQKR"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-M6L9Y7EQKR');
    </script>

</head>

<body>
    <div class="container">
        <div class="logo">
            <div class="logo__circle">
                <img src="{{ asset('assets/images/icon-logo.png') }}" alt="Logo">
            </div>
        </div>
        <form class="form" id="loginForm" action="{{ route('login') }}" method="post">
            <div class="form__group">
                <div class="form__icon">
                    <i class="bi bi-person"></i>
                </div>
                <input class="form__control" type="text" name="student_code" id="student_code" placeholder="Mã học viên"
                    required>
            </div>
            <div class="form__group">
                <div class="form__icon">
                    <i class="bi bi-lock"></i>
                </div>
                <input class="form__control" type="password" name="password" id="password" placeholder="Mật khẩu"
                    required>
                <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
            </div>
            <div>
                <button class="form__button" type="submit">
                    Đăng nhập
                </button>
            </div>
            @csrf
        </form>
    </div>

    @include('partials.footer')

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Toggle type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle icon
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>