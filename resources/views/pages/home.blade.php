@extends('layouts.master')
@section('content')
 <div class="container-fluid py-3">
      <div class="row g-3">
        <div class="col-lg-3 col-md-3">
          <div class="student-card">
            <div class="row">
              <div class="col-4 px-1">
                <img
                  src="https://cafefcdn.com/thumb_w/640/203337114487263232/2022/3/3/photo1646280815645-1646280816151764748403.jpg"
                  class="student-avatar"
                  alt="Avatar"
                />
              </div>
              <div class="col-8">
                <div class="mb-1">
                  <span class="info-label">Số báo danh:</span> <span class="info-value">LPT.SH.001</span>
                </div>
                <div class="mb-1">
                  <span class="info-label">Họ tên:</span>
                  <span class="info-value text-uppercase">Đặng Thành Nguyên</span>
                </div>
                <div class="mb-1"><span class="info-label">Giới tính:</span> <span class="info-value">Nam</span></div>
                <div class="mb-1">
                  <span class="info-label">Ngày sinh:</span> <span class="info-value">26/02/2026</span>
                </div>
                <div class="mb-1">
                  <span class="info-label">Đơn vị:</span> <span class="info-value">TTDN Đường Thủy Sông Hậu</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-md-6">
          <div class="question-box shadow-sm">
            <div class="q-header">
              <h5 class="q-title" id="display-q-num">Nội dung câu hỏi 1</h5>
              <button class="btn btn-outline-warning btn-sm" id="btn-flag" onclick="toggleFlag()">
                <i class="bi bi-flag"></i> Đánh dấu
              </button>
            </div>

            <div class="q-body">
              <div id="q-content-area">
                <p class="text-center text-muted mt-5">Đang tải dữ liệu...</p>
              </div>
            </div>
          </div>

          <div class="nav-actions">
            <button class="btn btn-nav" id="btn-prev" onclick="changeQuestion(-1)">
              <i class="bi bi-chevron-left"></i> Trở lại
            </button>
            <button class="btn btn-nav" id="btn-next" onclick="changeQuestion(1)">
              Tiếp tục <i class="bi bi-chevron-right"></i>
            </button>
          </div>
        </div>

        <div class="col-lg-3 col-md-3">
          <div class="timer-box">
            <div class="fw-bold border-bottom pb-1 mb-2">Đang thi</div>
            <div class="d-flex justify-content-between"><span>Thời gian:</span> <span>60 phút</span></div>
            <div class="d-flex justify-content-between"><span>Bù giờ:</span> <span>0 phút</span></div>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <span class="fw-bold">Còn lại:</span>
              <span class="timer-countdown" id="timer-display">60:00</span>
            </div>
          </div>

          <div class="sheet-wrapper">
            <table class="table-sheet" id="answer-table">
              <thead>
                <tr>
                  <th>Câu</th>
                  <th>A</th>
                  <th>B</th>
                  <th>C</th>
                  <th>D</th>
                </tr>
              </thead>
              <tbody id="sheet-body"></tbody>
            </table>
          </div>

          <button class="btn btn-submit shadow" onclick="confirmSubmit()">NỘP BÀI</button>
        </div>
      </div>
    </div>

@endsection
