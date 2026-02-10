<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TTDN Đường thủy Sông Hậu</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-logo.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

  @include('partials.style')

  @yield('styles')

</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-M6L9Y7EQKR"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag() { dataLayer.push(arguments); }
  gtag('js', new Date());

  gtag('config', 'G-M6L9Y7EQKR');
</script>

<body>

  @yield('content')

  @include('partials.footer')

  <div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Xác nhận nộp bài</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Bạn chắc chắn muốn kết thúc bài thi?</p>
          <ul>
            <li>Số câu đã làm: <strong id="modal-done">0</strong></li>
            <li>Số câu chưa làm: <strong id="modal-remain">0</strong></li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Làm tiếp</button>
          <button type="button" class="btn btn-primary" onclick="alert('Đã nộp bài thành công!')">Đồng ý nộp</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>