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
    // =========================================
    // 1. DỮ LIỆU THỰC TẾ TỪ SERVER (Blade)
    // =========================================
    const examData = @json($exam->questions->map(function ($q) {
        return [
            'id' => $q->id,
            'content' => $q->content,
            'section' => $q->section,
            'level' => $q->level,
            'options' => $q->options->shuffle()->map(function ($opt) {
                return [
                    'id' => $opt->id,
                    'content' => $opt->content,
                ];
            })->values()
        ];
    }));

    const TOTAL_QUESTIONS = examData.length;
    const EXAM_DURATION = {{ $exam->duration_minutes ?? 60 }} * 60; // Thời gian từ DB (phút → giây)

    /* =========================================
     2. STATE QUẢN LÝ
     ========================================= */
    let currentIdx = 0;
    let userAnswers = {}; // { question_id: option_id }
    let flaggedSet = new Set();
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
     4. HÀM RENDER
     ========================================= */

    // Khởi tạo bảng sheet
    function initSheet() {
        els.sheetBody.innerHTML = examData
            .map(
                (q, idx) => `
            <tr id="row-${q.id}">
                <td class="sheet-q-num" onclick="goToQuestion(${idx})" id="q-label-${idx}">${q.id}</td>
                ${[0, 1, 2, 3]
                  .map(
                    (optIdx) => `
                    <td>
                        <span class="sheet-check"
                              id="cell-${q.id}-${optIdx}"
                              onclick="selectAnswer(${q.id}, ${examData[idx].options[optIdx].id})"></span>
                    </td>
                `
                  )
                  .join("")}
            </tr>
        `
            )
            .join("");
    }

    // Hiển thị câu hỏi
    function renderQuestion(idx) {
        currentIdx = idx;
        const q = examData[idx];

        els.qNum.innerText = `Câu ${q.id} (Phần ${q.section} - Độ khó ${q.level})`;

        const savedAns = userAnswers[q.id];

        const optionsHTML = q.options
            .map(
                (opt, i) => `
            <label class="option-item">
                <input type="radio" name="currentQuestion" class="option-radio form-check-input"
                       value="${opt.id}"
                       ${savedAns === opt.id ? "checked" : ""}
                       onchange="selectAnswer(${q.id}, ${opt.id})">
                <span class="option-text">${opt.content}</span>
            </label>
        `
            )
            .join("");

        els.qContent.innerHTML = `
            <div class="q-content-text">${q.content}</div>
            <div class="q-options-list">${optionsHTML}</div>
        `;

        els.btnPrev.disabled = idx === 0;
        els.btnNext.disabled = idx === examData.length - 1;

        updateFlagButtonUI();

        document.querySelectorAll(".sheet-q-num").forEach((el) => el.classList.remove("active"));
        document.getElementById(`q-label-${idx}`).classList.add("active");

        document.getElementById(`row-${q.id}`).scrollIntoView({ behavior: "smooth", block: "center" });
    }

    /* =========================================
     5. LOGIC XỬ LÝ
     ========================================= */

    function selectAnswer(qId, optId) {
        userAnswers[qId] = optId;

        // Update UI Sheet
        const q = examData.find(q => q.id === qId);
        const optIdx = q.options.findIndex(opt => opt.id === optId);
        [0, 1, 2, 3].forEach(i => {
            document.getElementById(`cell-${qId}-${i}`).classList.remove("checked");
        });
        document.getElementById(`cell-${qId}-${optIdx}`).classList.add("checked");

        // Update radio nếu đang xem câu đó
        if (examData[currentIdx].id === qId) {
            document.querySelector(`input[value="${optId}"]`).checked = true;
        }
    }

    function changeQuestion(step) {
        const newIdx = currentIdx + step;
        if (newIdx >= 0 && newIdx < examData.length) {
            renderQuestion(newIdx);
        }
    }

    function goToQuestion(idx) {
        renderQuestion(idx);
    }

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
            els.btnFlag.classList.add("btn-warning");
            els.btnFlag.classList.remove("btn-outline-warning");
            els.btnFlag.innerHTML = '<i class="bi bi-flag-fill"></i> Đã đánh dấu';
        } else {
            els.btnFlag.classList.add("btn-outline-warning");
            els.btnFlag.classList.remove("btn-warning");
            els.btnFlag.innerHTML = '<i class="bi bi-flag"></i> Đánh dấu';
        }
    }

    function updateSheetFlagUI(qId) {
        const label = document.getElementById(`q-label-${currentIdx}`);
        if (flaggedSet.has(qId)) {
            label.style.backgroundColor = "#ffc107";
        } else {
            label.style.backgroundColor = "";
            if (currentIdx === qId - 1) label.classList.add("active");
        }
    }

    function startTimer() {
        timerInterval = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert("Hết giờ!");
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
        document.getElementById("modal-done").innerText = doneCount;
        document.getElementById("modal-remain").innerText = TOTAL_QUESTIONS - doneCount;

        const myModal = new bootstrap.Modal(document.getElementById("submitModal"));
        myModal.show();
    }

    function submitExam() {
        let correctCount = 0;
        let totalScore = 0;

        examData.forEach(q => {
            const selectedId = userAnswers[q.id];
            if (selectedId) {
                const selectedOpt = q.options.find(opt => opt.id === selectedId);
                if (selectedOpt && selectedOpt.is_correct) {
                    correctCount++;
                }
                totalScore += 1; // Giả sử mỗi câu 1 điểm
            }
        });

        const score = correctCount;
        const isPass = score >= {{ $exam->passing_score ?? 80 }};

        document.getElementById("result-score").innerText = `${score} / ${TOTAL_QUESTIONS}`;
        document.getElementById("result-status").innerText = isPass ? "Đạt" : "Không đạt";
        document.getElementById("result-title").innerText = isPass ? "Chúc mừng!" : "Cố lên nhé!";

        const resultModal = new bootstrap.Modal(document.getElementById("resultModal"));
        resultModal.show();
    }

    /* =========================================
     7. MAIN RUN
     ========================================= */
    initSheet();
    renderQuestion(0);
    startTimer();
</script>

@endsection
