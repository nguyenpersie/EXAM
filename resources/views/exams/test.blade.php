@extends('layouts.master')

@section('styles')
  <style>
    .exam-container {
      display: flex;
      gap: 20px;
    }

    .question-panel {
      flex: 1;
    }

    .sheet-panel {
      width: 350px;
      /* <--- CHỈNH CHIỀU RỘNG BẢNG CÂU HỎI TẠI ĐÂY (VD: 400px, 30%) */
    }

    .q-content-text {
      font-size: 1.1rem;
      margin-bottom: 20px;
    }

    .option-item {
      display: block;
      padding: 10px 15px;
      margin-bottom: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .option-item:hover {
      background-color: #f8f9fa;
    }

    .option-item:has(input:checked) {
      background-color: #e7f1ff;
      border-color: #0d6efd;
    }

    .option-radio {
      margin-right: 10px;
    }

    .sheet-table {
      width: 100%;
      font-size: 0.85rem;
    }

    .sheet-table th,
    .sheet-table td {
      text-align: center;
      padding: 4px;
      border: 1px solid #dee2e6;
    }

    .sheet-q-num {
      cursor: pointer;
      font-weight: bold;
      background-color: #f8f9fa;
    }

    .sheet-q-num:hover {
      background-color: #e9ecef;
    }

    .sheet-q-num.active {
      background-color: #0d6efd;
      color: white;
    }

    .sheet-check {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 2px solid #ccc;
      border-radius: 50%;
      cursor: pointer;
    }

    .sheet-check:hover {
      border-color: #0d6efd;
    }

    .sheet-check.checked {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .sheet-check.correct::before {
      content: "✓ ";
      color: white;
    }

    .sheet-check.correct {
      background-color: #198754 !important;
      border-color: #198754 !important;
    }

    .sheet-check.incorrect::before {
      content: "✗ ";
      color: white;
    }

    .sheet-check.incorrect {
      background-color: #dc3545 !important;
      border-color: #dc3545 !important;
    }

    .sheet-wrapper {
      max-height: none;
      /* Hiển thị toàn bộ chiều cao theo yêu cầu */
      overflow-y: visible;
    }

    /* Responsive styles for Mobile */
    @media (max-width: 768px) {
      .exam-container {
        flex-direction: column;
      }

      .question-panel {
        order: 1;
        /* Câu hỏi hiện trước */
      }

      .sheet-panel {
        width: 100%;
        /* Full width trên mobile */
        order: 2;
        /* Bảng trả lời hiện sau */
        margin-top: 20px;
      }

      .sheet-wrapper {
        max-height: none;
        /* Hiển thị full chiều cao trên mobile luôn */
        overflow-y: visible;
      }
    }

    /* Review mode styles */
    .option-item.correct {
      background-color: #d1e7dd !important;
      border-color: #198754 !important;
      border-width: 2px;
    }

    .option-item.incorrect {
      background-color: #f8d7da !important;
      border-color: #dc3545 !important;
      border-width: 2px;
    }

    .option-item.correct-answer {
      background-color: #d1e7dd !important;
      border-color: #198754 !important;
      border-width: 2px;
    }

    .option-item.correct::before,
    .option-item.correct-answer::before {
      content: "✓ ";
      color: #198754;
      font-weight: bold;
      margin-right: 5px;
    }

    .option-item.incorrect::before {
      content: "✗ ";
      color: #dc3545;
      font-weight: bold;
      margin-right: 5px;
    }

    .review-mode .option-item {
      cursor: default;
      pointer-events: none;
    }

    /* Blue variant for exam card */
    .import-phone-card--blue {
      background-color: #eef4fb;
    }

    .import-phone-card--blue .import-phone-card__menu {
      background: linear-gradient(135deg, #0d6efd, #6ea8fe);
    }

    .import-phone-card--blue .import-phone-card__circle {
      background: rgba(255, 255, 255, 0.12);
    }
  </style>
@endsection

@section('content')
  <div class="container py-3">
    <div class="row g-3">
      <div class="col-12">
        <div class="student-card d-flex align-items-center gap-3 p-3">
          <div class="student-avatar-wrapper" style="width: 100px; flex-shrink: 0;">
            <img
              src="https://cafefcdn.com/thumb_w/640/203337114487263232/2022/3/3/photo1646280815645-1646280816151764748403.jpg"
              class="student-avatar w-100 h-auto rounded" alt="Avatar" style="object-fit: cover;" />
          </div>
          <div class="student-info flex-grow-1">
            <div class="d-flex flex-wrap gap-4 mb-2 align-items-center">
              <div>
                <span class="info-label text-muted">Số báo danh:</span>
                <span class="info-value fw-bold">{{ Auth::user()->student_code }}</span>
              </div>
              <div>
                <span class="info-label text-muted">Họ tên:</span>
                <span class="info-value text-uppercase fw-bold text-primary">{{ Auth::user()->full_name }}</span>
              </div>
            </div>
            <div class="d-flex flex-wrap gap-4 align-items-center">
              <div>
                <span class="info-label text-muted">Ngày sinh:</span>
                <span class="info-value">26/02/2026</span>
              </div>
              <div>
                <span class="info-label text-muted">Giới tính:</span>
                <span class="info-value">Nam</span>
              </div>
              <div>
                <span class="info-label text-muted">Đơn vị:</span>
                <span class="info-value">TTDN Đường Thủy Sông Hậu</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container py-4">
    <div class="import-phone-card import-phone-card--blue">
      <div class="import-phone-card__circle"></div>
      <div class="import-phone-card__menu">
        <div class="import-phone-card__menu-left">
          <i class="bi bi-pencil-square"></i>
        </div>
        <div class="import-phone-card__menu-title">{{ $exam->title }}</div>
        <div class="import-phone-card__menu-right" style="display: flex; align-items: center; gap: 0.5rem;">
          <span class="badge bg-light text-dark">
            <i class="bi bi-clock"></i> <span id="timer">--:--</span>
          </span>
          <a href="{{ route('exams.show', $exam->id) }}"
            style="color: #fff; font-size: 0.85rem; text-decoration: none; opacity: 0.9;">
            <i class="bi bi-arrow-left"></i> Quay lại
          </a>
        </div>
      </div>
      <div class="import-phone-card__content">
        <div class="exam-container">
          <!-- Panel câu hỏi -->
          <div class="question-panel">
            <h5 id="q-num" class="text-muted mb-3">Câu hỏi 1/30</h5>
            <div id="q-content">
              <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Đang tải đề thi...</p>
              </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <button class="btn btn-outline-secondary" id="btn-prev" onclick="changeQuestion(-1)">
                <i class="bi bi-chevron-left"></i> Câu trước
              </button>
              <button class="btn btn-outline-warning" id="btn-flag" onclick="toggleFlag()">
                <i class="bi bi-flag"></i> Đánh dấu
              </button>
              <button class="btn btn-outline-secondary" id="btn-next" onclick="changeQuestion(1)">
                Câu tiếp <i class="bi bi-chevron-right"></i>
              </button>
            </div>
          </div>

          <!-- Panel bảng trả lời -->
          <div class="sheet-panel">
            <div class="card">
              <div class="card-header bg-light">
                <strong><i class="bi bi-grid"></i> Bảng trả lời</strong>
              </div>
              <div class="card-body p-2">
                <div class="row">
                  <div class="col-6 sheet-wrapper">
                    <table class="sheet-table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>A</th>
                          <th>B</th>
                          <th>C</th>
                          <th>D</th>
                        </tr>
                      </thead>
                      <tbody id="sheet-column-1"></tbody>
                    </table>
                  </div>
                  <div class="col-6 sheet-wrapper">
                    <table class="sheet-table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>A</th>
                          <th>B</th>
                          <th>C</th>
                          <th>D</th>
                        </tr>
                      </thead>
                      <tbody id="sheet-column-2"></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button class="btn btn-success w-100" onclick="confirmSubmit()">
                  <i class="bi bi-check-circle"></i> Nộp bài
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal xác nhận nộp bài -->
  <div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Xác nhận nộp bài</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Bạn đã trả lời <strong id="modal-done">0</strong> câu.</p>
          <p>Còn <strong id="modal-remain">0</strong> câu chưa trả lời.</p>
          <p class="text-danger">Bạn có chắc muốn nộp bài?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-success" onclick="submitExam()" data-bs-dismiss="modal">
            <i class="bi bi-check"></i> Nộp bài
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Config cho exam-test.js
    const EXAM_ID = {{ $exam->id }};
    const EXAM_TITLE = "{{ $exam->title }}";
    const EXAM_CODE = "{{ $exam->code }}";
    const TOTAL_SCORE = 30;
    const PASSING_SCORE = 25;

    // State
    let examData = [];
    let currentIdx = 0;
    let userAnswers = {};
    let isReviewMode = false;
    let flaggedSet = new Set();
    let timeLeft = {{ $exam->duration ?? 3600 }};

    // DOM elements
    const els = {
      qNum: document.getElementById('q-num'),
      qContent: document.getElementById('q-content'),
      timer: document.getElementById('timer'),
      btnPrev: document.getElementById('btn-prev'),
      btnNext: document.getElementById('btn-next'),
      btnFlag: document.getElementById('btn-flag'),
      sheetColumn1: document.getElementById('sheet-column-1'),
      sheetColumn2: document.getElementById('sheet-column-2')
    };
  </script>
  <script src="{{ asset('js/exam-test.js') }}"></script>
@endsection