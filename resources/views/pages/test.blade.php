@extends('layouts.master')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <!-- Cột trái: Bảng câu hỏi (Sheet) -->
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
        {{-- <div class="col-lg-3 col-md-4 mb-4">
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
        </div> --}}
        <!-- Cột trái: Bảng câu hỏi - chia 2 cột song song -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center py-2">
                    <h6 class="mb-0">Bảng câu hỏi</h6>
                </div>
                <div class="card-body p-2" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row g-2">
                        <!-- Cột 1 (câu 1-15) -->
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
                                <tbody id="sheet-column-1">

                                </tbody>
                            </table>
                        </div>

                        <!-- Cột 2 (câu 16-30) -->
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
                                <tbody id="sheet-column-2">

                                </tbody>
                            </table>
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
                <button type="button" class="btn btn-danger" onclick="submitExam()" data-bs-dismiss="modal">Nộp bài</button>
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
{{-- <script>
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
        correctAnswer: question.options.findIndex(opt => opt.is_correct === 1) // Lưu đáp án đúng
      }));

      const TOTAL_QUESTIONS = examData.length;
      const EXAM_DURATION = examDataFromDB.duration_minutes * 45; // Chuyển phút sang giây

      /* =========================================
       2. STATE QUẢN LÝ (TRẠNG THÁI)
       ========================================= */
      let currentIdx = 0;
      let userAnswers = {};
      let flaggedSet = new Set();
      let timeLeft = EXAM_DURATION;
      let timerInterval;
      let examStartTime = Date.now();

      /* =========================================
       3. DOM ELEMENTS
       ========================================= */
      const els = {
        qNum: document.getElementById("display-q-num"),
        qContent: document.getElementById("q-content-area"),
        //sheetBody: document.getElementById("sheet-body"),
        sheetColumn1: document.getElementById("sheet-column-1"),
        sheetColumn2: document.getElementById("sheet-column-2"),
        timer: document.getElementById("timer-display"),
        btnPrev: document.getElementById("btn-prev"),
        btnNext: document.getElementById("btn-next"),
        btnFlag: document.getElementById("btn-flag"),
      };

      /* =========================================
       4. HÀM RENDER (HIỂN THỊ)
       ========================================= */

      // Khởi tạo bảng trả lời (Chạy 1 lần đầu)
      // function initSheet() {
      //   els.sheetBody.innerHTML = examData
      //     .map(
      //       (q, idx) => `
      //       <tr id="row-${q.id}">
      //           <td class="sheet-q-num" onclick="goToQuestion(${idx})" id="q-label-${idx}">${idx + 1}</td>
      //           ${[0, 1, 2, 3]
      //             .map(
      //               (optIdx) => `
      //               <td>
      //                   <span class="sheet-check"
      //                         id="cell-${q.id}-${optIdx}"
      //                         onclick="selectAnswer(${q.id}, ${optIdx})"></span>
      //               </td>
      //           `
      //             )
      //             .join("")}
      //       </tr>
      //   `
      //     )
      //     .join("");
      // }

      function initSheet() {
        const midPoint = Math.ceil(examData.length / 2); // Chia đôi số câu hỏi

        // Cột 1: Câu 1 -> midPoint
        const column1HTML = examData
          .slice(0, midPoint)
          .map((q, idx) => createSheetRow(q, idx))
          .join("");

        // Cột 2: Câu midPoint+1 -> hết
        const column2HTML = examData
          .slice(midPoint)
          .map((q, idx) => createSheetRow(q, idx + midPoint))
          .join("");

        els.sheetColumn1.innerHTML = column1HTML;
        els.sheetColumn2.innerHTML = column2HTML;
      }

      // Hàm tạo 1 hàng trong bảng sheet
      function createSheetRow(q, idx) {
        return `
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
        `;
      }

      // Hiển thị câu hỏi chi tiết ở giữa
      function renderQuestion(idx) {
        currentIdx = idx;
        const q = examData[idx];

        // Update Tiêu đề
        els.qNum.innerText = `Nội dung câu hỏi ${idx + 1}`;

        // Update nội dung & đáp án (Radio buttons)
        const savedAns = userAnswers[q.id];

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

        // Cuộn bảng sheet đến câu đang làm
        document.getElementById(`row-${q.id}`).scrollIntoView({ behavior: "smooth", block: "center" });
      }

      /* =========================================
       5. LOGIC XỬ LÝ (ACTION)
       ========================================= */

      // Xử lý khi chọn đáp án
      function selectAnswer(qId, optIdx) {
        userAnswers[qId] = optIdx;

        // Cập nhật UI Bảng Sheet
        [0, 1, 2, 3].forEach((i) => {
          const cell = document.getElementById(`cell-${qId}-${i}`);
          if (cell) cell.classList.remove("checked");
        });

        const selectedCell = document.getElementById(`cell-${qId}-${optIdx}`);
        if (selectedCell) selectedCell.classList.add("checked");

        // Tick radio button nếu đang ở câu đó
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

      // Nhảy đến câu bất kỳ
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
            alert("Hết giờ làm bài! Bài thi sẽ tự động nộp.");
            submitExam();
            return;
          }
          timeLeft--;
          const m = Math.floor(timeLeft / 60).toString().padStart(2, "0");
          const s = (timeLeft % 60).toString().padStart(2, "0");
          els.timer.innerText = `${m}:${s}`;
        }, 1000);
      }

      function confirmSubmit() {
        const doneCount = Object.keys(userAnswers).length;
        const modalDone = document.getElementById("modal-done");
        const modalRemain = document.getElementById("modal-remain");

        if (modalDone) modalDone.innerText = doneCount;
        if (modalRemain) modalRemain.innerText = TOTAL_QUESTIONS - doneCount;

        const myModal = new bootstrap.Modal(document.getElementById("submitModal"));
        myModal.show();
      }

      // Hàm này được gọi từ nút "Xác nhận nộp bài" trong modal
      window.submitExam = submitExam;

      /* =========================================
       7. CHẤM ĐIỂM VÀ LƯU KẾT QUẢ
       ========================================= */
      function submitExam() {
        clearInterval(timerInterval);

        // Tính điểm
        let correctCount = 0;
        let wrongCount = 0;
        let skippedCount = 0;

        const detailedResults = examData.map((question, idx) => {
          const userAnswer = userAnswers[question.id];
          const isCorrect = userAnswer === question.correctAnswer;

          if (userAnswer === undefined) {
            skippedCount++;
            return {
              questionNumber: idx + 1,
              questionId: question.id,
              questionContent: question.content,
              userAnswer: null,
              correctAnswer: question.correctAnswer,
              isCorrect: false,
              status: 'skipped'
            };
          } else if (isCorrect) {
            correctCount++;
            return {
              questionNumber: idx + 1,
              questionId: question.id,
              questionContent: question.content,
              userAnswer: userAnswer,
              correctAnswer: question.correctAnswer,
              isCorrect: true,
              status: 'correct'
            };
          } else {
            wrongCount++;
            return {
              questionNumber: idx + 1,
              questionId: question.id,
              questionContent: question.content,
              userAnswer: userAnswer,
              correctAnswer: question.correctAnswer,
              isCorrect: false,
              status: 'wrong'
            };
          }
        });

        // Tính điểm số
        const score = (correctCount / TOTAL_QUESTIONS) * examDataFromDB.total_score;
        const percentage = (correctCount / TOTAL_QUESTIONS) * 100;
        const timeSpent = EXAM_DURATION - timeLeft;
        const isPassed = score >= examDataFromDB.passing_score;

        // Lưu kết quả vào object
        const examResult = {
          examId: examDataFromDB.id,
          examTitle: examDataFromDB.title,
          examCode: examDataFromDB.code,
          totalQuestions: TOTAL_QUESTIONS,
          correctCount: correctCount,
          wrongCount: wrongCount,
          skippedCount: skippedCount,
          score: score.toFixed(2),
          totalScore: examDataFromDB.total_score,
          passingScore: examDataFromDB.passing_score,
          percentage: percentage.toFixed(2),
          isPassed: isPassed,
          timeSpent: timeSpent,
          duration: EXAM_DURATION,
          submittedAt: new Date().toISOString(),
          detailedResults: detailedResults
        };

        // Lưu vào biến tạm (có thể dùng sessionStorage hoặc chuyển sang trang kết quả)
        sessionStorage.setItem('examResult', JSON.stringify(examResult));

        // Chuyển sang trang kết quả hoặc hiển thị modal kết quả
        showResultModal(examResult);
      }

      /* =========================================
       8. HIỂN THỊ KẾT QUẢ
       ========================================= */
      function showResultModal(result) {
        const resultHTML = `
          <div class="modal fade" id="resultModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header bg-${result.isPassed ? 'success' : 'danger'} text-white">
                  <h5 class="modal-title">
                    <i class="bi bi-${result.isPassed ? 'check-circle' : 'x-circle'}"></i>
                    Kết quả bài thi
                  </h5>
                </div>
                <div class="modal-body">
                  <div class="text-center mb-4">
                    <h3>${result.examTitle}</h3>
                    <p class="text-muted">Mã đề: ${result.examCode}</p>
                  </div>

                  <div class="row text-center mb-4">
                    <div class="col-md-6">
                      <div class="card bg-light">
                        <div class="card-body">
                          <h1 class="display-4 text-${result.isPassed ? 'success' : 'danger'}">
                            ${result.score}/${result.totalScore}
                          </h1>
                          <p class="mb-0">Điểm số</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card bg-light">
                        <div class="card-body">
                          <h1 class="display-4 text-primary">${result.percentage}%</h1>
                          <p class="mb-0">Tỷ lệ đúng</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-4 text-center">
                      <div class="text-success">
                        <i class="bi bi-check-circle fs-3"></i>
                        <p class="mb-0"><strong>${result.correctCount}</strong></p>
                        <small>Câu đúng</small>
                      </div>
                    </div>
                    <div class="col-4 text-center">
                      <div class="text-danger">
                        <i class="bi bi-x-circle fs-3"></i>
                        <p class="mb-0"><strong>${result.wrongCount}</strong></p>
                        <small>Câu sai</small>
                      </div>
                    </div>
                    <div class="col-4 text-center">
                      <div class="text-warning">
                        <i class="bi bi-dash-circle fs-3"></i>
                        <p class="mb-0"><strong>${result.skippedCount}</strong></p>
                        <small>Bỏ qua</small>
                      </div>
                    </div>
                  </div>

                  <div class="alert alert-info">
                    <i class="bi bi-clock"></i> Thời gian làm bài:
                    <strong>${Math.floor(result.timeSpent / 60)} phút ${result.timeSpent % 60} giây</strong>
                    / ${Math.floor(result.duration / 60)} phút
                  </div>

                  ${result.isPassed ?
                    '<div class="alert alert-success"><i class="bi bi-trophy"></i> Chúc mừng! Bạn đã vượt qua bài thi!</div>' :
                    '<div class="alert alert-danger"><i class="bi bi-emoji-frown"></i> Bạn chưa đạt điểm! Điểm cần đạt: ' + result.passingScore + '</div>'
                  }
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" onclick="viewDetailedResults()">
                    <i class="bi bi-eye"></i> Xem chi tiết
                  </button>
                  <button type="button" class="btn btn-secondary" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i> Làm lại
                  </button>
                  <button type="button" class="btn btn-success" onclick="window.close()">
                    <i class="bi bi-check-lg"></i> Hoàn thành
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;

        // Thêm modal vào body
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = resultHTML;
        document.body.appendChild(tempDiv.firstElementChild);

        // Hiển thị modal
        const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
        resultModal.show();
      }

      // Xem chi tiết từng câu
      function viewDetailedResults() {
        const result = JSON.parse(sessionStorage.getItem('examResult'));

        let detailHTML = `
          <!DOCTYPE html>
          <html lang="vi">
          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Chi tiết bài thi - ${result.examTitle}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
          </head>
          <body class="bg-light">
            <div class="container py-4">
              <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                  <h4 class="mb-0">Chi tiết bài làm - ${result.examTitle}</h4>
                  <small>Điểm: ${result.score}/${result.totalScore} (${result.percentage}%)</small>
                </div>
              </div>
        `;

        result.detailedResults.forEach((item) => {
          const statusClass = item.status === 'correct' ? 'success' : (item.status === 'wrong' ? 'danger' : 'warning');
          const statusIcon = item.status === 'correct' ? 'check-circle' : (item.status === 'wrong' ? 'x-circle' : 'dash-circle');

          detailHTML += `
            <div class="card mb-3 border-${statusClass}">
              <div class="card-header bg-${statusClass} bg-opacity-10">
                <strong>Câu ${item.questionNumber}</strong>
                <span class="badge bg-${statusClass} float-end">
                  <i class="bi bi-${statusIcon}"></i>
                  ${item.status === 'correct' ? 'Đúng' : (item.status === 'wrong' ? 'Sai' : 'Bỏ qua')}
                </span>
              </div>
              <div class="card-body">
                <p><strong>Câu hỏi:</strong> ${item.questionContent}</p>
                <p class="mb-1"><strong>Đáp án của bạn:</strong>
                  <span class="text-${statusClass}">
                    ${item.userAnswer !== null ? String.fromCharCode(65 + item.userAnswer) : 'Không trả lời'}
                  </span>
                </p>
                ${item.status !== 'correct' ? `
                  <p class="mb-0"><strong>Đáp án đúng:</strong>
                    <span class="text-success">${String.fromCharCode(65 + item.correctAnswer)}</span>
                  </p>
                ` : ''}
              </div>
            </div>
          `;
        });

        detailHTML += `
              <div class="text-center mb-4">
                <button class="btn btn-secondary" onclick="window.close()">Đóng</button>
              </div>
            </div>
          </body>
          </html>
        `;

        // Mở cửa sổ mới để hiển thị chi tiết
        const detailWindow = window.open('', '_blank');
        detailWindow.document.write(detailHTML);
        detailWindow.document.close();
      }

      /* =========================================
       9. EXPORT FUNCTIONS TO GLOBAL SCOPE
       ========================================= */
      // Để các hàm có thể gọi từ onclick trong HTML
      window.changeQuestion = changeQuestion;
      window.goToQuestion = goToQuestion;
      window.selectAnswer = selectAnswer;
      window.toggleFlag = toggleFlag;
      window.confirmSubmit = confirmSubmit;
      window.submitExam = submitExam;
      window.viewDetailedResults = viewDetailedResults;

      /* =========================================
       10. MAIN RUN
       ========================================= */
      if (examData && examData.length > 0) {
        initSheet();
        renderQuestion(0);
        startTimer();
      } else {
        alert('Không tìm thấy dữ liệu đề thi!');
      }
      function submitExam() {
        clearInterval(timerInterval); // Dừng timer

        let correctCount = 0;
        let wrongCount = 0;
        let skippedCount = 0;

        // Duyệt qua tất cả câu hỏi để tính điểm
        examData.forEach(q => {
            const selectedId = userAnswers[q.id];
            if (selectedId === undefined) {
                skippedCount++;
            } else {
                const selectedOpt = q.options.find(opt => opt.id === selectedId);
                if (selectedOpt && selectedOpt.is_correct) {
                    correctCount++;
                } else {
                    wrongCount++;
                }
            }
        });

        const score = correctCount;
        const totalScore = TOTAL_QUESTIONS; // Giả sử mỗi câu 1 điểm
        const percentage = ((correctCount / TOTAL_QUESTIONS) * 100).toFixed(2);
        const isPassed = score >= {{ $exam->passing_score ?? 80 }};

        // Hiển thị kết quả trong modal
        document.getElementById("result-score").innerText = `${score} / ${totalScore}`;
        document.getElementById("result-status").innerText = isPassed ? "Đạt" : "Không đạt";
        document.getElementById("result-title").innerText = isPassed ? "Chúc mừng!" : "Cố lên nhé!";

        // Đóng modal submit và mở modal kết quả
        const submitModal = bootstrap.Modal.getInstance(document.getElementById("submitModal"));
        submitModal.hide();

        const resultModal = new bootstrap.Modal(document.getElementById("resultModal"));
        resultModal.show();
    }

    /* =========================================
    7. MAIN RUN
    ========================================= */
    initSheet();
    renderQuestion(0);
    startTimer();
</script> --}}
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
        correctAnswer: question.options.findIndex(opt => opt.is_correct === 1) // Lưu đáp án đúng
      }));

      const TOTAL_QUESTIONS = examData.length;
      const EXAM_DURATION = examDataFromDB.duration_minutes * 60; // Chuyển phút sang giây

      /* =========================================
       2. STATE QUẢN LÝ (TRẠNG THÁI)
       ========================================= */
      let currentIdx = 0;
      let userAnswers = {};
      let flaggedSet = new Set();
      let timeLeft = EXAM_DURATION;
      let timerInterval;
      let examStartTime = Date.now();

      /* =========================================
       3. DOM ELEMENTS
       ========================================= */
      const els = {
        qNum: document.getElementById("display-q-num"),
        qContent: document.getElementById("q-content-area"),
        sheetColumn1: document.getElementById("sheet-column-1"),
        sheetColumn2: document.getElementById("sheet-column-2"),
        timer: document.getElementById("timer-display"),
        btnPrev: document.getElementById("btn-prev"),
        btnNext: document.getElementById("btn-next"),
        btnFlag: document.getElementById("btn-flag"),
      };

      /* =========================================
       4. HÀM RENDER (HIỂN THỊ)
       ========================================= */

      // Khởi tạo bảng trả lời (Chạy 1 lần đầu) - Chia làm 2 cột
      function initSheet() {
        const midPoint = Math.ceil(examData.length / 2); // Chia đôi số câu hỏi

        // Cột 1: Câu 1 -> midPoint
        const column1HTML = examData
          .slice(0, midPoint)
          .map((q, idx) => createSheetRow(q, idx))
          .join("");

        // Cột 2: Câu midPoint+1 -> hết
        const column2HTML = examData
          .slice(midPoint)
          .map((q, idx) => createSheetRow(q, idx + midPoint))
          .join("");

        els.sheetColumn1.innerHTML = column1HTML;
        els.sheetColumn2.innerHTML = column2HTML;
      }

      // Hàm tạo 1 hàng trong bảng sheet
      function createSheetRow(q, idx) {
        return `
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
        `;
      }

      // Hiển thị câu hỏi chi tiết ở giữa
      function renderQuestion(idx) {
        currentIdx = idx;
        const q = examData[idx];

        // Update Tiêu đề
        els.qNum.innerText = `Nội dung câu hỏi ${idx + 1}`;

        // Update nội dung & đáp án (Radio buttons)
        const savedAns = userAnswers[q.id];

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

        // Cuộn bảng sheet đến câu đang làm
        document.getElementById(`row-${q.id}`).scrollIntoView({ behavior: "smooth", block: "center" });
      }

      /* =========================================
       5. LOGIC XỬ LÝ (ACTION)
       ========================================= */

      // Xử lý khi chọn đáp án
      function selectAnswer(qId, optIdx) {
        userAnswers[qId] = optIdx;

        // Cập nhật UI Bảng Sheet
        [0, 1, 2, 3].forEach((i) => {
          const cell = document.getElementById(`cell-${qId}-${i}`);
          if (cell) cell.classList.remove("checked");
        });

        const selectedCell = document.getElementById(`cell-${qId}-${optIdx}`);
        if (selectedCell) selectedCell.classList.add("checked");

        // Tick radio button nếu đang ở câu đó
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

      // Nhảy đến câu bất kỳ
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
            alert("Hết giờ làm bài! Bài thi sẽ tự động nộp.");
            submitExam();
            return;
          }
          timeLeft--;
          const m = Math.floor(timeLeft / 60).toString().padStart(2, "0");
          const s = (timeLeft % 60).toString().padStart(2, "0");
          els.timer.innerText = `${m}:${s}`;
        }, 1000);
      }

      function confirmSubmit() {
        const doneCount = Object.keys(userAnswers).length;
        const modalDone = document.getElementById("modal-done");
        const modalRemain = document.getElementById("modal-remain");

        if (modalDone) modalDone.innerText = doneCount;
        if (modalRemain) modalRemain.innerText = TOTAL_QUESTIONS - doneCount;

        const myModal = new bootstrap.Modal(document.getElementById("submitModal"));
        myModal.show();
      }

      // Hàm này được gọi từ nút "Xác nhận nộp bài" trong modal
      window.submitExam = submitExam;

      /* =========================================
       7. CHẤM ĐIỂM VÀ LƯU KẾT QUẢ
       ========================================= */
      function submitExam() {
        clearInterval(timerInterval);

        // Tính điểm
        let correctCount = 0;
        let wrongCount = 0;
        let skippedCount = 0;

        const detailedResults = examData.map((question, idx) => {
          const userAnswer = userAnswers[question.id];
          const isCorrect = userAnswer === question.correctAnswer;

          if (userAnswer === undefined) {
            skippedCount++;
            return {
              questionNumber: idx + 1,
              questionId: question.id,
              questionContent: question.content,
              userAnswer: null,
              correctAnswer: question.correctAnswer,
              isCorrect: false,
              status: 'skipped'
            };
          } else if (isCorrect) {
            correctCount++;
            return {
              questionNumber: idx + 1,
              questionId: question.id,
              questionContent: question.content,
              userAnswer: userAnswer,
              correctAnswer: question.correctAnswer,
              isCorrect: true,
              status: 'correct'
            };
          } else {
            wrongCount++;
            return {
              questionNumber: idx + 1,
              questionId: question.id,
              questionContent: question.content,
              userAnswer: userAnswer,
              correctAnswer: question.correctAnswer,
              isCorrect: false,
              status: 'wrong'
            };
          }
        });

        // Tính điểm số
        const score = (correctCount / TOTAL_QUESTIONS) * examDataFromDB.total_score;
        const percentage = (correctCount / TOTAL_QUESTIONS) * 100;
        const timeSpent = EXAM_DURATION - timeLeft;
        const isPassed = score >= examDataFromDB.passing_score;

        // Lưu kết quả vào object
        const examResult = {
          examId: examDataFromDB.id,
          examTitle: examDataFromDB.title,
          examCode: examDataFromDB.code,
          totalQuestions: TOTAL_QUESTIONS,
          correctCount: correctCount,
          wrongCount: wrongCount,
          skippedCount: skippedCount,
          score: score.toFixed(2),
          totalScore: examDataFromDB.total_score,
          passingScore: examDataFromDB.passing_score,
          percentage: percentage.toFixed(2),
          isPassed: isPassed,
          timeSpent: timeSpent,
          duration: EXAM_DURATION,
          submittedAt: new Date().toISOString(),
          detailedResults: detailedResults
        };

        // Lưu vào biến tạm (có thể dùng sessionStorage hoặc chuyển sang trang kết quả)
        sessionStorage.setItem('examResult', JSON.stringify(examResult));

        // Chuyển sang trang kết quả hoặc hiển thị modal kết quả
        showResultModal(examResult);
      }

      /* =========================================
       8. HIỂN THỊ KẾT QUẢ
       ========================================= */
      function showResultModal(result) {
        const resultHTML = `
          <div class="modal fade" id="resultModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header bg-${result.isPassed ? 'success' : 'danger'} text-white">
                  <h5 class="modal-title">
                    <i class="bi bi-${result.isPassed ? 'check-circle' : 'x-circle'}"></i>
                    Kết quả bài thi
                  </h5>
                </div>
                <div class="modal-body">
                  <div class="text-center mb-4">
                    <h3>${result.examTitle}</h3>
                    <p class="text-muted">Mã đề: ${result.examCode}</p>
                  </div>

                  <div class="row text-center mb-4">
                    <div class="col-md-6">
                      <div class="card bg-light">
                        <div class="card-body">
                          <h1 class="display-4 text-${result.isPassed ? 'success' : 'danger'}">
                            ${result.score}/${result.totalScore}
                          </h1>
                          <p class="mb-0">Điểm số</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card bg-light">
                        <div class="card-body">
                          <h1 class="display-4 text-primary">${result.percentage}%</h1>
                          <p class="mb-0">Tỷ lệ đúng</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-4 text-center">
                      <div class="text-success">
                        <i class="bi bi-check-circle fs-3"></i>
                        <p class="mb-0"><strong>${result.correctCount}</strong></p>
                        <small>Câu đúng</small>
                      </div>
                    </div>
                    <div class="col-4 text-center">
                      <div class="text-danger">
                        <i class="bi bi-x-circle fs-3"></i>
                        <p class="mb-0"><strong>${result.wrongCount}</strong></p>
                        <small>Câu sai</small>
                      </div>
                    </div>
                    <div class="col-4 text-center">
                      <div class="text-warning">
                        <i class="bi bi-dash-circle fs-3"></i>
                        <p class="mb-0"><strong>${result.skippedCount}</strong></p>
                        <small>Bỏ qua</small>
                      </div>
                    </div>
                  </div>

                  <div class="alert alert-info">
                    <i class="bi bi-clock"></i> Thời gian làm bài:
                    <strong>${Math.floor(result.timeSpent / 60)} phút ${result.timeSpent % 60} giây</strong>
                    / ${Math.floor(result.duration / 60)} phút
                  </div>

                  ${result.isPassed ?
                    '<div class="alert alert-success"><i class="bi bi-trophy"></i> Chúc mừng! Bạn đã vượt qua bài thi!</div>' :
                    '<div class="alert alert-danger"><i class="bi bi-emoji-frown"></i> Bạn chưa đạt điểm! Điểm cần đạt: ' + result.passingScore + '</div>'
                  }
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" onclick="viewDetailedResults()">
                    <i class="bi bi-eye"></i> Xem chi tiết
                  </button>
                  <button type="button" class="btn btn-secondary" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i> Làm lại
                  </button>
                  <button type="button" class="btn btn-success" onclick="window.close()">
                    <i class="bi bi-check-lg"></i> Hoàn thành
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;

        // Thêm modal vào body
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = resultHTML;
        document.body.appendChild(tempDiv.firstElementChild);

        // Hiển thị modal
        const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
        resultModal.show();
      }

      // Xem chi tiết từng câu
      function viewDetailedResults() {
        const result = JSON.parse(sessionStorage.getItem('examResult'));

        let detailHTML = `
          <!DOCTYPE html>
          <html lang="vi">
          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Chi tiết bài thi - ${result.examTitle}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
          </head>
          <body class="bg-light">
            <div class="container py-4">
              <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                  <h4 class="mb-0">Chi tiết bài làm - ${result.examTitle}</h4>
                  <small>Điểm: ${result.score}/${result.totalScore} (${result.percentage}%)</small>
                </div>
              </div>
        `;

        result.detailedResults.forEach((item) => {
          const statusClass = item.status === 'correct' ? 'success' : (item.status === 'wrong' ? 'danger' : 'warning');
          const statusIcon = item.status === 'correct' ? 'check-circle' : (item.status === 'wrong' ? 'x-circle' : 'dash-circle');

          detailHTML += `
            <div class="card mb-3 border-${statusClass}">
              <div class="card-header bg-${statusClass} bg-opacity-10">
                <strong>Câu ${item.questionNumber}</strong>
                <span class="badge bg-${statusClass} float-end">
                  <i class="bi bi-${statusIcon}"></i>
                  ${item.status === 'correct' ? 'Đúng' : (item.status === 'wrong' ? 'Sai' : 'Bỏ qua')}
                </span>
              </div>
              <div class="card-body">
                <p><strong>Câu hỏi:</strong> ${item.questionContent}</p>
                <p class="mb-1"><strong>Đáp án của bạn:</strong>
                  <span class="text-${statusClass}">
                    ${item.userAnswer !== null ? String.fromCharCode(65 + item.userAnswer) : 'Không trả lời'}
                  </span>
                </p>
                ${item.status !== 'correct' ? `
                  <p class="mb-0"><strong>Đáp án đúng:</strong>
                    <span class="text-success">${String.fromCharCode(65 + item.correctAnswer)}</span>
                  </p>
                ` : ''}
              </div>
            </div>
          `;
        });

        detailHTML += `
              <div class="text-center mb-4">
                <button class="btn btn-secondary" onclick="window.close()">Đóng</button>
              </div>
            </div>
          </body>
          </html>
        `;

        // Mở cửa sổ mới để hiển thị chi tiết
        const detailWindow = window.open('', '_blank');
        detailWindow.document.write(detailHTML);
        detailWindow.document.close();
      }

      /* =========================================
       9. EXPORT FUNCTIONS TO GLOBAL SCOPE
       ========================================= */
      // Để các hàm có thể gọi từ onclick trong HTML
      window.changeQuestion = changeQuestion;
      window.goToQuestion = goToQuestion;
      window.selectAnswer = selectAnswer;
      window.toggleFlag = toggleFlag;
      window.confirmSubmit = confirmSubmit;
      window.submitExam = submitExam;
      window.viewDetailedResults = viewDetailedResults;

      /* =========================================
       10. MAIN RUN
       ========================================= */
      if (examData && examData.length > 0) {
        initSheet();
        renderQuestion(0);
        startTimer();
      } else {
        alert('Không tìm thấy dữ liệu đề thi!');
      }
    </script>

@endsection
