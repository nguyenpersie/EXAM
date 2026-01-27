// exam-test.js - Xử lý logic làm bài thi

// Hàm tạo 1 hàng trong bảng sheet
function createSheetRow(q, idx) {
    return `
      <tr id="row-${q.id}">
        <td class="sheet-q-num" onclick="goToQuestion(${idx})" id="q-label-${idx}">${idx + 1}</td>
        ${[0, 1, 2, 3]
            .map(optIdx => `
            <td>
              <span class="sheet-check" id="cell-${q.id}-${optIdx}" onclick="selectAnswer(${q.id}, ${optIdx})"></span>
            </td>
          `).join("")}
      </tr>
    `;
}

// Khởi tạo bảng câu hỏi (2 cột)
function initSheet() {
    const midPoint = Math.ceil(examData.length / 2);

    const column1HTML = examData
        .slice(0, midPoint)
        .map((q, idx) => createSheetRow(q, idx))
        .join("");

    const column2HTML = examData
        .slice(midPoint)
        .map((q, idx) => createSheetRow(q, idx + midPoint))
        .join("");

    els.sheetColumn1.innerHTML = column1HTML;
    els.sheetColumn2.innerHTML = column2HTML;
}

// Hiển thị câu hỏi
function renderQuestion(idx) {
    currentIdx = idx;
    const q = examData[idx];

    els.qNum.innerText = `Câu hỏi ${idx + 1}/${examData.length}`;

    const savedAns = userAnswers[q.id];
    const optionsHTML = q.options
        .map((opt, i) => `
            <label class="option-item">
                <input type="radio" name="currentQuestion" class="option-radio"
                       value="${i}" ${savedAns === i ? "checked" : ""}
                       onchange="selectAnswer(${q.id}, ${i})">
                <span class="option-text"><b>${String.fromCharCode(65 + i)}.</b> ${opt}</span>
            </label>
        `).join("");

    els.qContent.innerHTML = `
        <div class="q-content-text">${q.content}</div>
        <div class="q-options-list">${optionsHTML}</div>
    `;

    els.btnPrev.disabled = idx === 0;
    els.btnNext.disabled = idx === examData.length - 1;

    updateFlagButtonUI();

    document.querySelectorAll(".sheet-q-num").forEach(el => el.classList.remove("active"));
    const currentLabel = document.getElementById(`q-label-${idx}`);
    if (currentLabel) currentLabel.classList.add("active");

    const currentRow = document.getElementById(`row-${q.id}`);
    if (currentRow) currentRow.scrollIntoView({ behavior: "smooth", block: "center" });
}

// Chọn đáp án
function selectAnswer(qId, optIdx) {
    userAnswers[qId] = optIdx;

    [0, 1, 2, 3].forEach(i => {
        const cell = document.getElementById(`cell-${qId}-${i}`);
        if (cell) cell.classList.remove("checked");
    });

    const selectedCell = document.getElementById(`cell-${qId}-${optIdx}`);
    if (selectedCell) selectedCell.classList.add("checked");

    if (examData[currentIdx].id === qId) {
        const radios = document.getElementsByName("currentQuestion");
        if (radios[optIdx]) radios[optIdx].checked = true;
    }
}

// Chuyển câu
function changeQuestion(step) {
    const newIdx = currentIdx + step;
    if (newIdx >= 0 && newIdx < examData.length) {
        renderQuestion(newIdx);
    }
}

// Nhảy đến câu
function goToQuestion(idx) {
    renderQuestion(idx);
}

// Đánh dấu câu
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
        label.style.backgroundColor = flaggedSet.has(qId) ? "#ffc107" : "";
    }
}

// Timer
// function startTimer() {
//     timerInterval = setInterval(() => {
//         if (timeLeft <= 0) {
//             clearInterval(timerInterval);
//             alert("Hết giờ! Bài thi tự động nộp.");
//             submitExam();
//             return;
//         }
//         timeLeft--;
//         const m = Math.floor(timeLeft / 60).toString().padStart(2, "0");
//         const s = (timeLeft % 60).toString().padStart(2, "0");
//         els.timer.innerText = `${m}:${s}`;
//     }, 1000);
// }

// Confirm submit
function confirmSubmit() {
    const doneCount = Object.keys(userAnswers).length;
    document.getElementById("modal-done").innerText = doneCount;
    document.getElementById("modal-remain").innerText = examData.length - doneCount;

    const modal = new bootstrap.Modal(document.getElementById("submitModal"));
    modal.show();
}

// Submit và chấm điểm
function submitExam() {
    //clearInterval(timerInterval);

    let correctCount = 0;
    const detailedResults = examData.map((q, idx) => {
        const userAns = userAnswers[q.id];
        const isCorrect = userAns === q.correctAnswer;
        if (isCorrect) correctCount++;

        return {
            questionNumber: idx + 1,
            questionContent: q.content,
            options: q.options,
            userAnswer: userAns,
            correctAnswer: q.correctAnswer,
            isCorrect: isCorrect,
            status: userAns === undefined ? 'skipped' : (isCorrect ? 'correct' : 'wrong')
        };
    });

    const score = (correctCount / examData.length) * TOTAL_SCORE;
    const result = {
        examTitle: EXAM_TITLE,
        examCode: EXAM_CODE,
        totalQuestions: examData.length,
        correctCount: correctCount,
        wrongCount: examData.length - correctCount - (examData.length - Object.keys(userAnswers).length),
        skippedCount: examData.length - Object.keys(userAnswers).length,
        score: score.toFixed(2),
        totalScore: TOTAL_SCORE,
        passingScore: PASSING_SCORE,
        percentage: ((correctCount / examData.length) * 100).toFixed(2),
        isPassed: score >= PASSING_SCORE,
        timeSpent: (window.EXAM_DURATION_SECONDS || 3600) - timeLeft,
        detailedResults: detailedResults
    };

    sessionStorage.setItem('examResult', JSON.stringify(result));
    showResultModal(result);
}

