@extends('layouts.master')
@section('content')

  <div class="container-fluid py-4">
    <div class="row">
      <!-- Phần hiển thị câu hỏi -->
      <div class="col-lg-9 col-md-8 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0" id="display-q-num">Đang tải câu hỏi...</h5>
              <small>{{ $exam->title }} - {{ $exam->code }}</small>
            </div>
            <div class="text-end">
              <div class="fs-4" id="timer-display">
                <i class="bi bi-clock"></i>
                <span>45:00</span>
              </div>
            </div>
          </div>
          <div class="card-body p-4" style="min-height: 400px;">
            <div id="q-content-area">
              <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Đang tải...</span>
                </div>
                <p class="mt-3">Đang chuẩn bị đề thi...</p>
              </div>
            </div>
          </div>
          <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
              <button id="btn-prev" class="btn btn-secondary" onclick="changeQuestion(-1)" disabled>
                <i class="bi bi-arrow-left"></i> Câu trước
              </button>

              <button id="btn-flag" class="btn btn-outline-warning" onclick="toggleFlag()">
                <i class="bi bi-flag"></i> Đánh dấu
              </button>

              <button id="btn-next" class="btn btn-primary" onclick="changeQuestion(1)">
                Câu sau <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Bảng câu hỏi 2 cột -->
      <div class="col-lg-3 col-md-4 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white text-center py-2">
            <h6 class="mb-0">Bảng câu hỏi</h6>
          </div>
          <div class="card-body p-2" style="max-height: 70vh; overflow-y: auto;">
            <div class="row g-2">
              <!-- Cột 1 -->
              <div class="col-6">
                <table class="table table-bordered table-sm m-0 sheet-table">
                  <thead>
                    <tr class="bg-light">
                      <th>Câu</th>
                      <th>A</th>
                      <th>B</th>
                      <th>C</th>
                      <th>D</th>
                    </tr>
                  </thead>
                  <tbody id="sheet-column-1"></tbody>
                </table>
              </div>
              <!-- Cột 2 -->
              <div class="col-6">
                <table class="table table-bordered table-sm m-0 sheet-table">
                  <thead>
                    <tr class="bg-light">
                      <th>Câu</th>
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
          <div class="card-footer text-center p-2">
            <button class="btn btn-success btn-sm w-100" onclick="confirmSubmit()">
              <i class="bi bi-check-circle"></i> Nộp bài
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal xác nhận nộp bài -->
  <div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Xác nhận nộp bài</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Bạn đã hoàn thành <strong id="modal-done">0</strong> câu</p>
          <p>Còn lại <strong id="modal-remain">0</strong> câu chưa làm</p>
          <p class="text-danger">Bạn có chắc chắn muốn nộp bài?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-primary" onclick="submitExam()" data-bs-dismiss="modal">
            Xác nhận nộp bài
          </button>
        </div>
      </div>
    </div>
  </div>

  <style>
    /* Style cho bảng câu hỏi 2 cột */
    .sheet-table {
      font-size: 0.85rem;
    }

    .sheet-table th {
      padding: 0.3rem !important;
      text-align: center;
      font-weight: 600;
      font-size: 0.75rem;
    }

    .sheet-table td {
      padding: 0.2rem !important;
      text-align: center;
      vertical-align: middle;
    }

    .sheet-q-num {
      cursor: pointer;
      font-weight: 600;
      background-color: #f8f9fa;
      transition: all 0.2s;
      min-width: 35px;
    }

    .sheet-q-num:hover {
      background-color: #e9ecef;
      transform: scale(1.05);
    }

    .sheet-q-num.active {
      background-color: #0d6efd !important;
      color: white !important;
    }

    .sheet-check {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 2px solid #dee2e6;
      border-radius: 3px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .sheet-check:hover {
      border-color: #0d6efd;
      transform: scale(1.1);
    }

    .sheet-check.checked {
      background-color: #198754;
      border-color: #198754;
      position: relative;
    }

    .sheet-check.checked::after {
      content: "✓";
      color: white;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 14px;
      font-weight: bold;
    }

    /* Style cho câu hỏi */
    .q-content-text {
      font-size: 1.1rem;
      line-height: 1.8;
      margin-bottom: 1.5rem;
      padding: 1rem;
      background: #f8f9fa;
      border-radius: 0.5rem;
    }

    .q-options-list {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .option-item {
      display: flex;
      align-items: center;
      padding: 1rem;
      border: 2px solid #dee2e6;
      border-radius: 0.5rem;
      cursor: pointer;
      transition: all 0.2s;
    }

    .option-item:hover {
      border-color: #0d6efd;
      background-color: #f8f9fa;
    }

    .option-item input[type="radio"] {
      margin-right: 0.75rem;
      cursor: pointer;
      width: 20px;
      height: 20px;
    }

    .option-item input[type="radio"]:checked~.option-text {
      color: #0d6efd;
      font-weight: 600;
    }

    .option-text {
      flex: 1;
      font-size: 1rem;
    }
  </style>

  <script>
    // Truyền exam ID từ Laravel sang JavaScript
    const EXAM_ID = {{ $exam->id }};

    let examData = [];
    let currentIdx = 0;
    let userAnswers = {};
    let flaggedSet = new Set();
    let timeLeft = 45 * 60;
    let timerInterval;
    const TOTAL_SCORE = {{ $exam->total_score }};
    const PASSING_SCORE = {{ $exam->passing_score }};
    const EXAM_TITLE = "{{ $exam->title }}";
    const EXAM_CODE = "{{ $exam->code }}";
    const EXAM_DURATION_SECONDS = 45 * 60; // Cố định 45 phút

    const els = {
      qNum: document.getElementById("display-q-num"),
      qContent: document.getElementById("q-content-area"),
      sheetColumn1: document.getElementById("sheet-column-1"),
      sheetColumn2: document.getElementById("sheet-column-2"),
      timer: document.getElementById("timer-display").querySelector('span'),
      btnPrev: document.getElementById("btn-prev"),
      btnNext: document.getElementById("btn-next"),
      btnFlag: document.getElementById("btn-flag"),
    };

    // Load đề thi đã trộn ngẫu nhiên từ API
    async function loadExam() {
      try {
        const response = await fetch(`/exams/${EXAM_ID}/randomized`);
        const data = await response.json();

        examData = data.questions.map((q, idx) => ({
          id: q.id,
          content: q.content,
          options: q.options.map(opt => opt.content),
          correctAnswer: q.options.findIndex(opt => opt.is_correct === 1)
        }));

        console.log('Đề thi đã load và trộn:', examData);

        initSheet();
        renderQuestion(0);
        startTimer();
      } catch (error) {
        console.error('Lỗi load đề thi:', error);
        els.qContent.innerHTML = '<div class="alert alert-danger">Không thể tải đề thi. Vui lòng thử lại!</div>';
      }
    }

    // GỌI HÀM LOAD KHI TRANG ĐÃ SẴN SÀNG
    document.addEventListener('DOMContentLoaded', function () {
      loadExam(); // THÊM DÒNG NÀY
    });
  </script>

  <script src="{{ asset('js/exam-test.js') }}"></script>
@endsection