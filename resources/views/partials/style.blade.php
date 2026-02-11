<style>
  html,
  body {
    height: 100%;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  /* === GLOBAL STYLES & COLORS === */
  :root {
    --theme-orange: #f39c12;
    /* Màu cam chủ đạo nút/timer */
    --theme-blue: #0056b3;
    /* Màu xanh footer/header */
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
    background-color: #d1ecf1;
    /* Highlight câu đang làm */
    color: #0c5460;
  }

  .sheet-check {
    width: 16px;
    height: 16px;
    border: 1px solid #999;
    border-radius: 3px;
    /* Hình vuông bo nhẹ */
    display: inline-block;
    cursor: pointer;
  }

  .sheet-check:hover {
    background-color: #eee;
  }

  .sheet-check.checked {
    background-color: #333;
    /* Tô đen ô giống tô trắc nghiệm */
    border-color: #000;
  }

  .sheet-check.flagged {
    background-color: #ffc107;
    /* Màu vàng đánh dấu */
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

  /* Reset cho breadcrumb */
  .breadcrumb {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    list-style-type: none;
  }

  .breadcrumb * {
    box-sizing: border-box;
    list-style-type: none;
    text-decoration: none;
  }

  /* Breadcrumb container */
  .breadcrumb {
    display: flex;
    box-shadow: 0 8px 14px -2px rgba(0, 0, 0, 0.1),
      0 4px 6px -2px rgba(0, 0, 0, 0.05);
    padding: 0.75rem 1.25rem;
    border-radius: 35px;
    margin-bottom: 1.5rem;
    background-color: #ffffff;
  }

  .breadcrumb-links {
    display: flex;
    column-gap: 1rem;
    align-items: center;
    margin: 0;
    padding: 0;
  }

  .breadcrumb-links>li {
    list-style: none;
  }

  .breadcrumb-links>li:nth-child(n + 4) {
    display: none;
  }

  .breadcrumb-box {
    display: flex;
    align-items: center;
    text-decoration: none;
  }

  .breadcrumb-link {
    color: #9ca3af;
  }

  .breadcrumb-box:hover>*:not(.breadcrumb-icon) {
    color: #4f46e5;
  }

  .breadcrumb-icon,
  .breadcrumb-icon-home {
    flex-shrink: 0;
    width: 1.25rem;
    height: 1.25rem;
    color: #9ca3af;
  }

  .breadcrumb-links li:first-child .breadcrumb-text {
    display: none;
  }

  .breadcrumb-text {
    margin-left: 1rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    font-weight: 500;
    color: #6b7280;
    text-decoration: none;
  }

  .breadcrumb-text:hover {
    color: #4f46e5;
  }

  .breadcrumb-text-active {
    color: #374151;
    font-weight: 600;
  }

  /* Responsive */
  @media (min-width: 640px) {
    .breadcrumb-links>li:nth-child(n + 4) {
      display: block;
    }

    .breadcrumb-links li:first-child .breadcrumb-text {
      display: block;
    }
  }

  /* Custom Tabs Wrapper */
  .custom-tabs-wrapper {
    margin-bottom: 1.5rem;
  }

  /* Custom Tabs Container */
  .custom-tabs {
    display: flex;
    gap: 0.75rem;
    padding: 0;
    margin: 0;
    list-style: none;
    border-bottom: none;
  }

  .custom-tab-item {
    list-style: none;
  }

  /* Tab Link/Button */
  .custom-tab-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 35px;
    background-color: #f3f4f6;
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  .custom-tab-link:hover {
    background-color: #e5e7eb;
    color: #374151;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
      0 2px 4px -1px rgba(0, 0, 0, 0.06);
  }

  .custom-tab-link.active {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #ffffff;
    box-shadow: 0 8px 14px -2px rgba(79, 70, 229, 0.3),
      0 4px 6px -2px rgba(79, 70, 229, 0.2);
  }

  .custom-tab-link.active:hover {
    background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
    box-shadow: 0 10px 18px -2px rgba(79, 70, 229, 0.4),
      0 6px 8px -2px rgba(79, 70, 229, 0.3);
  }

  /* Tab Icon */
  .custom-tab-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
  }

  .custom-tab-link:not(.active) .custom-tab-icon {
    color: #9ca3af;
  }

  .custom-tab-link.active .custom-tab-icon {
    color: #ffffff;
  }

  /* Tab Text */
  .custom-tab-text {
    font-size: 0.875rem;
    line-height: 1.25rem;
    font-weight: 500;
  }

  /* Responsive */
  @media (max-width: 640px) {
    .custom-tabs {
      flex-direction: column;
      gap: 0.5rem;
    }

    .custom-tab-link {
      width: 100%;
      justify-content: center;
      padding: 0.875rem 1.25rem;
    }

    .custom-tab-text {
      font-size: 0.9375rem;
    }
  }

  /* Focus state cho accessibility */
  .custom-tab-link:focus {
    outline: 2px solid #4f46e5;
    outline-offset: 2px;
  }

  .custom-tab-link:focus:not(:focus-visible) {
    outline: none;
  }

  /* ===== 3D BUTTON STYLES ===== */

  /* Base button */
  .btn-3d {
    position: relative;
    border: none;
    background: transparent;
    padding: 0;
    cursor: pointer;
    outline-offset: 4px;
    transition: filter 250ms;
    user-select: none;
    touch-action: manipulation;
    text-decoration: none;
    display: inline-block;
  }

  /* Shadow layer */
  .btn-3d-shadow {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 12px;
    background: hsl(0deg 0% 0% / 0.25);
    will-change: transform;
    transform: translateY(2px);
    transition: transform 600ms cubic-bezier(.3, .7, .4, 1);
  }

  /* Edge layer (base colors) */
  .btn-3d-edge {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 12px;
  }

  /* Front layer */
  .btn-3d-front {
    display: block;
    position: relative;
    padding: 12px 27px;
    border-radius: 12px;
    font-size: 1rem;
    color: white;
    font-weight: 500;
    will-change: transform;
    transform: translateY(-4px);
    transition: transform 600ms cubic-bezier(.3, .7, .4, 1);
    white-space: nowrap;
  }

  /* Hover effects */
  .btn-3d:hover {
    filter: brightness(110%);
    text-decoration: none;
  }

  .btn-3d:hover .btn-3d-front {
    transform: translateY(-6px);
    transition: transform 250ms cubic-bezier(.3, .7, .4, 1.5);
  }

  .btn-3d:active .btn-3d-front {
    transform: translateY(-2px);
    transition: transform 34ms;
  }

  .btn-3d:hover .btn-3d-shadow {
    transform: translateY(4px);
    transition: transform 250ms cubic-bezier(.3, .7, .4, 1.5);
  }

  .btn-3d:active .btn-3d-shadow {
    transform: translateY(1px);
    transition: transform 34ms;
  }

  .btn-3d:focus:not(:focus-visible) {
    outline: none;
  }

  /* ===== COLOR VARIANTS ===== */

  /* Success (Green) - Nút Thi thử */
  .btn-3d-success .btn-3d-edge {
    background: linear-gradient(to left,
        hsl(145deg 63% 32%) 0%,
        hsl(145deg 63% 42%) 8%,
        hsl(145deg 63% 42%) 92%,
        hsl(145deg 63% 32%) 100%);
  }

  .btn-3d-success .btn-3d-front {
    background: hsl(145deg 63% 49%);
  }

  /* Primary (Blue) - Nút Ôn tập */
  .btn-3d-primary .btn-3d-edge {
    background: linear-gradient(to left,
        hsl(217deg 91% 40%) 0%,
        hsl(217deg 91% 50%) 8%,
        hsl(217deg 91% 50%) 92%,
        hsl(217deg 91% 40%) 100%);
  }

  .btn-3d-primary .btn-3d-front {
    background: hsl(217deg 91% 60%);
  }

  /* Secondary (Gray) - Nút Edit */
  .btn-3d-secondary .btn-3d-edge {
    background: linear-gradient(to left,
        hsl(215deg 16% 37%) 0%,
        hsl(215deg 16% 47%) 8%,
        hsl(215deg 16% 47%) 92%,
        hsl(215deg 16% 37%) 100%);
  }

  .btn-3d-secondary .btn-3d-front {
    background: hsl(215deg 16% 57%);
    padding: 12px 20px;
    /* Nhỏ hơn một chút cho nút icon */
  }

  /* Danger (Red) - Nếu cần */
  .btn-3d-danger .btn-3d-edge {
    background: linear-gradient(to left,
        hsl(340deg 100% 16%) 0%,
        hsl(340deg 100% 32%) 8%,
        hsl(340deg 100% 32%) 92%,
        hsl(340deg 100% 16%) 100%);
  }

  .btn-3d-danger .btn-3d-front {
    background: hsl(345deg 100% 47%);
  }

  /* Warning (Yellow/Orange) - Nếu cần */
  .btn-3d-warning .btn-3d-edge {
    background: linear-gradient(to left,
        hsl(32deg 95% 34%) 0%,
        hsl(32deg 95% 44%) 8%,
        hsl(32deg 95% 44%) 92%,
        hsl(32deg 95% 34%) 100%);
  }

  .btn-3d-warning .btn-3d-front {
    background: hsl(32deg 95% 54%);
  }

  /* Info (Cyan) - Nếu cần */
  .btn-3d-info .btn-3d-edge {
    background: linear-gradient(to left,
        hsl(188deg 78% 31%) 0%,
        hsl(188deg 78% 41%) 8%,
        hsl(188deg 78% 41%) 92%,
        hsl(188deg 78% 31%) 100%);
  }

  .btn-3d-info .btn-3d-front {
    background: hsl(188deg 78% 51%);
  }

  /* Icon spacing */
  .btn-3d-front i {
    margin-right: 6px;
    font-size: 1.1em;
  }

  .btn-3d-secondary .btn-3d-front i {
    margin-right: 0;
    /* Nút chỉ có icon thì không cần margin */
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .btn-3d-front {
      padding: 10px 20px;
      font-size: 0.9rem;
    }

    .btn-3d-secondary .btn-3d-front {
      padding: 10px 16px;
    }
  }

  /* Flex-fill support */
  .btn-3d.flex-fill {
    flex: 1 1 auto;
    min-width: 0;
  }
</style>