// Hiển thị kết quả
function showResultModal(result) {
    const modal = `
    <div class="modal fade" id="resultModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-${result.isPassed ? 'success' : 'danger'} text-white">
                    <h5><i class="bi bi-${result.isPassed ? 'check-circle' : 'x-circle'}"></i> Kết quả bài thi</h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h3>${result.examTitle}</h3>
                        <p class="text-muted">Mã: ${result.examCode}</p>
                    </div>
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <h1 class="display-4 text-${result.isPassed ? 'success' : 'danger'}">${result.score}/${result.totalScore}</h1>
                            <p>Điểm số</p>
                        </div>
                        <div class="col-6">
                            <h1 class="display-4 text-primary">${result.percentage}%</h1>
                            <p>Tỷ lệ đúng</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 text-center text-success">
                            <i class="bi bi-check-circle fs-3"></i>
                            <p><strong>${result.correctCount}</strong><br>Đúng</p>
                        </div>
                        <div class="col-4 text-center text-danger">
                            <i class="bi bi-x-circle fs-3"></i>
                            <p><strong>${result.wrongCount}</strong><br>Sai</p>
                        </div>
                        <div class="col-4 text-center text-warning">
                            <i class="bi bi-dash-circle fs-3"></i>
                            <p><strong>${result.skippedCount}</strong><br>Bỏ qua</p>
                        </div>
                    </div>
                    ${result.isPassed ?
            '<div class="alert alert-success"><i class="bi bi-trophy"></i> Chúc mừng! Bạn đã đạt!</div>' :
            '<div class="alert alert-danger"><i class="bi bi-emoji-frown"></i> Chưa đạt! Cần: ' + result.passingScore + '</div>'
        }
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="viewDetailedResults()">
                        <i class="bi bi-eye"></i> Xem chi tiết
                    </button>
                    <button class="btn btn-secondary" onclick="location.reload()">
                        <i class="bi bi-arrow-clockwise"></i> Làm lại
                    </button>
                    <a href="/" class="btn btn-success">
                        <i class="bi bi-house"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>`;

    document.body.insertAdjacentHTML('beforeend', modal);
    new bootstrap.Modal(document.getElementById('resultModal')).show();
}

// Xem chi tiết
function viewDetailedResults() {
    const result = JSON.parse(sessionStorage.getItem('examResult'));
    let html = `<!DOCTYPE html>
    <html><head><meta charset="UTF-8">
    <title>Chi tiết - ${result.examTitle}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    </head><body class="bg-light"><div class="container py-4">
    <div class="card mb-3"><div class="card-header bg-primary text-white">
    <h4>Chi tiết - ${result.examTitle}</h4>
    <small>Điểm: ${result.score}/${result.totalScore} (${result.percentage}%)</small>
    </div></div>`;

    result.detailedResults.forEach(item => {
        const c = item.status === 'correct' ? 'success' : (item.status === 'wrong' ? 'danger' : 'warning');
        html += `<div class="card mb-2 border-${c}">
        <div class="card-header bg-${c} bg-opacity-10">
            <strong>Câu ${item.questionNumber}</strong>
            <span class="badge bg-${c} float-end">${item.status === 'correct' ? 'Đúng' : (item.status === 'wrong' ? 'Sai' : 'Bỏ qua')}</span>
        </div>
        <div class="card-body">
            <p><strong>Câu hỏi:</strong> ${item.questionContent}</p>
            <p><strong>Đáp án của bạn:</strong> <span class="text-${c}">${item.userAnswer !== null && item.userAnswer !== undefined ? String.fromCharCode(65 + item.userAnswer) + '. ' + item.options[item.userAnswer] : 'Không trả lời'}</span></p>
            ${item.status !== 'correct' ? `<p><strong>Đáp án đúng:</strong> <span class="text-success">${String.fromCharCode(65 + item.correctAnswer)}. ${item.options[item.correctAnswer]}</span></p>` : ''}
        </div></div>`;
    });

    html += '<div class="text-center"><button class="btn btn-secondary" onclick="window.close()">Đóng</button></div></div></body></html>';

    const win = window.open('', '_blank');
    win.document.write(html);
    win.document.close();
}

// Export functions
window.changeQuestion = changeQuestion;
window.goToQuestion = goToQuestion;
window.selectAnswer = selectAnswer;
window.toggleFlag = toggleFlag;
window.confirmSubmit = confirmSubmit;
window.submitExam = submitExam;
window.viewDetailedResults = viewDetailedResults;

// Load đề thi từ API
async function loadExam() {
    try {
        const response = await fetch(`/exams/${EXAM_ID}/randomized?limit=30`);
        const data = await response.json();

        examData = data.questions.map((q) => ({
            id: q.id,
            content: q.content,
            options: q.options.map(opt => opt.content),
            correctAnswer: q.options.findIndex(opt => opt.is_correct === 1)
        }));

        console.log('Đã load:', examData);

        if (examData.length > 0) {
            //window.EXAM_DURATION_SECONDS = timeLeft; // Lưu thời gian ban đầu
            initSheet();
            renderQuestion(0);
            //startTimer();
        } else {
            els.qContent.innerHTML = '<div class="alert alert-warning">Chưa có câu hỏi nào!</div>';
        }
    } catch (error) {
        console.error('Lỗi:', error);
        els.qContent.innerHTML = '<div class="alert alert-danger">Lỗi load đề thi!</div>';
    }
}

// Auto load khi trang sẵn sàng
document.addEventListener('DOMContentLoaded', function () {
    loadExam();
});