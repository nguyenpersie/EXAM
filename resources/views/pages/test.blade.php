@extends('layouts.master')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <!-- Cột trái: Bảng câu hỏi (Sheet) -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Bảng câu hỏi</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered m-0 sheet-table">
                        <thead>
                            <tr class="bg-light">
                                <th>Câu</th>
                                <th>A</th>
                                <th>B</th>
                                <th>C</th>
                                <th>D</th>
                            </tr>
                        </thead>
                        <tbody id="sheet-body">
                            <!-- Script sẽ render bảng này -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cột giữa: Nội dung câu hỏi + đáp án -->
        <div class="col-lg-6 col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" id="display-q-num">Câu hỏi số 1</h5>
                    <div>
                        <button id="btn-flag" class="btn btn-outline-warning btn-sm" onclick="toggleFlag()">
                            <i class="bi bi-flag"></i> Đánh dấu
                        </button>
                        <span id="timer-display" class="badge bg-danger ms-2">60:00</span>
                    </div>
                </div>
                <div class="card-body" id="q-content-area">
                    <!-- Script sẽ render nội dung câu hỏi + đáp án ở đây -->
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button id="btn-prev" class="btn btn-secondary" onclick="changeQuestion(-1)" disabled>
                        <i class="bi bi-arrow-left"></i> Trước
                    </button>
                    <button id="btn-submit" class="btn btn-primary" onclick="confirmSubmit()">
                        Nộp bài
                    </button>
                    <button id="btn-next" class="btn btn-secondary" onclick="changeQuestion(1)">
                        Sau <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Cột phải: Thông tin thi -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Thông tin thi</h5>
                </div>
                <div class="card-body">
                    <p><strong>Đề thi:</strong> {{ $exam->title ?? 'Đề thử nghiệm' }}</p>
                    <p><strong>Hạng:</strong> {{ $exam->code }}</p>
                    <p><strong>Thời gian:</strong> {{ $exam->duration_minutes }} phút</p>
                    <p><strong>Tổng điểm:</strong> {{ $exam->total_score }}</p>
                    <p><strong>Điểm đạt:</strong> {{ $exam->passing_score }}</p>
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
                <p>Bạn đã làm xong <strong><span id="modal-done">0</span></strong> / {{ $exam->questions->count() ?? 30 }} câu.</p>
                <p>Còn <strong><span id="modal-remain">30</span></strong> câu chưa làm. Nộp bài ngay?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tiếp tục làm</button>
                <button type="button" class="btn btn-danger" onclick="submitExam()">Nộp bài</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal kết quả -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kết quả thi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <h3 id="result-title">Kết quả</h3>
                <p class="fs-4">Điểm: <strong id="result-score">0</strong> / {{ $exam->total_score ?? 100 }}</p>
                <p class="fs-5" id="result-status">Không đạt</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="location.reload()">Làm lại</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
      /* =========================================
       1. LOAD DỮ LIỆU TỪ DATABASE (EXAM ID = 67)
       ========================================= */
      // Dữ liệu được truyền từ Controller qua Blade
      const examDataFromDB = @json($exam);

      // Chuyển đổi dữ liệu từ database sang format phù hợp
      const examData = examDataFromDB.questions.map((question, index) => ({
        id: question.id,
        content: question.content,
        options: question.options.map(opt => opt.content),
        correctAnswer: question.options.findIndex(opt => opt.is_correct === 1) // Lưu đáp án đúng (không hiển thị cho user)
      }));

      const TOTAL_QUESTIONS = examData.length;
      const EXAM_DURATION = examDataFromDB.duration_minutes * 60; // Chuyển phút sang giây

      /* =========================================
       2. STATE QUẢN LÝ (TRẠNG THÁI)
       ========================================= */
      let currentIdx = 0; // Đang xem câu index 0 (câu 1)
      let userAnswers = {}; // Lưu đáp án: { 1: 0, 2: 3 } (câu 1 chọn A, câu 2 chọn D)
      let flaggedSet = new Set(); // Các câu đánh dấu
      let timeLeft = EXAM_DURATION;
      let timerInterval;

      /* =========================================
       3. DOM ELEMENTS
       ========================================= */
      const els = {
        qNum: document.getElementById("display-q-num"),
        qContent: document.getElementById("q-content-area"),
        sheetBody: document.getElementById("sheet-body"),
        timer: document.getElementById("timer-display"),
        btnPrev: document.getElementById("btn-prev"),
        btnNext: document.getElementById("btn-next"),
        btnFlag: document.getElementById("btn-flag"),
      };

      /* =========================================
       4. HÀM RENDER (HIỂN THỊ)
       ========================================= */

      // Khởi tạo bảng trả lời (Chạy 1 lần đầu)
      function initSheet() {
        els.sheetBody.innerHTML = examData
          .map(
            (q, idx) => `
            <tr id="row-${q.id}">
                <td class="sheet-q-num" onclick="goToQuestion(${idx})" id="q-label-${idx}">${idx + 1}</td>
                ${[0, 1, 2, 3]
                  .map(
                    (optIdx) => `
                    <td>
                        <span class="sheet-check"
                              id="cell-${q.id}-${optIdx}"
                              onclick="selectAnswer(${q.id}, ${optIdx})"></span>
                    </td>
                `
                  )
                  .join("")}
            </tr>
        `
          )
          .join("");
      }

      // Hiển thị câu hỏi chi tiết ở giữa
      function renderQuestion(idx) {
        currentIdx = idx;
        const q = examData[idx];

        // Update Tiêu đề
        els.qNum.innerText = `Nội dung câu hỏi ${idx + 1}`;

        // Update nội dung & đáp án (Radio buttons)
        const savedAns = userAnswers[q.id]; // Đáp án đã chọn trước đó (nếu có)

        const optionsHTML = q.options
          .map(
            (opt, i) => `
            <label class="option-item">
                <input type="radio" name="currentQuestion" class="option-radio form-check-input"
                       value="${i}"
                       ${savedAns === i ? "checked" : ""}
                       onchange="selectAnswer(${q.id}, ${i})">
                <span class="option-text"><b>${String.fromCharCode(65 + i)}.</b> ${opt}</span>
            </label>
        `
          )
          .join("");

        els.qContent.innerHTML = `
            <div class="q-content-text">${q.content}</div>
            <div class="q-options-list">${optionsHTML}</div>
        `;

        // Update nút Điều hướng
        els.btnPrev.disabled = idx === 0;
        els.btnNext.disabled = idx === examData.length - 1;

        // Update nút Flag
        updateFlagButtonUI();

        // Highlight dòng đang chọn bên bảng Sheet
        document.querySelectorAll(".sheet-q-num").forEach((el) => el.classList.remove("active"));
        document.getElementById(`q-label-${idx}`).classList.add("active");

        // Cuộn bảng sheet đến câu đang làm (nếu bảng dài quá)
        document.getElementById(`row-${q.id}`).scrollIntoView({ behavior: "smooth", block: "center" });
      }

      /* =========================================
       5. LOGIC XỬ LÝ (ACTION)
       ========================================= */

      // Xử lý khi chọn đáp án (Từ Radio hoặc từ Bảng Sheet)
      function selectAnswer(qId, optIdx) {
        // 1. Lưu vào State
        userAnswers[qId] = optIdx;

        // 2. Cập nhật UI Bảng Sheet (Tô đen ô)
        // Reset dòng đó trước
        [0, 1, 2, 3].forEach((i) => {
          const cell = document.getElementById(`cell-${qId}-${i}`);
          if (cell) cell.classList.remove("checked");
        });

        // Tô màu ô mới
        const selectedCell = document.getElementById(`cell-${qId}-${optIdx}`);
        if (selectedCell) selectedCell.classList.add("checked");

        // 3. Nếu đang đứng ở câu đó thì tick radio button tương ứng
        if (examData[currentIdx].id === qId) {
          const radios = document.getElementsByName("currentQuestion");
          if (radios[optIdx]) radios[optIdx].checked = true;
        }
      }

      // Chuyển câu hỏi
      function changeQuestion(step) {
        const newIdx = currentIdx + step;
        if (newIdx >= 0 && newIdx < examData.length) {
          renderQuestion(newIdx);
        }
      }

      // Nhảy đến câu bất kỳ từ bảng sheet
      function goToQuestion(idx) {
        renderQuestion(idx);
      }

      // Đánh dấu (Flag)
      function toggleFlag() {
        const qId = examData[currentIdx].id;
        if (flaggedSet.has(qId)) {
          flaggedSet.delete(qId);
        } else {
          flaggedSet.add(qId);
        }
        updateFlagButtonUI();
        updateSheetFlagUI(qId);
      }

      function updateFlagButtonUI() {
        const qId = examData[currentIdx].id;
        if (flaggedSet.has(qId)) {
          els.btnFlag.classList.remove("btn-outline-warning");
          els.btnFlag.classList.add("btn-warning");
          els.btnFlag.innerHTML = '<i class="bi bi-flag-fill"></i> Đã đánh dấu';
        } else {
          els.btnFlag.classList.add("btn-outline-warning");
          els.btnFlag.classList.remove("btn-warning");
          els.btnFlag.innerHTML = '<i class="bi bi-flag"></i> Đánh dấu';
        }
      }

      function updateSheetFlagUI(qId) {
        const label = document.getElementById(`q-label-${currentIdx}`);
        if (label) {
          if (flaggedSet.has(qId)) {
            label.style.backgroundColor = "#ffc107";
          } else {
            label.style.backgroundColor = "";
            if (currentIdx === examData.findIndex(q => q.id === qId)) {
              label.classList.add("active");
            }
          }
        }
      }

      /* =========================================
       6. TIMER VÀ SUBMIT
       ========================================= */
      function startTimer() {
        timerInterval = setInterval(() => {
          if (timeLeft <= 0) {
            clearInterval(timerInterval);
            alert("Hết giờ làm bài!");
            // Có thể tự động submit ở đây
            return;
          }
          timeLeft--;
          const m = Math.floor(timeLeft / 60)
            .toString()
            .padStart(2, "0");
          const s = (timeLeft % 60).toString().padStart(2, "0");
          els.timer.innerText = `${m}:${s}`;
        }, 1000);
      }

      function confirmSubmit() {
        const doneCount = Object.keys(userAnswers).length;
        document.getElementById("modal-done").innerText = doneCount;
        document.getElementById("modal-remain").innerText = TOTAL_QUESTIONS - doneCount;

        const myModal = new bootstrap.Modal(document.getElementById("submitModal"));
        myModal.show();
      }

      // Hàm submit bài thi (gửi lên server)
      function submitExam() {
        // Chuyển userAnswers thành format phù hợp để gửi lên server
        const answers = Object.entries(userAnswers).map(([questionId, optionIndex]) => ({
          question_id: parseInt(questionId),
          selected_option: optionIndex
        }));

        // Gửi dữ liệu qua AJAX hoặc form submit
        // Ví dụ dùng fetch API:
        fetch(`/exams-${examDataFromDB.id}/submit`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            answers: answers,
            time_spent: EXAM_DURATION - timeLeft
          })
        })
        .then(response => response.json())
        .then(data => {
          // Xử lý kết quả trả về
          window.location.href = `/exams-${examDataFromDB.id}/result`;
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi nộp bài!');
        });
      }

      /* =========================================
       7. MAIN RUN
       ========================================= */
      // Chạy khi load trang
      if (examData && examData.length > 0) {
        initSheet(); // Vẽ bảng câu hỏi
        renderQuestion(0); // Vào câu 1
        startTimer(); // Đếm giờ
      } else {
        alert('Không tìm thấy dữ liệu đề thi!');
      }
    </script>

@endsection
