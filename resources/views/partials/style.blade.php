<link rel="stylesheet" href="{{ asset('css/modern-alerts.css') }}?v=2">
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

  /* ===== LOGOUT BUTTON STYLES ===== */

  .btn-logout {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.25);
    background-color: #dc3545;
    padding: 0;
    vertical-align: middle;
  }

  /* Icon sign */
  .btn-logout-sign {
    width: 100%;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btn-logout-sign i {
    font-size: 1.1rem;
    color: white;
  }

  /* Text */
  .btn-logout-text {
    position: absolute;
    right: 0;
    width: 0;
    opacity: 0;
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    white-space: nowrap;
    overflow: hidden;
  }

  /* Hover effect - mở rộng thành hình chữ nhật */
  .btn-logout:hover {
    width: 90px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.35);
    background-color: #c82333;
  }

  .btn-logout:hover .btn-logout-sign {
    width: 35%;
    padding-left: 8px;
  }

  /* Hover effect button's text */
  .btn-logout:hover .btn-logout-text {
    opacity: 1;
    width: 65%;
    padding-right: 10px;
  }

  /* Button click effect */
  .btn-logout:active {
    transform: scale(0.95);
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.25);
  }

  /* Focus state */
  .btn-logout:focus {
    outline: 2px solid #dc3545;
    outline-offset: 2px;
  }

  .btn-logout:focus:not(:focus-visible) {
    outline: none;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .btn-logout {
      width: 38px;
      height: 38px;
    }

    .btn-logout-sign i {
      font-size: 1rem;
    }

    .btn-logout-text {
      font-size: 0.8125rem;
    }

    .btn-logout:hover {
      width: 110px;
    }
  }

  @media (max-width: 576px) {
    .btn-logout {
      width: 36px;
      height: 36px;
      border-radius: 6px;
    }

    .btn-logout-sign i {
      font-size: 0.95rem;
    }

    .btn-logout-text {
      font-size: 0.75rem;
    }

    .btn-logout:hover {
      width: 105px;
    }

    .btn-logout:hover .btn-logout-sign {
      padding-left: 6px;
    }
  }

  /* Căn giữa với các nút khác */
  .d-inline {
    display: inline-flex !important;
    align-items: center;
  }

  /* ===== EXPANDABLE BUTTON SYSTEM ===== */
  /* Generic expandable button - Base */
  .btn-expand {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    padding: 0;
    vertical-align: middle;
  }

  .btn-expand-sign {
    width: 100%;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btn-expand-sign i {
    font-size: 1.1rem;
    color: white;
  }

  .btn-expand-text {
    position: absolute;
    right: 0;
    width: 0;
    opacity: 0;
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    white-space: nowrap;
    overflow: hidden;
  }

  /* Hover effect */
  /* 🎯 CUSTOMIZATION: Change button expanded width here */
  .btn-expand:hover {
    width: 100px;
    /* Default: 90px - Increase for longer text, decrease for shorter */
    border-radius: 8px;
  }

  .btn-expand:hover .btn-expand-sign {
    width: 35%;
    /* Icon takes 35% of expanded width */
    padding-left: 8px;
  }

  .btn-expand:hover .btn-expand-text {
    opacity: 1;
    width: 65%;
    /* Text takes 65% of expanded width (matches icon 35% + text 65% = 100%) */
    padding-right: 10px;
  }

  .btn-expand:active {
    transform: scale(0.95);
  }

  .btn-expand:focus {
    outline-offset: 2px;
  }

  .btn-expand:focus:not(:focus-visible) {
    outline: none;
  }

  /* Primary variant (blue) - for Manage Users */
  .btn-expand-primary {
    background-color: #0d6efd;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.25);
  }

  .btn-expand-primary:hover {
    background-color: #0b5ed7;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.35);
  }

  .btn-expand-primary:focus {
    outline: 2px solid #0d6efd;
  }

  /* Success variant (green) - for Create Exam */
  .btn-expand-success {
    background-color: #198754;
    box-shadow: 0 2px 8px rgba(25, 135, 84, 0.25);
  }

  .btn-expand-success:hover {
    background-color: #157347;
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.35);
  }

  .btn-expand-success:focus {
    outline: 2px solid #198754;
  }

  /* Secondary variant (gray) - for Change Password */
  .btn-expand-secondary {
    background-color: #6c757d;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.25);
  }

  .btn-expand-secondary:hover {
    background-color: #5c636a;
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.35);
  }

  .btn-expand-secondary:focus {
    outline: 2px solid #6c757d;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .btn-expand {
      width: 38px;
      height: 38px;
    }

    .btn-expand-sign i {
      font-size: 1rem;
    }

    .btn-expand-text {
      font-size: 0.8125rem;
    }

    .btn-expand:hover {
      width: 85px;
      /* Mobile: Slightly smaller expanded width */
    }
  }

  @media (max-width: 576px) {
    .btn-expand {
      width: 36px;
      height: 36px;
      border-radius: 6px;
    }

    .btn-expand-sign i {
      font-size: 0.95rem;
    }

    .btn-expand-text {
      font-size: 0.75rem;
    }

    .btn-expand:hover {
      width: 80px;
      /* Small mobile: Even smaller expanded width */
    }

    .btn-expand:hover .btn-expand-sign {
      padding-left: 6px;
    }
  }

  /* ===== IMPORT PHONE-STYLE CARD ===== */
  .import-phone-card {
    position: relative;
    width: 100%;
    background-color: #f0faf0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    margin-bottom: 1rem;
  }

  /* Decorative gradient circle (header accent) */
  .import-phone-card__circle {
    position: absolute;
    width: 8rem;
    height: 8rem;
    border-radius: 100%;
    background: rgba(255, 255, 255, 0.15);
    top: -2rem;
    right: -2rem;
    z-index: 1;
  }

  /* Header menu bar */
  .import-phone-card__menu {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.1rem 1.8rem;
    background: linear-gradient(135deg, #198754, #20c997);
    border-bottom: none;
  }

  .import-phone-card__menu-left i,
  .import-phone-card__menu-right i {
    font-size: 1.1rem;
    color: #fff;
    opacity: 0.85;
  }

  .import-phone-card__menu-title {
    font-weight: 800;
    font-size: 1.1rem;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  /* Content area */
  .import-phone-card__content {
    position: relative;
    z-index: 1;
    padding: 1.5rem 1.8rem 2rem;
  }

  /* Labels */
  .import-phone-card__label {
    display: block;
    font-weight: 700;
    font-size: 0.875rem;
    color: #155d36;
    margin-bottom: 0.5rem;
  }

  .import-phone-card__label i {
    margin-right: 0.25rem;
    color: #198754;
  }

  /* Inputs */
  .import-phone-card__input {
    width: 100%;
    padding: 0.65rem 1rem;
    border: 2px solid #c3e6cb;
    border-radius: 12px;
    background-color: #fff;
    font-size: 0.9rem;
    color: #333;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    outline: none;
  }

  .import-phone-card__input:focus {
    border-color: #198754;
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15);
  }

  .import-phone-card__input::placeholder {
    color: #a3cfb5;
    font-weight: 500;
  }

  /* Hint text */
  .import-phone-card__hint {
    display: block;
    font-size: 0.78rem;
    color: #6c9d7f;
    margin-top: 0.35rem;
  }

  /* Action buttons */
  .import-phone-card__actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1.2rem;
  }

  .import-phone-card__btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.7rem 1rem;
    border-radius: 14px;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    border: none;
    will-change: transform;
  }

  .import-phone-card__btn--primary {
    background: linear-gradient(135deg, #198754, #20c997);
    color: #fff;
    box-shadow: 0 3px 10px rgba(25, 135, 84, 0.25);
  }

  .import-phone-card__btn--primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(25, 135, 84, 0.35);
    background: linear-gradient(135deg, #157347, #1baa80);
    color: #fff;
  }

  .import-phone-card__btn--primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(25, 135, 84, 0.3);
  }

  .import-phone-card__btn--outline {
    background-color: #fff;
    color: #198754;
    border: 2px solid #c3e6cb;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .import-phone-card__btn--outline:hover {
    transform: translateY(-2px);
    background-color: #f0faf0;
    border-color: #198754;
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15);
    color: #155d36;
  }

  .import-phone-card__btn--outline:active {
    transform: translateY(0);
  }

  /* Responsive */
  @media (max-width: 576px) {
    .import-phone-card {
      border-radius: 28px;
    }

    .import-phone-card__menu {
      padding: 0.8rem 1.2rem;
    }

    .import-phone-card__content {
      padding: 1.2rem 1.2rem 1.5rem;
    }

    .import-phone-card__actions {
      flex-direction: column;
    }

    .import-phone-card__circle {
      width: 10rem;
      height: 10rem;
    }
  }
</style>