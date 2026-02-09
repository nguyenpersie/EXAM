<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TTDN Đường thủy Sông Hậu</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    @include('partials.header_login')

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        }
    </script>

    <style>
        /* Ngăn scroll hoàn toàn */
        html,
        body {
            height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        /* Style cho icon toggle password */
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            font-size: 1.2em;
            transition: color 0.3s;
            z-index: 10;
        }

        .toggle-password:hover {
            color: #06b6d4;
        }

        /* Fix label password không nhảy */
        .password-wrapper input:focus~label,
        .password-wrapper input:not(:placeholder-shown)~label {
            top: -0.875rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
    </style>

    @include('partials.style')

</head>

<body>
    <div class="min-h-screen bg-gray-100 py-4 flex flex-col justify-start sm:py-8">
        <div class="relative py-2 sm:max-w-xl sm:mx-auto mt-8">
            <div
                class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-sky-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
            </div>
            <div class="relative px-4 py-6 bg-white shadow-lg sm:rounded-3xl sm:p-12">

                <div class="max-w-md mx-auto">
                    <div>
                        <h1 class="text-2xl font-semibold">Đăng nhập</h1>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <form id="loginForm" action="{{ route('login') }}" method="post"
                            class="py-6 text-base leading-6 space-y-5 text-gray-700 sm:text-lg sm:leading-7">

                            <div class="relative">
                                <input autocomplete="off" id="student_code" name="student_code" type="text"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-cyan-600"
                                    placeholder="Mã học viên" required />
                                <label for="student_code"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">
                                    Tài khoản (Mã học viên)
                                </label>
                            </div>

                            <div class="relative password-wrapper">
                                <input autocomplete="off" id="password" name="password" type="password"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-cyan-600 pr-8"
                                    placeholder="Mật khẩu" required />
                                <label for="password"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm pointer-events-none">
                                    Mật khẩu
                                </label>
                                <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                            </div>

                            <div class="relative pt-2">
                                <button type="submit"
                                    class="bg-cyan-500 text-white rounded-md px-6 py-2 hover:bg-cyan-600 transition-colors w-full">
                                    Đăng nhập
                                </button>
                            </div>

                            @csrf
                        </form>
                    </div>
                </div>

            </div>
        </div>

        @include('partials.footer')
    </div>

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