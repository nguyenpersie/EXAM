<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hệ thống ôn thi trắc nghiệm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    @include('partials.header_login')

    @include('partials.style')

  </head>
  <body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h2 class="mb-4 text-center">LOGIN</h2>
            <form id="loginForm" action="#" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu">
                </div>
                <div class="mb-3 form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember_token">
                    <label class="form-check-label" for="remember">Remember</label>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreate" style="width: 40%;">Register</button>
                    <button type="submit" class="btn btn-primary" style="width: 40%;">Login</button>
                </div>
                @csrf
            </form>
        </div>
    </div>
    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('admin.questions.modals.add')
  </body>
</html>
