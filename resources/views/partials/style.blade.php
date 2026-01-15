<style>

      html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
      /* === GLOBAL STYLES & COLORS === */
      :root {
        --theme-orange: #f39c12; /* Màu cam chủ đạo nút/timer */
        --theme-blue: #0056b3; /* Màu xanh footer/header */
        --bg-light: #f4f6f9;
      }

      body {
        background-color: var(--bg-light);
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
      }

      /* === CỘT 1: THÔNG TIN THÍ SINH === */
      .student-card {
        background: #fff;
        border: 1px solid #ddd;
        padding: 15px;
        font-size: 0.9rem;
      }
      .student-avatar {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border: 1px solid #ccc;
        padding: 2px;
        background: #fff;
      }
      .info-label {
        color: #666;
        width: 90px;
        display: inline-block;
      }
      .info-value {
        color: var(--theme-blue);
        font-weight: 600;
      }

      /* === CỘT 2: KHUNG CÂU HỎI (CENTER) === */
      .question-box {
        background: #fff;
        border: 1px solid #ccc;
        height: 100%;
        display: flex;
        flex-direction: column;
      }
      .q-header {
        padding: 10px 15px;
        border-bottom: 2px solid var(--theme-orange);
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .q-title {
        color: var(--theme-orange);
        font-weight: bold;
        font-size: 1.1rem;
        margin: 0;
      }
      .q-body {
        padding: 20px;
        flex-grow: 1;
        overflow-y: auto;
        min-height: 400px;
      }
      .q-content-text {
        font-size: 1.1rem;
        margin-bottom: 20px;
        color: #333;
      }
      /* Custom Radio Style */
      .option-item {
        margin-bottom: 12px;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
      }
      .option-radio {
        margin-top: 5px;
        margin-right: 10px;
        transform: scale(1.2);
        cursor: pointer;
      }
      .option-text {
        font-size: 1rem;
      }

      /* Nút điều hướng dưới chân câu hỏi */
      .nav-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 15px;
        margin-bottom: 15px;
      }
      .btn-nav {
        background-color: var(--theme-orange);
        color: white;
        border: none;
        padding: 8px 25px;
        font-weight: bold;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 5px;
      }
      .btn-nav:hover {
        background-color: #e67e22;
        color: white;
      }
      .btn-nav:disabled {
        background-color: #f8c291;
      }

      /* === CỘT 3: TIMER & ANSWER SHEET (RIGHT) === */
      .timer-box {
        background-color: var(--theme-orange);
        color: white;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 10px;
        font-size: 0.9rem;
      }
      .timer-countdown {
        font-size: 1.2rem;
        font-weight: bold;
        text-align: right;
      }

      /* Bảng trả lời mô phỏng giấy thi */
      .sheet-wrapper {
        max-height: 500px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ccc;
      }
      .table-sheet {
        width: 100%;
        text-align: center;
        font-size: 0.85rem;
        border-collapse: collapse;
      }
      .table-sheet th {
        background: #eee;
        position: sticky;
        top: 0;
        z-index: 10;
        border: 1px solid #ccc;
        padding: 5px;
      }
      .table-sheet td {
        border: 1px solid #ccc;
        padding: 4px;
      }
      .sheet-q-num {
        font-weight: bold;
        background: #f9f9f9;
        cursor: pointer;
      }
      .sheet-q-num.active {
        background-color: #d1ecf1; /* Highlight câu đang làm */
        color: #0c5460;
      }
      .sheet-check {
        width: 16px;
        height: 16px;
        border: 1px solid #999;
        border-radius: 3px; /* Hình vuông bo nhẹ */
        display: inline-block;
        cursor: pointer;
      }
      .sheet-check:hover {
        background-color: #eee;
      }
      .sheet-check.checked {
        background-color: #333; /* Tô đen ô giống tô trắc nghiệm */
        border-color: #000;
      }
      .sheet-check.flagged {
        background-color: #ffc107; /* Màu vàng đánh dấu */
        border-color: #ffc107;
      }

      .btn-submit {
        background-color: var(--theme-blue);
        color: white;
        width: 100%;
        padding: 10px;
        font-weight: bold;
        border: none;
        margin-top: 10px;
      }
      .btn-submit:hover {
        background-color: #004494;
      }

      /* === FOOTER === */
      .main-footer {
        margin-top: auto;
        background-color: var(--theme-blue);
        color: white;
        padding: 15px 0;
        font-size: 0.85rem;
      }
      .footer-logo {
        background: white;
        border-radius: 50%;
        padding: 5px;
        width: 80px;
        height: 80px;
        object-fit: contain;
      }

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
    </style>
