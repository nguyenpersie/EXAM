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

    const imageHTML = q.image
        ? `<div class="text-center mb-3"><img src="/storage/${q.image}" class="img-fluid rounded" style="max-height: 300px;" alt="Hình minh họa"></div>`
        : '';

    els.qContent.innerHTML = `
        ${imageHTML}
        <div class="q-content-text">${q.content}</div>
        <div class="q-options-list">${optionsHTML}</div>
    `;

    els.btnPrev.disabled = idx === 0;
    els.btnNext.disabled = idx === examData.length - 1;

    updateFlagButtonUI();

    document.querySelectorAll(".sheet-q-num").forEach(el => el.classList.remove("active"));
    const currentLabel = document.getElementById(`q-label-${idx}`);
    if (currentLabel) currentLabel.classList.add("active");

    // Đã bỏ auto-scroll theo yêu cầu
}

// ... existing code ...

// Load đề thi từ API
async function loadExam() {
    try {
        const response = await fetch(`/exams/${EXAM_ID}/randomized?limit=30`);
        const data = await response.json();

        examData = data.questions.map((q) => ({
            id: q.id,
            content: q.content,
            image: q.image,
            options: q.options.map(opt => opt.content),
            correctAnswer: q.options.findIndex(opt => opt.is_correct === 1)
        }));

        console.log('Đã load:', examData);

        if (examData.length > 0) {
            window.EXAM_DURATION_SECONDS = timeLeft; // Lưu thời gian ban đầu
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