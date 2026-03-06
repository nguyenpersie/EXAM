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

    // Generate options HTML with review mode support
    const optionsHTML = q.options
        .map((opt, i) => {
            let classes = 'option-item';
            let disabled = '';

            if (isReviewMode) {
                // In review mode, show correct/incorrect feedback
                if (i === q.correctAnswer) {
                    classes += ' correct-answer'; // Always mark the correct answer
                }
                if (savedAns === i) {
                    // User selected this option
                    if (i === q.correctAnswer) {
                        classes += ' correct';
                    } else {
                        classes += ' incorrect';
                    }
                }
                disabled = 'disabled';
            }

            return `
                <label class="${classes}">
                    <input type="radio" name="currentQuestion" class="option-radio"
                           value="${i}" ${savedAns === i ? "checked" : ""} ${disabled}
                           onchange="selectAnswer(${q.id}, ${i})">
                    <span class="option-text"><b>${String.fromCharCode(65 + i)}.</b> ${opt}</span>
                </label>
            `;
        }).join("");

    const imageHTML = q.image
        ? `<div class="text-center mb-3"><img src="/storage/${q.image}" class="img-fluid rounded" style="max-height: 300px;" alt="Hình minh họa"></div>`
        : '';

    const containerClass = isReviewMode ? 'q-options-list review-mode' : 'q-options-list';
    els.qContent.innerHTML = `
        <div class="q-content-text">${q.content}</div>
        ${imageHTML}
        <div class="${containerClass}">${optionsHTML}</div>
    `;

    els.btnPrev.disabled = idx === 0;
    els.btnNext.disabled = idx === examData.length - 1;

    updateFlagButtonUI();

    document.querySelectorAll(".sheet-q-num").forEach(el => el.classList.remove("active"));
    const currentLabel = document.getElementById(`q-label-${idx}`);
    if (currentLabel) currentLabel.classList.add("active");

    // Đã bỏ auto-scroll theo yêu cầu
}

// Chọn đáp án
function selectAnswer(qId, optIdx) {
    if (isReviewMode) return;

    userAnswers[qId] = optIdx;

    [0, 1, 2, 3].forEach(i => {
        const cell = document.getElementById(`cell-${qId}-${i}`);
        if (cell) cell.classList.remove("checked", "correct", "incorrect");
    });

    const selectedCell = document.getElementById(`cell-${qId}-${optIdx}`);
    if (selectedCell) selectedCell.classList.add("checked");

    if (examData[currentIdx].id === qId) {
        const radios = document.getElementsByName("currentQuestion");
        if (radios[optIdx]) radios[optIdx].checked = true;

        // Practice mode: Show immediate feedback
        if (isPracticeMode) {
            const q = examData[currentIdx];
            const isCorrect = optIdx === q.correctAnswer;

            // Update answer sheet cell with correct/incorrect
            if (isCorrect) {
                if (selectedCell) selectedCell.classList.add("correct");
            } else {
                if (selectedCell) selectedCell.classList.add("incorrect");
                // Also mark the correct answer
                const correctCell = document.getElementById(`cell-${qId}-${q.correctAnswer}`);
                if (correctCell) correctCell.classList.add("correct");
            }

            // Re-render question to show feedback on options
            renderQuestionWithFeedback(currentIdx);
        }
    }
}

// Render question with immediate feedback (practice mode)
function renderQuestionWithFeedback(idx) {
    const q = examData[idx];
    const savedAns = userAnswers[q.id];

    if (savedAns === undefined) return; // No answer yet

    const optionsHTML = q.options
        .map((opt, i) => {
            let classes = 'option-item';

            // Mark correct answer
            if (i === q.correctAnswer) {
                classes += ' correct-answer';
            }

            // Mark user's selection
            if (savedAns === i) {
                if (i === q.correctAnswer) {
                    classes += ' correct';
                } else {
                    classes += ' incorrect';
                }
            }

            return `
                <label class="${classes}">
                    <input type="radio" name="currentQuestion" class="option-radio"
                           value="${i}" ${savedAns === i ? "checked" : ""}
                           onchange="selectAnswer(${q.id}, ${i})">
                    <span class="option-text"><b>${String.fromCharCode(65 + i)}.</b> ${opt}</span>
                </label>
            `;
        }).join("");

    const imageHTML = q.image
        ? `<div class="text-center mb-3"><img src="/storage/${q.image}" class="img-fluid rounded" style="max-height: 300px;" alt="Hình minh họa"></div>`
        : '';

    els.qContent.innerHTML = `
        <div class="q-content-text">${q.content}</div>
        ${imageHTML}
        <div class="q-options-list practice-mode">${optionsHTML}</div>
    `;
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

//Timer
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
                    <h5 class="modal-title">
                    <i class="bi bi-${result.isPassed ? 'check-circle' : 'x-circle'}"></i> Kết quả bài thi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h3>${result.examTitle}</h3>
                        <p class="text-muted">Mã: ${result.examCode}</p>
                    </div>
                    <div class="text-center mb-3">
                        <img src="/assets/images/${result.isPassed ? 'catlike' : 'danceshiba'}.gif" 
                             alt="${result.isPassed ? 'Passed' : 'Failed'}" 
                             style="max-width: 150px; height: auto; border-radius: 10px;">
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
            '<div class="alert-simple alert-success"><i class="start-icon bi bi-trophy faa-tada animated"></i> <strong>Chúc mừng!</strong> Bạn đã đạt!</div>' :
            '<div class="alert-simple alert-danger"><i class="start-icon bi bi-emoji-frown faa-pulse animated"></i> <strong>Chưa đạt!</strong> Bạn cần: ' + result.passingScore + ' câu</div>'
        }
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" onclick="showAnswerReview()">
                        <i class="bi bi-eye-fill"></i> Xem đáp án
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
// Export functions
window.changeQuestion = changeQuestion;
window.goToQuestion = goToQuestion;
window.selectAnswer = selectAnswer;
window.toggleFlag = toggleFlag;
window.confirmSubmit = confirmSubmit;
window.submitExam = submitExam;

