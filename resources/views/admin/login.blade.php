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
        /* Reset */
        :root {
            --color-neutral-900: oklch(0.185 0 0);
            color-scheme: light dark;
        }

        body {
            background-color: oklch(0.145 0 0);
            color: oklch(0.985 0 0);
            font-family: system-ui, -apple-system, sans-serif;
            overflow: hidden;
        }

        /* SVG positioning */
        .svg-container {
            position: absolute;
        }

        /* Card container */
        .login-card-container {
            padding: 2px;
            border-radius: 1.5em;
            position: relative;
            background: linear-gradient(-30deg,
                    var(--gradient-color),
                    transparent,
                    var(--gradient-color)),
                linear-gradient(to bottom,
                    var(--color-neutral-900),
                    var(--color-neutral-900));
            --f: url(#🌀↖️);
            --electric-border-color: #dd8448;
            --electric-light-color: oklch(from var(--electric-border-color) l c h);
            --gradient-color: oklch(from var(--electric-border-color) 0.3 calc(c / 2) h / 0.4);
        }

        /* Inner container */
        .inner-container {
            position: relative;
        }

        /* Border layers */
        .border-outer {
            border: 2px solid oklch(from var(--electric-border-color) l c h / 0.5);
            border-radius: 1.5em;
            padding-right: .15em;
            padding-bottom: .15em;
        }

        .main-card {
            width: 100%;
            max-width: 450px;
            border-radius: 1.5em;
            border: 2px solid var(--electric-border-color);
            margin-top: -4px;
            margin-left: -4px;
            filter: var(--f);
            background: var(--color-neutral-900);
            padding: 48px;
        }

        /* Glow effects */
        .glow-layer-1 {
            border: 2px solid oklch(from var(--electric-border-color) l c h / 0.6);
            border-radius: 24px;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            filter: blur(1px);
            pointer-events: none;
        }

        .glow-layer-2 {
            border: 2px solid var(--electric-light-color);
            border-radius: 24px;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            filter: blur(4px);
            pointer-events: none;
        }

        /* Overlay effects */
        .overlay-1 {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 24px;
            opacity: 1;
            mix-blend-mode: overlay;
            transform: scale(1.1);
            filter: blur(16px);
            background: linear-gradient(-30deg, white, transparent 30%, transparent 70%, white);
            pointer-events: none;
        }

        .overlay-2 {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 24px;
            opacity: 0.5;
            mix-blend-mode: overlay;
            transform: scale(1.1);
            filter: blur(16px);
            background: linear-gradient(-30deg, white, transparent 30%, transparent 70%, white);
            pointer-events: none;
        }

        /* Background glow */
        .background-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 24px;
            filter: blur(32px);
            transform: scale(1.1);
            opacity: 0.3;
            z-index: -1;
            background: linear-gradient(-30deg, var(--electric-light-color), transparent, var(--electric-border-color));
        }

        /* Form styling */
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 0.5em;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--electric-border-color);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(221, 132, 72, 0.25);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-label {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .btn-primary {
            background: var(--electric-border-color);
            border: none;
            border-radius: 0.5em;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: oklch(from var(--electric-border-color) calc(l * 1.1) c h);
        }

        h2 {
            color: white;
            font-weight: 600;
        }
    </style>

    @include('partials.style')
</head>

<body>
    <!-- SVG Filters -->
    <svg class="svg-container">
        <defs>
            <filter id="🌀↖️" colorInterpolationFilters="sRGB" x="-20%" y="-20%" width="140%" height="140%">
                <feTurbulence type="turbulence" baseFrequency="0.02" numOctaves="10" result="noise1" seed="1" />
                <feOffset in="noise1" dx="0" dy="0" result="offsetNoise1">
                    <animate attributeName="dy" values="700; 0" dur="6s" repeatCount="indefinite" calcMode="linear" />
                </feOffset>

                <feTurbulence type="turbulence" baseFrequency="0.02" numOctaves="10" result="noise2" seed="1" />
                <feOffset in="noise2" dx="0" dy="0" result="offsetNoise2">
                    <animate attributeName="dy" values="0; -700" dur="6s" repeatCount="indefinite" calcMode="linear" />
                </feOffset>

                <feTurbulence type="turbulence" baseFrequency="0.02" numOctaves="10" result="noise1" seed="2" />
                <feOffset in="noise1" dx="0" dy="0" result="offsetNoise3">
                    <animate attributeName="dx" values="490; 0" dur="6s" repeatCount="indefinite" calcMode="linear" />
                </feOffset>

                <feTurbulence type="turbulence" baseFrequency="0.02" numOctaves="10" result="noise2" seed="2" />
                <feOffset in="noise2" dx="0" dy="0" result="offsetNoise4">
                    <animate attributeName="dx" values="0; -490" dur="6s" repeatCount="indefinite" calcMode="linear" />
                </feOffset>

                <feComposite in="offsetNoise1" in2="offsetNoise2" result="part1" />
                <feComposite in="offsetNoise3" in2="offsetNoise4" result="part2" />
                <feBlend in="part1" in2="part2" mode="color-dodge" result="combinedNoise" />

                <feDisplacementMap in="SourceGraphic" in2="combinedNoise" scale="30" xChannelSelector="R"
                    yChannelSelector="B" />
            </filter>
        </defs>
    </svg>

    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="login-card-container">
            <div class="inner-container">
                <div class="border-outer">
                    <div class="main-card">
                        <h2 class="mb-4 text-center">Đăng nhập</h2>
                        <form id="loginForm" action="{{ route('login') }}" method="post">
                            <div class="mb-3">
                                <label for="student_code" class="form-label">Tài khoản (Mã học viên)</label>
                                <input type="text" class="form-control" name="student_code" id="student_code"
                                    placeholder="Nhập mã học viên">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Mật khẩu">
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                            </div>
                            @csrf
                        </form>
                    </div>
                </div>
                <div class="glow-layer-1"></div>
                <div class="glow-layer-2"></div>
            </div>

            <div class="overlay-1"></div>
            <div class="overlay-2"></div>
            <div class="background-glow"></div>
        </div>
    </div>

    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>