<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hệ thống ôn thi trắc nghiệm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

    @include('partials.style')

  </head>
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
    <script>
      /* =========================================
       1. SINH DỮ LIỆU GIẢ (40 CÂU HỎI)
       ========================================= */
      const TOTAL_QUESTIONS = 30
      const EXAM_DURATION = 60 * 60 // 60 phút tính bằng giây

      function generateMockData() {
        const data = []
        for (let i = 1; i <= TOTAL_QUESTIONS; i++) {
          data.push({
            id: i,
            content: `Đây là nội dung câu hỏi số <b>${i}</b>. Hãy chọn phương án đúng nhất trong các lựa chọn dưới đây? <br> (Nội dung demo để test giao diện hiển thị).`,
            options: [`Phương án A cho câu hỏi ${i}`, `Phương án B`, `Phương án C là đáp án đúng`, `Phương án D`],
          })
        }
        return data
      }

      const examData = generateMockData()

      /* =========================================
       2. STATE QUẢN LÝ (TRẠNG THÁI)
       ========================================= */
      let currentIdx = 0 // Đang xem câu index 0 (câu 1)
      let userAnswers = {} // Lưu đáp án: { 1: 0, 2: 3 } (câu 1 chọn A, câu 2 chọn D)
      let flaggedSet = new Set() // Các câu đánh dấu
      let timeLeft = EXAM_DURATION
      let timerInterval

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
      }

      /* =========================================
       4. HÀM RENDER (HIỂN THỊ)
       ========================================= */

      // Khởi tạo bảng trả lời (Chạy 1 lần đầu)
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
                              onclick="selectAnswer(${q.id}, ${optIdx})"></span>
                    </td>
                `
                  )
                  .join("")}
            </tr>
        `
          )
          .join("")
      }

      // Hiển thị câu hỏi chi tiết ở giữa
      function renderQuestion(idx) {
        currentIdx = idx
        const q = examData[idx]

        // Update Tiêu đề
        els.qNum.innerText = `Nội dung câu hỏi ${q.id}`

        // Update nội dung & đáp án (Radio buttons)
        const savedAns = userAnswers[q.id] // Đáp án đã chọn trước đó (nếu có)

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
          .join("")

        els.qContent.innerHTML = `
            <div class="q-content-text">${q.content}</div>
            <div class="q-options-list">${optionsHTML}</div>
        `

        // Update nút Điều hướng
        els.btnPrev.disabled = idx === 0
        els.btnNext.disabled = idx === examData.length - 1

        // Update nút Flag
        updateFlagButtonUI()

        // Highlight dòng đang chọn bên bảng Sheet
        document.querySelectorAll(".sheet-q-num").forEach((el) => el.classList.remove("active"))
        document.getElementById(`q-label-${idx}`).classList.add("active")

        // Cuộn bảng sheet đến câu đang làm (nếu bảng dài quá)
        document.getElementById(`row-${q.id}`).scrollIntoView({ behavior: "smooth", block: "center" })
      }

      /* =========================================
       5. LOGIC XỬ LÝ (ACTION)
       ========================================= */

      // Xử lý khi chọn đáp án (Từ Radio hoặc từ Bảng Sheet)
      function selectAnswer(qId, optIdx) {
        // 1. Lưu vào State
        userAnswers[qId] = optIdx

        // 2. Cập nhật UI Bảng Sheet (Tô đen ô)
        // Reset dòng đó trước
        ;[0, 1, 2, 3].forEach((i) => {
          document.getElementById(`cell-${qId}-${i}`).classList.remove("checked")
        })
        // Tô màu ô mới
        document.getElementById(`cell-${qId}-${optIdx}`).classList.add("checked")

        // 3. Nếu đang đứng ở câu đó thì tick radio button tương ứng
        // (Xử lý trường hợp click trên bảng sheet thì radio ở giữa cũng nhảy theo)
        if (examData[currentIdx].id === qId) {
          const radios = document.getElementsByName("currentQuestion")
          if (radios[optIdx]) radios[optIdx].checked = true
        }
      }

      // Chuyển câu hỏi
      function changeQuestion(step) {
        const newIdx = currentIdx + step
        if (newIdx >= 0 && newIdx < examData.length) {
          renderQuestion(newIdx)
        }
      }

      // Nhảy đến câu bất kỳ từ bảng sheet
      function goToQuestion(idx) {
        renderQuestion(idx)
      }

      // Đánh dấu (Flag)
      function toggleFlag() {
        const qId = examData[currentIdx].id
        if (flaggedSet.has(qId)) {
          flaggedSet.delete(qId)
        } else {
          flaggedSet.add(qId)
        }
        updateFlagButtonUI()
        updateSheetFlagUI(qId)
      }

      function updateFlagButtonUI() {
        const qId = examData[currentIdx].id
        if (flaggedSet.has(qId)) {
          els.btnFlag.classList.remove("btn-outline-warning")
          els.btnFlag.classList.add("btn-warning")
          els.btnFlag.innerHTML = '<i class="bi bi-flag-fill"></i> Đã đánh dấu'
        } else {
          els.btnFlag.classList.add("btn-outline-warning")
          els.btnFlag.classList.remove("btn-warning")
          els.btnFlag.innerHTML = '<i class="bi bi-flag"></i> Đánh dấu'
        }
      }

      function updateSheetFlagUI(qId) {
        // Tìm dòng tương ứng trong sheet để đổi màu hoặc thêm icon (Demo đổi màu số câu)
        const label = document.getElementById(`q-label-${currentIdx}`)
        // Ở đây đơn giản ta cho ô số câu hỏi màu vàng
        if (flaggedSet.has(qId)) {
          label.style.backgroundColor = "#ffc107"
        } else {
          label.style.backgroundColor = "" // Reset
          // Add lại class active nếu đang active
          if (currentIdx === qId - 1) label.classList.add("active")
        }
      }

      /* =========================================
       6. TIMER VÀ SUBMIT
       ========================================= */
      function startTimer() {
        timerInterval = setInterval(() => {
          if (timeLeft <= 0) {
            clearInterval(timerInterval)
            alert("Hết giờ làm bài!")
            return
          }
          timeLeft--
          const m = Math.floor(timeLeft / 60)
            .toString()
            .padStart(2, "0")
          const s = (timeLeft % 60).toString().padStart(2, "0")
          els.timer.innerText = `${m}:${s}`
        }, 1000)
      }

      function confirmSubmit() {
        const doneCount = Object.keys(userAnswers).length
        document.getElementById("modal-done").innerText = doneCount
        document.getElementById("modal-remain").innerText = TOTAL_QUESTIONS - doneCount

        const myModal = new bootstrap.Modal(document.getElementById("submitModal"))
        myModal.show()
      }

      /* =========================================
       7. MAIN RUN
       ========================================= */
      // Chạy khi load trang
      initSheet() // Vẽ bảng 40 câu
      renderQuestion(0) // Vào câu 1
      startTimer() // Đếm giờ
    </script>
  </body>
</html>