// Practice mode variables (read from URL)
const urlParams = new URLSearchParams(window.location.search);
const isPracticeMode = urlParams.get('mode') === 'practice';
const practiceSection = urlParams.get('section');

// Hiển thị chế độ xem lại đáp án sau khi nộp bài
window.showAnswerReview = function () {
    isReviewMode = true;

    // Đóng modal kết quả
    const modal = bootstrap.Modal.getInstance(document.getElementById('resultModal'));
    if (modal) modal.hide();

    // Xóa onclick trực tiếp khỏi tất cả ô ABCD trong bảng trả lời
    document.querySelectorAll('.sheet-check').forEach(cell => {
        cell.onclick = null;
        cell.style.cursor = 'default';
    });

    // Tô màu đúng/sai trên bảng trả lời
    examData.forEach(q => {
        const userAns = userAnswers[q.id];

        // Xóa màu cũ
        [0, 1, 2, 3].forEach(i => {
            const cell = document.getElementById(`cell-${q.id}-${i}`);
            if (cell) cell.classList.remove('checked', 'correct', 'incorrect');
        });

        // Tô xanh ô đáp án đúng
        const correctCell = document.getElementById(`cell-${q.id}-${q.correctAnswer}`);
        if (correctCell) correctCell.classList.add('correct');

        // Tô xanh/đỏ ô thí sinh đã chọn
        if (userAns !== undefined) {
            const userCell = document.getElementById(`cell-${q.id}-${userAns}`);
            if (userCell) userCell.classList.add(userAns === q.correctAnswer ? 'correct' : 'incorrect');
        }
    });

    // Hiển thị câu hỏi hiện tại ở chế độ xem lại
    renderQuestion(currentIdx);
};

// Load đề thi từ API
async function loadExam() {
    try {
        // Build API URL with mode and section params
        let apiUrl = `/exams/${EXAM_ID}/randomized?limit=30`;
        if (isPracticeMode && practiceSection) {
            apiUrl = `/exams/${EXAM_ID}/randomized?mode=practice&section=${practiceSection}`;
        }

        console.log('Practice Mode:', isPracticeMode);
        console.log('Section:', practiceSection);
        console.log('API URL:', apiUrl);

        const response = await fetch(apiUrl);
        const data = await response.json();

        examData = data.questions.map((q) => ({
            id: q.id,
            content: q.content,
            image: q.image,
            options: q.options.map(opt => opt.content),
            correctAnswer: q.options.findIndex(opt => opt.is_correct === 1)
        }));

        console.log('Đã load:', examData.length, 'câu hỏi');

        if (examData.length > 0) {
            window.EXAM_DURATION_SECONDS = timeLeft; // Lưu thời gian ban đầu

            // Hide answer sheet in practice mode
            if (isPracticeMode) {
                const answerSheet = document.querySelector('.sheet-panel');
                if (answerSheet) {
                    answerSheet.style.display = 'none';
                }
                // Update title to show practice mode
                const titleEl = document.querySelector('.exam-header h4');
                if (titleEl) {
                    titleEl.innerHTML = `<i class="bi bi-book"></i> Ôn Tập - Phần ${practiceSection}`;
                }
                // Hide submit button
                const submitBtn = document.querySelector('button[onclick="confirmSubmit()"]');
                if (submitBtn) {
                    submitBtn.style.display = 'none';
                }
            }

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