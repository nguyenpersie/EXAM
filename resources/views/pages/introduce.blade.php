<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Giới thiệu - TTDN Đường thủy Sông Hậu</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-logo.png') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            display: ['Space Grotesk', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <style>
    /* Smooth transition for tab changes & interactive elements */
    .fade-in {
      animation: fadeIn 0.3s ease-in-out forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body class="min-h-screen bg-slate-50 flex flex-col justify-between font-sans text-slate-900 antialiased">

  <!-- ================= HEADER / NAVIGATION ================= -->
  <header id="app-header" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        
        <!-- Logo Brand -->
        <a href="#" onclick="switchTab('student'); window.scrollTo({ top: 0, behavior: 'smooth' }); return false;" class="flex items-center gap-3 group text-left">
          <div class="p-2.5 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 text-white shadow-md shadow-indigo-200">
            <i class="bi bi-tsunami text-2xl"></i>
          </div>
          <div>
            <span class="block font-display text-xl font-bold text-slate-900 tracking-tight leading-none uppercase">
              TTDN Sông Hậu
            </span>
            <span class="block text-[10px] font-semibold text-blue-600 tracking-wider uppercase mt-1 font-mono">
              Đào tạo Lái Cano - Thuyền Trưởng
            </span>
          </div>
        </a>

        <!-- Navigation Links (Student Mode only) -->
        <nav id="desktop-nav-links" class="hidden lg:flex items-center space-x-1">
          <a href="#about" onclick="scrollToSection('about', event)" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-colors">Giới thiệu</a>
          <a href="#courses" onclick="scrollToSection('courses', event)" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-colors">Khóa đào tạo</a>
          <a href="#registration" onclick="scrollToSection('registration', event)" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-colors">Đăng ký tuyển sinh</a>
          <a href="#faq" onclick="scrollToSection('faq', event)" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-colors">FAQs</a>
        </nav>

        <!-- Mode Switchers & Actions -->
        <div class="hidden md:flex items-center gap-2">
          <button id="tab-btn-student" onclick="switchTab('student')" class="flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg transition-all border bg-slate-100 text-slate-900 border-slate-300 shadow-sm">
            <i class="bi bi-mortarboard text-blue-600 text-sm"></i>
            Học Viên
          </button>

          <button id="tab-btn-admin" onclick="switchTab('admin')" class="flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg transition-all border text-slate-600 hover:bg-slate-50 border-transparent">
            <i class="bi bi-shield-lock text-sm"></i>
            QL Đăng Ký
          </button>

          <button id="tab-btn-laravel" onclick="switchTab('laravel')" class="flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg transition-all border bg-orange-50 text-orange-700 hover:bg-orange-100/80 border-transparent">
            <i class="bi bi-code-slash text-orange-600 text-sm"></i>
            Tích hợp Backend
          </button>

          <!-- Integration with exam home -->
          <div class="h-6 w-px bg-slate-200 mx-1"></div>
          @auth
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-md">
              <i class="bi bi-play-circle-fill"></i> Vào phòng thi
            </a>
          @else
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-md">
              <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
            </a>
          @endauth
        </div>

        <!-- Mobile Menu Toggle Button -->
        <div class="flex md:hidden items-center gap-2">
          <span id="mobile-tab-indicator" class="text-[10px] font-bold font-mono bg-blue-50 text-blue-700 px-2 py-1 rounded-sm border border-blue-100 uppercase">
            Học viên
          </span>
          <button onclick="toggleMobileMenu()" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg">
            <i id="mobile-menu-icon" class="bi bi-list text-2xl"></i>
          </button>
        </div>

      </div>
    </div>

    <!-- Mobile Drawer -->
    <div id="mobile-nav-drawer" class="hidden md:hidden border-t border-slate-100 bg-white px-4 py-4 space-y-3 shadow-lg absolute w-full left-0 fade-in">
      <div id="mobile-student-links" class="space-y-1 py-1 border-b border-slate-100">
        <a href="#about" onclick="scrollToSection('about', event, true)" class="block w-full text-left px-3 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50 rounded-md">Giới thiệu</a>
        <a href="#courses" onclick="scrollToSection('courses', event, true)" class="block w-full text-left px-3 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50 rounded-md">Khóa đào tạo</a>
        <a href="#registration" onclick="scrollToSection('registration', event, true)" class="block w-full text-left px-3 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50 rounded-md">Đăng ký tuyển sinh</a>
        <a href="#faq" onclick="scrollToSection('faq', event, true)" class="block w-full text-left px-3 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50 rounded-md">FAQs</a>
      </div>

      <div class="flex flex-col gap-2 pt-1">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">Chế độ giao diện</p>
        <button onclick="switchTab('student'); toggleMobileMenu();" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md text-slate-700 hover:bg-slate-50 bg-slate-100">
          <span class="flex items-center gap-2"><i class="bi bi-mortarboard text-blue-600"></i> Học Viên</span>
        </button>

        <button onclick="switchTab('admin'); toggleMobileMenu();" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md text-slate-700 hover:bg-slate-50">
          <span class="flex items-center gap-2"><i class="bi bi-shield-lock"></i> Quản Lý Đăng Ký (Admin)</span>
        </button>

        <button onclick="switchTab('laravel'); toggleMobileMenu();" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md text-slate-700 hover:bg-slate-50">
          <span class="flex items-center gap-2"><i class="bi bi-code-slash text-orange-600"></i> Tích hợp Backend</span>
        </button>
      </div>
    </div>
  </header>


  <!-- ================= MAIN CONTAINER ================= -->
  <main class="flex-grow">

    <!-- ================= TAB: HỌC VIÊN ================= -->
    <div id="tab-content-student" class="tab-pane fade-in max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      
      <!-- HERO BANNER -->
      <div class="relative overflow-hidden bg-slate-900 text-white rounded-3xl my-6 shadow-2xl">
        <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] rounded-full bg-blue-600/20 blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] rounded-full bg-indigo-500/15 blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 py-16 sm:px-12 lg:px-16 relative z-10">
          <div class="grid lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6">
              <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold tracking-wide backdrop-blur-sm">
                <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                Tuyển Sinh Khóa Mới Niên Khóa 2026 - 2027
              </div>

              <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-[1.1]">
                Trường Đào Tạo Lái Cano & <br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-sky-300">
                  Thuyền Trưởng Thủy Nội Địa
                </span>
              </h1>

              <p class="text-slate-300 text-base sm:text-lg max-w-2xl font-normal leading-relaxed">
                Chào mừng bạn đến với <strong class="text-white">Trung tâm dạy nghề Đường thủy Sông Hậu</strong> - Đơn vị được ủy quyền đào tạo, huấn luyện và tổ chức sát hạch cấp Bằng Thuyền Trưởng, Máy Trưởng & Chứng Chỉ Chuyên Môn Lái Cano Cao Tốc, Mô Tô Nước, Du Thuyền Yacht cá nhân chuẩn pháp lý Quốc Gia.
              </p>

              <div class="grid sm:grid-cols-2 gap-4 pt-2">
                <div class="flex items-center gap-3 bg-white/5 border border-white/10 p-3.5 rounded-xl">
                  <div class="p-2 rounded-lg bg-blue-500/20 text-blue-400">
                    <i class="bi bi-award text-xl"></i>
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold text-white">Bằng Chuẩn Quốc Gia</h4>
                    <p class="text-xs text-slate-400">Do Cục Đường Thủy Nội Địa cấp</p>
                  </div>
                </div>

                <div class="flex items-center gap-3 bg-white/5 border border-white/10 p-3.5 rounded-xl">
                  <div class="p-2 rounded-lg bg-emerald-500/20 text-emerald-400">
                    <i class="bi bi-shield-check text-xl"></i>
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold text-white">Thực Hành Sát Hạch</h4>
                    <p class="text-xs text-slate-400">Huấn luyện trực tiếp trên sông nước thực tế</p>
                  </div>
                </div>
              </div>

              <div class="flex flex-wrap items-center gap-4 pt-4">
                <a href="#registration" onclick="scrollToSection('registration', event)" class="inline-flex items-center gap-2 px-6 py-3.5 bg-blue-600 font-semibold text-sm text-white rounded-xl shadow-lg shadow-blue-500/25 hover:bg-blue-500 transition-all group">
                  Đăng ký xét tuyển trực tuyến
                  <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
                </a>

                <a href="#courses" onclick="scrollToSection('courses', event)" class="inline-flex items-center gap-2 px-6 py-3.5 bg-slate-800 font-semibold text-sm text-white rounded-xl border border-slate-700 hover:bg-slate-700 hover:border-slate-600 transition-all">
                  Xem các ngành đào tạo
                </a>
              </div>
            </div>

            <div class="lg:col-span-5 relative">
              <div class="bg-slate-800/80 border border-white/10 p-6 rounded-2xl shadow-xl backdrop-blur-md space-y-6">
                <div class="flex justify-between items-center border-b border-white/10 pb-4">
                  <span class="text-sm font-mono text-slate-300 uppercase tracking-widest">Hình Ảnh Đào Tạo</span>
                  <span class="text-xs font-semibold bg-emerald-500/10 text-emerald-400 px-2.5 py-1 rounded">Sông Nước Thực Tế</span>
                </div>

                <div class="grid grid-cols-2 gap-3.5">
                  <div class="relative rounded-xl overflow-hidden aspect-video bg-slate-800 border border-white/5">
                    <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=350&q=80" alt="Thực hành cano" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-end p-2">
                      <span class="text-[10px] font-semibold text-white tracking-tight bg-blue-600/90 px-1.5 py-0.5 rounded">Thực hành cano</span>
                    </div>
                  </div>

                  <div class="relative rounded-xl overflow-hidden aspect-video bg-slate-800 border border-white/5">
                    <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=350&q=80" alt="Sa hình rẽ sóng" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-end p-2">
                      <span class="text-[10px] font-semibold text-white tracking-tight bg-blue-600/90 px-1.5 py-0.5 rounded">Sa hình rẽ sóng</span>
                    </div>
                  </div>

                  <div class="relative rounded-xl overflow-hidden aspect-video bg-slate-800 border border-white/5">
                    <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=350&q=80" alt="Cabin lái" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-end p-2">
                      <span class="text-[10px] font-semibold text-white tracking-tight bg-blue-600/90 px-1.5 py-0.5 rounded">Cabin lái thực tế</span>
                    </div>
                  </div>

                  <div class="relative rounded-xl overflow-hidden aspect-video bg-slate-800 border border-white/5">
                    <img src="https://images.unsplash.com/photo-1567899378494-47b22a2ae96a?auto=format&fit=crop&w=350&q=80" alt="Lái du thuyền" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-end p-2">
                      <span class="text-[10px] font-semibold text-white tracking-tight bg-blue-600/90 px-1.5 py-0.5 rounded">Bằng Du Thuyền</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-start gap-3 bg-blue-950/40 p-4 rounded-xl border border-blue-900/30">
                  <i class="bi bi-calendar-check text-blue-400 text-lg mt-0.5"></i>
                  <p class="text-xs text-slate-300 leading-relaxed">
                    <strong class="text-blue-300 text-xs block mb-1">Thời gian linh động:</strong> 
                    Hỗ trợ ôn luyện vào cuối tuần (Thứ 7 & Chủ Nhật) không ảnh hưởng tới công việc. Thủ tục nhanh gọn, nhận chứng chỉ chỉ sau 7-10 ngày thi đỗ.
                  </p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- ABOUT SECTION (CORE VALUES) -->
      <section id="about" class="py-12 px-2 scroll-mt-24">
        <div class="text-center max-w-3xl mx-auto mb-10">
          <span class="text-xs font-mono font-bold tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded uppercase">
            UY TÍN - KHÁCH QUAN - CHUYÊN NGHIỆP
          </span>
          <h3 class="font-display text-2xl sm:text-3xl font-bold mt-3 text-slate-900">
            Tại Sao Chọn TTDN Đường Thủy Sông Hậu?
          </h3>
          <p class="text-slate-500 text-sm mt-3">
            Quy trình đào tạo chuẩn chỉ giúp học viên nắm trọn chứng chỉ lý thuyết lẫn kỹ năng điều khiển an toàn bất kỳ dòng cano hay tàu thủy nào.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 sm:gap-8">
          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
              <i class="bi bi-compass text-2xl"></i>
            </div>
            <h4 class="text-base sm:text-lg font-bold text-slate-900">Luyện Bản Đồ & Sa Hình Thật</h4>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
              Học viên được trực tiếp cầm lái, thi thử sa hình zic-zắc vòng số 8, cập bến khi ngược dòng và kỹ năng cứu hộ cứu nạn thực tế trên Sông Hậu.
            </p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
              <i class="bi bi-mortarboard text-2xl"></i>
            </div>
            <h4 class="text-base sm:text-lg font-bold text-slate-900">Lý Thuyết Đậu Cao 99%</h4>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
              Hệ thống ôn luyện 150 câu hỏi luật giao thông đường thủy chính thức. Hướng dẫn mẹo thi trắc nghiệm trực quan ngay trên phần mềm thi của trung tâm.
            </p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
              <i class="bi bi-card-checklist text-2xl"></i>
            </div>
            <h4 class="text-base sm:text-lg font-bold text-slate-900">Thủ Tục Nhanh - Trọn Gói</h4>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
              Trung tâm hỗ trợ trọn gói hồ sơ đăng ký, chụp ảnh thẻ, giấy khám sức khỏe đường thủy tại chỗ, cam kết không phát sinh bất kỳ khoản phụ phí nào.
            </p>
          </div>
        </div>
      </section>

      <!-- COURSE CATALOG SECTION -->
      <section id="courses" class="py-12 scroll-mt-24 space-y-10">
        <div class="text-center max-w-3xl mx-auto space-y-3">
          <span class="text-xs font-mono font-bold tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded uppercase">
            HẠNG ĐÀO TẠO & CHỨNG CHỈ SÁT HẠCH
          </span>
          <h2 class="font-display text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">
            Khóa Học Thuyền Trưởng & Lái Cano Hiện Đại
          </h2>
          <div class="h-1 w-20 bg-blue-600 mx-auto rounded-full"></div>
          <p class="text-slate-600 text-sm">
            Chương trình đào tạo thực chiến chuẩn quốc gia được ủy quyền sát hạch. Trải nghiệm hệ thống cano cao tốc, jetski và du thuyền đời mới với quy trình giảng dạy khoa học, hiệu quả.
          </p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-2" id="course-filter-container">
          <!-- Buttons dynamically rendered in JS -->
        </div>

        <!-- Courses Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="courses-grid-container">
          <!-- Cards dynamically rendered in JS -->
        </div>

        <!-- CAMPUS & INFRASTRUCTURE SECTION -->
        <div class="py-12 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 my-10">
          <div class="grid lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-5 space-y-4">
              <span class="text-xs font-mono font-bold tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded uppercase">
                BẾN THỰC HÀNH HIỆN ĐẠI
              </span>
              <h3 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">
                Cơ Sở Vật Chất Chuẩn Khung Khảo Thí
              </h3>
              <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                Bến thuyền thực hành của trường được thiết kế tại luồng triều tĩnh trên Sông Hậu, trang bị hệ thống phao tiêu, cầu cảng nổi tiêu chuẩn quốc gia cùng loạt cano cao tốc composite và cabin mô phỏng hải đồ điện tử hàng hải thế hệ mới.
              </p>
              
              <div class="space-y-2.5 pt-2">
                <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700">
                  <i class="bi bi-geo-alt text-blue-600 shrink-0"></i>
                  <span>Trụ sở chính: D30 Đường số 30 khu ĐTM Hưng Phú, Cái Răng, Cần Thơ</span>
                </div>
                <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700">
                  <i class="bi bi-anchor text-blue-600 shrink-0"></i>
                  <span>Bến cảng thực hành khảo thí: Bờ sông Hậu, Cần Thơ</span>
                </div>
              </div>
            </div>

            <div class="lg:col-span-7 grid grid-cols-2 gap-4">
              <div class="relative rounded-2xl overflow-hidden h-40 bg-slate-100 shadow-sm border border-slate-100">
                <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=400&q=80" alt="Bến thực hành tàu" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                  <span class="text-xs text-white font-semibold">Bến du thuyền khảo thí</span>
                </div>
              </div>
              <div class="relative rounded-2xl overflow-hidden h-40 bg-slate-100 shadow-sm border border-slate-100">
                <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=400&q=80" alt="Phòng mô phỏng" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                  <span class="text-xs text-white font-semibold">Phòng máy lái tàu điện tử</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

      <!-- REGISTRATION FORM SECTION -->
      <section id="registration" class="py-12 scroll-mt-24 max-w-4xl mx-auto">
        <div class="text-center mb-10">
          <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-widest">
            Phòng Tuyển Sinh Trực Tuyến
          </span>
          <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-3">
            Phiếu Đăng Ký Xét Tuyển Nhập Học
          </h2>
          <p class="text-slate-500 mt-2 text-xs sm:text-sm max-w-xl mx-auto">
            Điền thông tin đăng ký trực tuyến chính xác. Ban chỉ đạo học vụ và đào tạo sát hạch sẽ thẩm định hồ sơ của bạn sớm nhất.
          </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden" id="registration-card-body">
          <!-- Form or Success ticket will be rendered here dynamically by Javascript -->
        </div>
      </section>

      <!-- FAQ SECTION -->
      <section id="faq" class="py-12 max-w-4xl mx-auto scroll-mt-24">
        <div class="text-center mb-10">
          <h3 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">
            Giải Đáp Thắc Mắc Thường Gặp
          </h3>
          <p class="text-slate-500 text-xs sm:text-sm mt-2">
            Các câu hỏi phổ biến giúp quý học viên tự tin nộp hồ sơ xét tuyển.
          </p>
        </div>

        <div class="space-y-3" id="faq-container-list">
          <!-- Rendered in JS -->
        </div>
      </section>

    </div>


    <!-- ================= TAB: QUẢN LÝ ĐĂNG KÝ (ADMIN) ================= -->
    <div id="tab-content-admin" class="tab-pane fade-in hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Top Actions Bar -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div>
          <div class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 bg-green-500 rounded-full animate-pulse"></span>
            <span class="text-xs font-bold text-slate-500 font-mono uppercase tracking-wider">Hệ thống cục bộ lưu trữ</span>
          </div>
          <h2 class="font-display text-2xl font-bold text-slate-900 mt-1">Cổng Quản Trị Tuyển Sinh Trực Tuyến</h2>
          <p class="text-slate-500 text-xs sm:text-sm">Danh sách học viên đăng ký xét tuyển đầu vào từ biểu mẫu giới thiệu.</p>
        </div>

        <div class="flex items-center gap-2">
          <button onclick="loadAdminRegistrations()" class="px-4 py-2 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-all flex items-center gap-1.5 border border-slate-300">
            <i class="bi bi-arrow-clockwise text-blue-600 text-sm"></i>
            Tải Lại Dữ Liệu
          </button>
          
          <button onclick="clearAllRegistrations()" class="px-4 py-2 text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl transition-all flex items-center gap-1.5 border border-rose-200">
            <i class="bi bi-trash text-sm"></i>
            Xóa Toàn Bộ
          </button>
        </div>
      </div>

      <!-- Filters -->
      <div class="grid sm:grid-cols-12 gap-4 bg-white p-5 rounded-2xl border border-slate-200 mt-6">
        <div class="sm:col-span-8 relative">
          <i class="bi bi-search absolute left-3.5 top-3.5 text-slate-400"></i>
          <input
            id="admin-search-input"
            type="text"
            oninput="filterAdminRecords()"
            placeholder="Tìm kiếm theo Tên Học Viên, Số Điện Thoại, Mã Hồ Sơ..."
            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white text-slate-800"
          />
        </div>

        <div class="sm:col-span-4 rounded-xl">
          <select
            id="admin-status-filter"
            onchange="filterAdminRecords()"
            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-slate-800"
          >
            <option value="all">Tất cả trạng thái</option>
            <option value="Pending">Chờ xét duyệt (Pending)</option>
            <option value="Approved">Đã duyệt học vụ (Approved)</option>
          </select>
        </div>
      </div>

      <!-- Table Container -->
      <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm mt-6">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                <th class="p-4 pl-6">Mã & Ngày Đăng Ký</th>
                <th class="p-4">Thông Tin Học Viên</th>
                <th class="p-4">Ngành Nghề Đăng Ký</th>
                <th class="p-4">Số CCCD & Ngày Cấp</th>
                <th class="p-4">Trạng Thái & Ghi Chú</th>
                <th class="p-4 pr-6 text-right">Thao Tác</th>
              </tr>
            </thead>
            <tbody id="admin-table-tbody" class="divide-y divide-slate-100 text-xs sm:text-sm">
              <!-- Rendered dynamically -->
            </tbody>
          </table>
        </div>
        <div id="admin-empty-state" class="hidden p-16 text-center text-slate-400 space-y-2">
          <p class="text-sm font-semibold">Chưa tìm thấy hồ sơ đăng ký nào tương ứng!</p>
          <p class="text-xs">Hãy điền thêm phiếu đăng ký mới ở Giao diện Học viên.</p>
        </div>
      </div>

    </div>


    <!-- ================= TAB: TÍCH HỢP BACKEND ================= -->
    <div id="tab-content-laravel" class="tab-pane fade-in hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <div class="bg-slate-900 text-slate-100 rounded-3xl p-6 sm:p-10 shadow-xl">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-white/10 pb-6 mb-6">
          <div>
            <span class="text-[10px] font-bold font-mono tracking-widest text-orange-400 uppercase bg-orange-400/10 px-2.5 py-1 rounded-md border border-orange-400/20">
              Hỗ Trợ Toàn Diện Laravel 11
            </span>
            <h2 class="font-display text-2xl sm:text-3xl font-bold mt-2 text-white">
              Bộ Mã Nguồn Backend Laravel 11 Đồng Bộ
            </h2>
            <p class="text-slate-400 text-xs sm:text-sm mt-1 max-w-xl">
              Sẵn sàng tích hợp! Bạn muốn chạy trang web này với cơ sở dữ liệu thực tế bằng PHP Laravel 11? Dưới đây là mã nguồn backend hoàn thiện đáp ứng đúng mong đợi.
            </p>
          </div>

          <button onclick="copySnippetToClipboard()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/15 border border-white/20 text-xs font-semibold rounded-xl text-white transition-all shadow-xs self-end">
            <i class="bi bi-clipboard text-orange-400"></i>
            <span id="copy-btn-text">Copy Mã Nguồn Của Tab</span>
          </button>
        </div>

        <div class="grid lg:grid-cols-12 gap-6">
          
          <!-- Left tabs selector -->
          <div class="lg:col-span-3 flex flex-row lg:flex-col overflow-x-auto lg:overflow-visible gap-2 pb-2 lg:pb-0">
            <button id="snippet-btn-migration" onclick="switchSnippet('migration')" class="flex items-center gap-2.5 px-4 py-3 text-xs font-bold rounded-xl whitespace-nowrap transition-all w-full text-left bg-orange-500 text-white shadow-md shadow-orange-500/20">
              <i class="bi bi-database"></i> Database Migration
            </button>
            <button id="snippet-btn-model" onclick="switchSnippet('model')" class="flex items-center gap-2.5 px-4 py-3 text-xs font-bold rounded-xl whitespace-nowrap transition-all w-full text-left bg-white/5 text-slate-300 hover:bg-white/10">
              <i class="bi bi-file-earmark-code"></i> Eloquent Model
            </button>
            <button id="snippet-btn-controller" onclick="switchSnippet('controller')" class="flex items-center gap-2.5 px-4 py-3 text-xs font-bold rounded-xl whitespace-nowrap transition-all w-full text-left bg-white/5 text-slate-300 hover:bg-white/10">
              <i class="bi bi-server"></i> Controller API
            </button>
            <button id="snippet-btn-routes" onclick="switchSnippet('routes')" class="flex items-center gap-2.5 px-4 py-3 text-xs font-bold rounded-xl whitespace-nowrap transition-all w-full text-left bg-white/5 text-slate-300 hover:bg-white/10">
              <i class="bi bi-signpost-split"></i> API Web Routes
            </button>
          </div>

          <!-- Right Code visualizer -->
          <div class="lg:col-span-9 relative bg-slate-950 rounded-2xl border border-white/5 overflow-hidden">
            <!-- Header indicator -->
            <div class="flex justify-between items-center bg-slate-900 px-4 py-2 border-b border-white/5">
              <span id="code-file-name" class="text-[11px] font-mono text-slate-500">
                database/migrations/2026_06_15_create_vocational_tables.php
              </span>
              <div class="flex gap-1.5">
                <span class="w-2.5 h-2.5 bg-red-500/80 rounded-full"></span>
                <span class="w-2.5 h-2.5 bg-yellow-500/80 rounded-full"></span>
                <span class="w-2.5 h-2.5 bg-green-500/80 rounded-full"></span>
              </div>
            </div>

            <pre class="p-4 sm:p-6 overflow-x-auto font-mono text-xs text-orange-200/90 leading-relaxed max-h-[480px]"><code id="code-content-block"><!-- Injection --></code></pre>
          </div>

        </div>
      </div>

    </div>

  </main>


  <!-- ================= FOOTER ================= -->
  <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 pt-16 pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto grid md:grid-cols-12 gap-8 mb-10">
      
      <div class="md:col-span-5 space-y-4">
        <div class="flex items-center gap-2.5">
          <div class="p-2 rounded-lg bg-blue-600 text-white">
            <i class="bi bi-tsunami"></i>
          </div>
          <span class="font-display text-lg font-bold text-white tracking-wider uppercase">
            TTDN SÔNG HẬU
          </span>
        </div>
        <p class="text-xs text-slate-400 leading-relaxed">
          Trung tâm dạy nghề Đường thủy Sông Hậu tự hào là người bạn đồng hành tin cậy của học viên trên hành trình kiến tạo sự nghiệp sông nước tương lai vững chắc.
        </p>
        <div class="flex gap-2">
          <span class="text-[10px] uppercase font-mono bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700">
            Lớp Đào Tạo Trọng Điểm Quốc Gia
          </span>
        </div>
      </div>

      <div class="md:col-span-4 space-y-3">
        <h4 class="text-[11px] font-bold text-white uppercase tracking-widest font-mono">Đường Dây Nóng Tư Vấn</h4>
        <div class="space-y-2 text-xs sm:text-sm">
          <p class="flex items-center gap-2.5 text-slate-400">
            <i class="bi bi-telephone text-blue-400"></i>
            Hotline Tuyển sinh: <strong class="text-slate-105 text-white">0325207333 (Miễn phí)</strong>
          </p>
          <p class="flex items-center gap-2.5 text-slate-400">
            <i class="bi bi-envelope text-blue-400"></i>
            Email học vụ: <span class="font-sans">tuyensinh@songhau.edu.vn</span>
          </p>
        </div>
      </div>

      <div class="md:col-span-3 space-y-3">
        <h4 class="text-[11px] font-bold text-white uppercase tracking-widest font-mono">Địa chỉ xưởng thực hành</h4>
        <div class="space-y-2 text-xs text-slate-400 leading-relaxed">
          <p class="flex items-start gap-2">
            <i class="bi bi-geo-alt text-blue-400 mt-0.5"></i>
            <span>D30 Đường số 30 khu ĐTM Hưng Phú, Phường Cái Răng, TP. Cần Thơ</span>
          </p>
        </div>
      </div>

    </div>

    <div class="max-w-7xl mx-auto border-t border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center text-[11px] text-slate-500 gap-4">
      <p>© 2026 TTDN Đường thủy Sông Hậu. Bảo lưu tất cả quyền học tập.</p>
      <div class="flex gap-4">
        <a href="#about" onclick="scrollToSection('about', event)" class="hover:text-slate-300">Giới thiệu</a>
        <a href="#courses" onclick="scrollToSection('courses', event)" class="hover:text-slate-300">Khóa học</a>
        <a href="#registration" onclick="scrollToSection('registration', event)" class="hover:text-slate-300">Tuyển sinh</a>
      </div>
    </div>
  </footer>


  <!-- ================= CORE COMPONENT MODALS ================= -->

  <!-- COURSE DETAIL MODAL -->
  <div id="course-detail-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden fade-in">
    <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl relative border border-slate-100 flex flex-col max-h-[90vh]">
      
      <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
        <div>
          <span id="modal-course-code" class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded uppercase">
            MÃ ĐÀO TẠO
          </span>
          <h3 id="modal-course-name" class="font-display text-xl sm:text-2xl font-bold text-slate-900 mt-2">
            Tên Khóa Học
          </h3>
        </div>
        <button onclick="closeCourseModal()" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-full transition-colors">
          <i class="bi bi-x-lg text-lg"></i>
        </button>
      </div>

      <div class="p-6 space-y-6 overflow-y-auto max-h-[60vh]">
        
        <div>
          <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 font-mono">Hình ảnh học viên & thiết bị</h4>
          <div class="grid grid-cols-3 gap-3" id="modal-image-grid">
            <!-- Dynamic Images -->
          </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
          <div class="bg-blue-50/40 p-4 rounded-xl border border-blue-100 space-y-2">
            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider font-mono">Tóm tắt đào tạo</h4>
            <p id="modal-course-desc" class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
              Mô tả khóa học
            </p>
          </div>
          <div class="bg-orange-50/40 p-4 rounded-xl border border-orange-100 space-y-2">
            <h4 class="text-xs font-bold text-orange-600 uppercase tracking-wider font-mono">Điều kiện nhận hồ sơ</h4>
            <p id="modal-course-req" class="text-xs sm:text-sm text-slate-700 leading-relaxed font-semibold">
              Điều kiện tuyển sinh
            </p>
          </div>
        </div>

        <div>
          <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 font-mono">Học phần kỹ năng cốt lõi</h4>
          <div class="grid sm:grid-cols-2 gap-3" id="modal-skills-list">
            <!-- Dynamic Skills -->
          </div>
        </div>

        <div>
          <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 font-mono">Bằng cấp & Cơ hội việc làm bến bãi</h4>
          <div class="space-y-2" id="modal-careers-list">
            <!-- Dynamic Career Opportunities -->
          </div>
        </div>

        <div class="bg-slate-900 text-slate-200 p-4.5 rounded-2xl grid grid-cols-3 gap-2 text-center text-xs p-4">
          <div>
            <span class="block text-slate-400 text-[10px] uppercase font-mono tracking-wider">Hệ Học</span>
            <span id="modal-level-badge" class="block font-bold mt-1 text-white text-sm">Hạng Cấp</span>
          </div>
          <div>
            <span class="block text-slate-400 text-[10px] uppercase font-mono tracking-wider">Thời Lượng</span>
            <span id="modal-duration-badge" class="block font-bold mt-1 text-white text-sm">Thời gian</span>
          </div>
          <div>
            <span class="block text-slate-400 text-[10px] uppercase font-mono tracking-wider">Đào tạo</span>
            <span class="block font-bold mt-1 text-emerald-400 text-sm">Cầm Tay Chỉ Việc</span>
          </div>
        </div>

      </div>

      <div class="p-6 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3 shrink-0">
        <button onclick="closeCourseModal()" class="px-4.5 py-2.5 text-xs text-slate-650 font-semibold hover:bg-slate-100 rounded-xl transition-all">
          Đóng
        </button>
        <button id="modal-register-prefill-btn" class="px-5 py-2.5 bg-blue-600 text-white font-bold text-xs rounded-xl shadow-md hover:bg-blue-500 transition-all">
          Đăng ký học ngay
        </button>
      </div>

    </div>
  </div>


  <!-- ================= CORE DATA & JAVASCRIPT LOGIC ================= -->
  <script>
    // ================= STATIC DATA =================
    const COURSES_DATA = [
      {
        id: "cano-hang-ba",
        code: "T3",
        name: "Bằng Thuyền Trưởng Phương Tiện Thủy Nội Địa Hạng Ba",
        category: "thuyen-truong",
        duration: "10 ngày",
        level: "Bằng Thuyền Trưởng",
        tuitionFee: 4500000,
        salaryExpectation: "Điều khiển phương tiện công suất < 150 HP",
        requirements: "Học viên đủ 18 tuổi trở lên, có giấy khám sức khỏe đường thủy và biết bơi cơ bản.",
        description: "Khóa đào tạo chuẩn Quốc Gia cấp bằng Thuyền Trưởng Hạng Ba do Cục Đường Thủy Nội Địa cấp phép. Học viên được học lý thuyết luật đường thủy, quy tắc phòng ngừa va chạm và thực hành trực tiếp lái cano/tàu vỏ sắt.",
        skillsLearned: [
          "Luật giao thông đường thủy nội địa Việt Nam mới nhất",
          "Kỹ thuật thắt các loại nút dây hàng hải cơ bản",
          "Kịch bản xử lý sự cố tràn nước, cứu nạn cứu hộ đường sông",
          "Kỹ thuật điều khiển, cập cầu bến an toàn khi ngược nước"
        ],
        careerOpportunities: [
          "Thuyền trưởng lái đò dọc, phương tiện chở khách du lịch",
          "Chuyên viên cứu hộ bãi biển, ban quản lý vịnh",
          "Vận hành phương tiện đánh bắt, vận chuyển nông thủy sản tư nhân",
          "Đủ điều kiện pháp lý nâng lên hạng Nhì sau 1 năm"
        ],
        image: "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=600&q=80",
        images: [
          "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1569263979104-865ab7cd8d13?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1505244208947-16f296b1cf33?auto=format&fit=crop&w=600&q=80"
        ]
      },
      {
        id: "lai-cano-thuong",
        code: "CC-LPT",
        name: "Chứng Chỉ Chuyên Môn Lái Phương Tiện (Cano/Xuồng Máy dưới 15 HP)",
        category: "cano",
        duration: "5 ngày",
        level: "Chứng Chỉ Chuyên Môn",
        tuitionFee: 3500000,
        salaryExpectation: "Lái cano cá nhân, cano kéo dù kéo phao chuối",
        requirements: "Đủ 18 tuổi, sức khỏe tốt, cam kết biết bơi.",
        description: "Chứng chỉ bắt buộc đối với người vận hành cano du lịch cá nhân, jetski (mô tô nước), xuồng máy bán tải công suất nhỏ tuần tra hoặc phục vụ trò chơi giải trí ven sông, ven biển.",
        skillsLearned: [
          "Cách vận hành máy nổ ngoài (Outboard Motor) an toàn",
          "Tư thế điều khiển mô tô nước tránh lật sóng",
          "Các phao tiêu, biển báo giao thông thủy nội địa cơ bản",
          "Kỹ năng sơ cứu đuối nước khẩn cấp"
        ],
        careerOpportunities: [
          "Kỹ thuật viên tại các resort, bãi tắm du lịch lớn",
          "Nhân viên quản lý trò chơi mạo hiểm ven sông, ven biển",
          "Vận hành cano kéo phao chuối, jetski cá nhân giải trí hợp quy",
          "Tuần tra nội bộ dự án bến du thuyền, khu đô thị sinh thái sông nước"
        ],
        image: "https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=600&q=80",
        images: [
          "https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1610641818989-c2051b5e2cfd?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=600&q=80"
        ]
      },
      {
        id: "cano-cao-toc",
        code: "CC-CT",
        name: "Chứng Chỉ Chuyên Môn Điều Khiển Phương Tiện Cao Tốc (Cano Cao Tốc)",
        category: "cao-toc",
        duration: "7 ngày",
        level: "Hạng Đặc Biệt",
        tuitionFee: 4000000,
        salaryExpectation: "Điều khiển cano cao tốc vận tải hành khách du lịch",
        requirements: "Trực tiếp đã có bằng Thuyền trưởng hạng Ba trở lên.",
        description: "Khóa bồi dưỡng chứng chỉ đặc chuyên sâu cho người đã có bằng thuyền trưởng để được phép điều khiển các dòng cano cao tốc chở khách du lịch trọng tải lớn, cano tuần tra cao tốc vượt sóng lớn.",
        skillsLearned: [
          "Kỹ thuật bọc cua gấp an toàn ở tốc độ trên 50km/h",
          "Sử dụng bản đồ định vị GPS hàng hải hải đồ số",
          "Cách neo đậu ghềnh đá, đảo xa khó cập bến",
          "Phương pháp bảo dưỡng định kỳ hệ thống máy xăng 2 thì/4 thì"
        ],
        careerOpportunities: [
          "Tài lái cano cao tốc tại các tuyến du lịch đảo, vịnh",
          "Thuyền trưởng cano cấp cứu của lực lượng tìm kiếm cứu hộ chuyên nghiệp",
          "Vận hành tàu du lịch siêu tốc chặng ngắn ven sông, ven vịnh",
          "Đội ngũ kỹ thuật thử nghiệm vận tốc cano mới lắp ráp"
        ],
        image: "https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=600&q=80",
        images: [
          "https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1611001716885-b3402558a62b?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1562620612-1293d14b3552?auto=format&fit=crop&w=600&q=80"
        ]
      },
      {
        id: "du-thuyen-yacht",
        code: "CC-YACHT",
        name: "Chứng Chỉ Điều Khiển Du Thuyền Cao Cấp (Yacht Master)",
        category: "du-thuyen",
        duration: "15 ngày",
        level: "Hạng Đặc Biệt",
        tuitionFee: 8500000,
        salaryExpectation: "Lái du thuyền cá nhân, du thuyền hạng sang 5 sao",
        requirements: "Đủ 18 tuổi, vượt qua kỳ khám kiểm tra phản xạ vận động hàng hải.",
        description: "Chứng chỉ thời thượng dành riêng cho chủ nhân sở hữu hoặc thuyền trưởng phục vụ trên các dòng du thuyền cá nhân (Yacht), thuyền buồm động cơ sang trọng dọc bến sông vịnh quốc gia.",
        skillsLearned: [
          "Cách liên lạc vô tuyến điện hàng hải VHF thông thạo",
          "Học đọc thời tiết, luồng triều tĩnh và hướng gió chính xác",
          "Sử dụng radar hàng hải chống va chạm sương mù đêm",
          "Đón tiếp và phục vụ nghi thức đối ngoại cao cấp trên cabin du thuyền"
        ],
        careerOpportunities: [
          "Thuyền trưởng chuyên trách của giới siêu giàu sở hữu du thuyền",
          "Đại diện lái thử tàu nước, bàn giao nghiệm thu du thuyền nhập khẩu",
          "Kỹ thuật viên bến du thuyền cao cấp (Marina Harbor Manager)",
          "Vận hành du thuyền tiệc tối sang trọng trên Sông Sài Gòn, Vịnh Hạ Long, Sông Hậu"
        ],
        image: "https://images.unsplash.com/photo-1567899378494-47b22a2ae96a?auto=format&fit=crop&w=600&q=80",
        images: [
          "https://images.unsplash.com/photo-1567899378494-47b22a2ae96a?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1605281317010-fe5fed93a4c6?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1540962351504-03099e0a754b?auto=format&fit=crop&w=600&q=80"
        ]
      },
      {
        id: "thuyen-truong-hang-hai",
        code: "T2",
        name: "Bằng Thuyền Trưởng Phương Tiện Thủy Nội Địa Hạng Nhì",
        category: "thuyen-truong",
        duration: "1 tháng",
        level: "Bằng Thuyền Trưởng",
        tuitionFee: 6500000,
        salaryExpectation: "Được phép điều khiển phương tiện tải trọng đến 500 tấn",
        requirements: "Đã có bằng thuyền trưởng hạng Ba ít nhất 12 tháng liên tục hành nghề.",
        description: "Nâng cao nghiệp vụ lái các loại sà lan sông cát đá, tàu chở xăng dầu tầm trung, tàu chở khách lớn liên tỉnh trên các tuyến thủy nội địa quốc gia trọng yếu.",
        skillsLearned: [
          "Quy cách dẫn dắt sà lan đi qua trụ cầu hẹp mùa lũ",
          "Quản lý tàu hàng rời, tính toán phân tải trọng tâm mạn tàu",
          "Kỹ thuật xử lý sóng lớn va chạm trực diện sông ngòi nước chảy dữ",
          "Quản lý thủy thủ đoàn và kịch bản phân trách nhiệm"
        ],
        careerOpportunities: [
          "Thuyền trưởng tàu du lịch hạng trung dọc Mekong, Sông Hậu",
          "Kỹ sư đội điều phối sà lan xây lắp công trình sông biển",
          "Thuyền phó hoặc thuyền trưởng sà lan kéo đẩy vận chuyển liên tỉnh",
          "Nâng lên hạng Nhất sau 24 tháng thực tế hành nghề"
        ],
        image: "https://images.unsplash.com/photo-1505244208947-16f296b1cf33?auto=format&fit=crop&w=600&q=80",
        images: [
          "https://images.unsplash.com/photo-1505244208947-16f296b1cf33?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=600&q=80"
        ]
      }
    ];

    const FAQS_DATA = [
      {
        id: "faq1",
        question: "Bằng lái cano và bằng lái tàu sông có thời hạn sử dụng bao lâu?",
        answer: "Theo quy định mới nhất thuộc Thông tư Bộ Giao thông vận tải, Bằng Thuyền trưởng phương tiện thủy nội địa các hạng thi đỗ sẽ có giá trị SỬ DỤNG VÔ THỜI HẠN đối với cá nhân tham gia bồi dưỡng thông thường."
      },
      {
        id: "faq2",
        question: "Không biết bơi có đăng ký dự thi sát hạch lái cano được không?",
        answer: "Biết bơi là điều kiện bắt buộc tối thiểu đối với học viên trước khi nộp hồ sơ đăng ký thi chứng chỉ đường thủy nội địa để cam kết phòng chống tai nạn trên mặt nước. Tuy vậy, trung tâm có lớp bổ trợ bơi kèm mặc áo phao tiêu chuẩn vượt khó cho học viên chưa biết bơi."
      },
      {
        id: "faq3",
        question: "Thủ tục hồ sơ thi sát hạch bằng lái cano bao gồm những gì?",
        answer: "Học viên chỉ cần chuẩn bị trực tuyến: 1) Bản sao CCCD định danh; 2) Giấy khám sức khỏe lái tàu của cơ sở y tế cấp huyện chỉ định; 3) 4 ảnh chân dung nền xanh 3x4 nét chuẩn; 4) Bản sao bằng lái sơ cấp cũ nếu có để nâng hạng."
      },
      {
        id: "faq4",
        question: "Sự khác nhau giữa Bằng Thuyền Trưởng và Chứng Chỉ Chuyên Môn là gì?",
        answer: "Bằng thuyền trưởng (Hạng 3, 2, 1) là chứng thư cấp quốc gia bắt buộc để điều khiển phương tiện kinh doanh lớn có tải trọng hoặc tàu sông công suất cao. Chứng chỉ chuyên môn (Lái phương tiện, Lái cao tốc, Du thuyền) là module đào tạo bổ sung để hoàn thành các yêu cầu vận hành dòng phương tiện thủy cụ thể."
      }
    ];

    const LARAVEL_11_SNIPPETS = {
      migration: `<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for the maritime education center.
     */
    public function up(): void
    {
        // Table of courses (Yacht/Cano/Boat degree)
        Schema::create('courses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('category', ['cano', 'thuyen-truong', 'cao-toc', 'du-thuyen']);
            $table->string('duration');
            $table->string('level');
            $table->string('salary_expectation');
            $table->text('description');
            $table->json('skills_learned');
            $table->json('career_opportunities');
            $table->string('requirements');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Table of registrations (leads)
        Schema::create('registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('student_name');
            $table->date('dob');
            $table->string('phone');
            $table->string('course_id');
            $table->string('ccid'); // Số định danh CCCD
            $table->date('ccid_date'); // Ngày cấp CCCD
            $table->text('notes')->nullable();
            $table->enum('status', ['Pending', 'Approved'])->default('Pending');
            $table->timestamps();

            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('courses');
    }
};`,
      model: `<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Concerns\\HasUuids;
use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
use Illuminate\\Database\\Eloquent\\Model;
use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;

class Registration extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'student_name',
        'dob',
        'phone',
        'course_id',
        'ccid',
        'ccid_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'ccid_date' => 'date',
    ];

    /**
     * Get the registered maritime course details.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}`,
      controller: `<?php

namespace App\\Http\\Controllers\\Api;

use App\\Http\\Controllers\\Controller;
use App\\Models\\Registration;
use App\\Models\\Course;
use Illuminate\\Http\\Request;
use Illuminate\\Support\\Facades\\Validator;

class RegistrationController extends Controller
{
    /**
     * Fetch all registered students (Admin dashboard)
     */
    public function index()
    {
        $registrations = Registration::with('course')->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $registrations
        ], 200);
    }

    /**
     * Submit a new student registration (leads form API)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:100',
            'dob' => 'required|date',
            'phone' => 'required|string|max:15|regex:/^([0-9\\s\\-\\+\\(\\)]*)$/',
            'course_id' => 'required|string|exists:courses,id',
            'ccid' => 'required|string|min:9|max:12',
            'ccid_date' => 'required|date',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $registration = Registration::create([
            'student_name' => $request->student_name,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'course_id' => $request->course_id,
            'ccid' => $request->ccid,
            'ccid_date' => $request->ccid_date,
            'notes' => $request->notes,
            'status' => 'Pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký nhập học thành công!',
            'data' => $registration->load('course')
        ], 201);
    }

    /**
     * Update dynamic application status
     */
    public function updateStatus(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);
        $request->validate([
            'status' => 'required|in:Pending,Approved'
        ]);

        $registration->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công!',
            'data' => $registration
        ], 200);
    }
}`,
      routes: `<?php

use Illuminate\\Support\\Facades\\Route;
use App\\Http\\Controllers\\Api\\RegistrationController;

/*
|--------------------------------------------------------------------------
| API Routes - Laravel 11 bootstrap
|--------------------------------------------------------------------------
|
| Register endpoints under 'routes/api.php'.
|
|--------------------------------------------------------------------------
*/

Route::prefix('maritime')->group(function () {
    // Public visitor endpoints
    Route::post('/register', [RegistrationController::class, 'store']);
    
    // Administrative endpoints
    Route::get('/registrations', [RegistrationController::class, 'index']);
    Route::patch('/registrations/{id}/status', [RegistrationController::class, 'updateStatus']);
});`
    };

    // ================= STATE MANAGEMENT =================
    let activeTab = 'student';
    let selectedCategory = 'all';
    let prefilledCourseId = '';
    let openFaqId = null;
    let carouselIndexes = {}; // courseId -> currentImageIndex
    let activeSnippet = 'migration';
    let submittedTicket = null;

    // Seed mock data for local storage if empty
    const MOCK_REGISTRATIONS = [
      {
        id: "REG-948512",
        studentName: "Nguyễn Văn Đạt",
        phone: "0345998122",
        dob: "1998-04-18",
        selectedCourseId: "cano-hang-ba",
        ccid: "030098001234",
        ccidDate: "2018-05-10",
        notes: "Muốn học lớp cấp tốc để lái tàu chở khách sông nước miền Tây.",
        createdAt: new Date(Date.now() - 3600000 * 4).toISOString(),
        status: "Pending"
      },
      {
        id: "REG-230591",
        studentName: "Phạm Minh Hoàng",
        phone: "0982334001",
        dob: "2001-10-01",
        selectedCourseId: "du-thuyen-yacht",
        ccid: "034099008877",
        ccidDate: "2020-11-20",
        notes: "Lái du thuyền cá nhân cho gia đình dọc Sông Hậu.",
        createdAt: new Date(Date.now() - 3600000 * 24).toISOString(),
        status: "Approved"
      }
    ];

    if (!localStorage.getItem("vietvoc_registrations")) {
      localStorage.setItem("vietvoc_registrations", JSON.stringify(MOCK_REGISTRATIONS));
    }

    // ================= TAB MANAGEMENT =================
    function switchTab(tab) {
      activeTab = tab;
      
      // Update UI elements
      ['student', 'admin', 'laravel'].forEach(t => {
        const btn = document.getElementById(`tab-btn-${t}`);
        const content = document.getElementById(`tab-content-${t}`);
        
        if (t === tab) {
          content.classList.remove('hidden');
          // Add active class style
          btn.className = "flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg transition-all border shadow-sm " +
            (t === 'laravel' ? "bg-orange-100 text-orange-850 border-orange-350" : "bg-slate-100 text-slate-900 border-slate-300");
        } else {
          content.classList.add('hidden');
          btn.className = "flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg transition-all border text-slate-650 border-transparent hover:bg-slate-50 text-slate-600";
        }
      });

      // Update mobile indicators
      const indicator = document.getElementById('mobile-tab-indicator');
      if (tab === 'student') indicator.textContent = 'Học viên';
      if (tab === 'admin') indicator.textContent = 'Admin';
      if (tab === 'laravel') indicator.textContent = 'Laravel';

      // Load specific tab data
      if (tab === 'admin') {
        loadAdminRegistrations();
      }
      if (tab === 'laravel') {
        renderSnippet();
      }
    }

    // Mobile Hamburger
    let isMobileMenuOpen = false;
    function toggleMobileMenu() {
      isMobileMenuOpen = !isMobileMenuOpen;
      const drawer = document.getElementById('mobile-nav-drawer');
      const icon = document.getElementById('mobile-menu-icon');
      
      if (isMobileMenuOpen) {
        drawer.classList.remove('hidden');
        icon.className = 'bi bi-x-lg text-2xl';
      } else {
        drawer.classList.add('hidden');
        icon.className = 'bi bi-list text-2xl';
      }
    }

    function scrollToSection(sectionId, event, closeMobile = false) {
      if (event) event.preventDefault();
      if (closeMobile) toggleMobileMenu();
      
      switchTab('student');
      setTimeout(() => {
        const target = document.getElementById(sectionId);
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }, 100);
    }

    // ================= CAROUSEL ACTIONS =================
    function handlePrevImage(courseId, length, event) {
      if (event) event.stopPropagation();
      const current = carouselIndexes[courseId] || 0;
      const next = current === 0 ? length - 1 : current - 1;
      carouselIndexes[courseId] = next;
      updateCourseCardImage(courseId);
    }

    function handleNextImage(courseId, length, event) {
      if (event) event.stopPropagation();
      const current = carouselIndexes[courseId] || 0;
      const next = current === length - 1 ? 0 : current + 1;
      carouselIndexes[courseId] = next;
      updateCourseCardImage(courseId);
    }

    function updateCourseCardImage(courseId) {
      const course = COURSES_DATA.find(c => c.id === courseId);
      const index = carouselIndexes[courseId] || 0;
      const imgEl = document.getElementById(`course-img-${courseId}`);
      if (imgEl) {
        imgEl.src = course.images[index];
      }
      
      // Update dot indicators
      const dotsContainer = document.getElementById(`course-dots-${courseId}`);
      if (dotsContainer) {
        const dots = dotsContainer.children;
        for (let i = 0; i < dots.length; i++) {
          if (i === index) {
            dots[i].className = "h-1.5 w-4 rounded-full bg-white transition-all";
          } else {
            dots[i].className = "h-1.5 w-1.5 rounded-full bg-white/40 transition-all";
          }
        }
      }
    }

    // ================= COURSE CATALOG RENDERING =================
    const categories = [
      { value: "all", label: "Tất Cả Khóa Học" },
      { value: "cano", label: "Cano dưới 15 HP" },
      { value: "thuyen-truong", label: "Bằng Thuyền Trưởng" },
      { value: "cao-toc", label: "Cano Cao Tốc" },
      { value: "du-thuyen", label: "Du Thuyền Yacht" }
    ];

    function filterCourses(category) {
      selectedCategory = category;
      renderFilterTabs();
      renderCourseGrid();
    }

    function renderFilterTabs() {
      const container = document.getElementById('course-filter-container');
      container.innerHTML = categories.map(cat => {
        const activeClass = selectedCategory === cat.value
          ? "bg-blue-600 text-white shadow-md shadow-blue-100"
          : "bg-white text-slate-700 border border-slate-200 hover:bg-slate-50";
        return `
          <button onclick="filterCourses('${cat.value}')" class="px-4.5 py-2.5 text-xs sm:text-sm font-semibold rounded-xl transition-all cursor-pointer ${activeClass}">
            ${cat.label}
          </button>
        `;
      }).join('');
    }

    function renderCourseGrid() {
      const container = document.getElementById('courses-grid-container');
      const filtered = selectedCategory === 'all'
        ? COURSES_DATA
        : COURSES_DATA.filter(c => c.category === selectedCategory);

      container.innerHTML = filtered.map(course => {
        const images = course.images || [course.image];
        const currentIndex = carouselIndexes[course.id] || 0;
        const currentImage = images[currentIndex];
        
        let carouselControls = '';
        let dotsIndicators = '';
        
        if (images.length > 1) {
          carouselControls = `
            <button onclick="handlePrevImage('${course.id}', ${images.length}, event)" class="absolute left-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-black/40 hover:bg-black/60 text-white transition-colors cursor-pointer z-10">
              <i class="bi bi-chevron-left"></i>
            </button>
            <button onclick="handleNextImage('${course.id}', ${images.length}, event)" class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-black/40 hover:bg-black/60 text-white transition-colors cursor-pointer z-10">
              <i class="bi bi-chevron-right"></i>
            </button>
          `;

          dotsIndicators = `
            <div id="course-dots-${course.id}" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
              ${images.map((_, idx) => `
                <span class="h-1.5 rounded-full transition-all ${idx === currentIndex ? 'w-4 bg-white' : 'w-1.5 bg-white/40'}"></span>
              `).join('')}
            </div>
          `;
        }

        return `
          <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg hover:border-slate-300 transition-all flex flex-col group">
            <div class="relative h-56 w-full overflow-hidden bg-slate-900">
              <img id="course-img-${course.id}" src="${currentImage}" alt="${course.name}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>
              
              ${carouselControls}
              ${dotsIndicators}

              <div class="absolute top-4 left-4 bg-blue-600 text-white text-[10px] font-bold font-mono px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm flex items-center gap-1">
                <i class="bi bi-anchor"></i> Mã: ${course.code}
              </div>
              <div class="absolute top-4 right-4 bg-slate-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm">
                ${course.level}
              </div>
            </div>

            <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
              <div class="space-y-3">
                <h3 class="font-display text-lg font-bold text-slate-900 hover:text-blue-600 transition-colors line-clamp-1">
                  ${course.name}
                </h3>
                <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 leading-relaxed">
                  ${course.description}
                </p>

                <div class="border-t border-b border-slate-100 py-3.5 space-y-2.5">
                  <div class="flex items-center justify-between text-xs text-slate-650">
                    <span class="flex items-center gap-1.5 text-slate-500"><i class="bi bi-clock text-blue-500"></i> Thời gian học:</span>
                    <span class="font-semibold text-slate-800">${course.duration}</span>
                  </div>
                  <div class="flex items-center justify-between text-xs text-slate-650">
                    <span class="flex items-center gap-1.5 text-slate-500"><i class="bi bi-water text-emerald-500"></i> Hệ đào tạo:</span>
                    <span class="font-semibold text-slate-800">${course.level}</span>
                  </div>
                  <div class="flex items-center justify-between text-xs text-slate-650">
                    <span class="flex items-center gap-1.5 text-slate-500"><i class="bi bi-person-exclamation text-orange-500"></i> Yêu cầu tuổi:</span>
                    <span class="font-semibold text-slate-800 max-w-[150px] truncate">Đủ 18 tuổi trở lên</span>
                  </div>
                  <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-50 mt-1">
                    <span class="text-emerald-600 font-semibold flex items-center gap-1">✓ Chuẩn pháp lý:</span>
                    <span class="font-semibold text-slate-800 text-xs">Cục Đường Thủy VN</span>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between pt-1">
                <div class="flex flex-col">
                  <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Phân hạng điều khiển</span>
                  <span class="text-emerald-600 text-xs font-bold leading-normal">${course.salaryExpectation}</span>
                </div>

                <button onclick="openCourseModal('${course.id}')" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 bg-blue-50/20 rounded-xl transition-all cursor-pointer">
                  Chi tiết hồ sơ
                  <i class="bi bi-arrow-up-right"></i>
                </button>
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    // ================= MODAL ACTIONS =================
    function openCourseModal(courseId) {
      const course = COURSES_DATA.find(c => c.id === courseId);
      if (!course) return;

      document.getElementById('modal-course-code').textContent = `MÃ ĐÀO TẠO: ${course.code}`;
      document.getElementById('modal-course-name').textContent = course.name;
      document.getElementById('modal-course-desc').textContent = course.description;
      document.getElementById('modal-course-req').textContent = course.requirements;
      document.getElementById('modal-level-badge').textContent = course.level;
      document.getElementById('modal-duration-badge').textContent = course.duration;

      // Render Modal Images
      const imageGrid = document.getElementById('modal-image-grid');
      imageGrid.innerHTML = (course.images || [course.image]).map(img => `
        <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
          <img src="${img}" alt="Huấn luyện" class="w-full h-full object-cover" />
        </div>
      `).join('');

      // Render Skills
      const skillsList = document.getElementById('modal-skills-list');
      skillsList.innerHTML = course.skillsLearned.map(skill => `
        <div class="flex items-start gap-2 text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
          <i class="bi bi-check-circle-fill text-emerald-500 shrink-0 mt-0.5"></i>
          <span class="text-xs leading-normal">${skill}</span>
        </div>
      `).join('');

      // Render Career Opportunities
      const careersList = document.getElementById('modal-careers-list');
      careersList.innerHTML = course.careerOpportunities.map(opp => `
        <div class="flex items-center gap-2 text-slate-700">
          <i class="bi bi-chevron-right text-blue-500 shrink-0"></i>
          <span class="text-xs sm:text-sm font-medium">${opp}</span>
        </div>
      `).join('');

      // Setup prefill click handler
      const regBtn = document.getElementById('modal-register-prefill-btn');
      regBtn.onclick = () => {
        prefillRegistrationCourse(course.id);
        closeCourseModal();
      };

      // Show Modal
      const modal = document.getElementById('course-detail-modal');
      modal.classList.remove('hidden');
    }

    function closeCourseModal() {
      const modal = document.getElementById('course-detail-modal');
      modal.classList.add('hidden');
    }

    function prefillRegistrationCourse(courseId) {
      prefilledCourseId = courseId;
      renderRegistrationForm();
      const target = document.getElementById('registration');
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    // ================= REGISTRATION FORM LOGIC =================
    function renderRegistrationForm() {
      const container = document.getElementById('registration-card-body');
      
      if (submittedTicket) {
        // Render success ticket
        const selectedCourse = COURSES_DATA.find(c => c.id === submittedTicket.selectedCourseId) || COURSES_DATA[0];
        container.innerHTML = `
          <div class="p-8 sm:p-12 text-center space-y-6">
            <div class="mx-auto w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200">
              <i class="bi bi-check-circle-fill text-3xl"></i>
            </div>

            <div class="space-y-2">
              <h3 class="font-display text-2xl font-bold text-slate-900">Đăng Ký Thành Công!</h3>
              <p class="text-sm text-slate-500">Mã hồ sơ tuyển sinh của học viên đã được cấp hệ thống:</p>
            </div>

            <div class="max-w-md mx-auto bg-slate-50 border border-slate-200 rounded-2xl p-6 text-left relative overflow-hidden">
              <div class="absolute top-0 right-0 p-3 text-slate-200/40 uppercase font-mono font-bold text-7xl tracking-tighter select-none pointer-events-none">
                PASS
              </div>

              <div class="flex justify-between items-center border-b border-dashed border-slate-200 pb-3 mb-3">
                <span class="text-xs font-mono font-bold text-blue-600 uppercase flex items-center gap-1.5">
                  <i class="bi bi-ticket-perforated-fill text-blue-500"></i> HỒ SƠ SÔNG HẬU
                </span>
                <span class="text-xs font-mono font-bold text-slate-750 bg-slate-200 px-2 py-0.5 rounded">
                  ${submittedTicket.id}
                </span>
              </div>

              <div class="space-y-2.5 text-xs sm:text-sm">
                <div class="flex justify-between">
                  <span class="text-slate-400">Họ và tên:</span>
                  <span class="font-semibold text-slate-800">${submittedTicket.studentName}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-400">Sinh ngày:</span>
                  <span class="font-semibold text-slate-800">
                    ${new Date(submittedTicket.dob).toLocaleDateString("vi-VN")}
                  </span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-400">Số điện thoại:</span>
                  <span class="font-semibold text-slate-800">${submittedTicket.phone}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-400">Số CCCD / Ngày cấp:</span>
                  <span class="font-semibold text-slate-800">
                    ${submittedTicket.ccid} (${submittedTicket.ccidDate ? new Date(submittedTicket.ccidDate).toLocaleDateString("vi-VN") : ""})
                  </span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-400">Ngành dự tuyển:</span>
                  <span class="font-bold text-slate-900 text-right">
                    ${selectedCourse.name}
                  </span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-400">Trạng thái:</span>
                  <span class="font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded text-xs">
                    ✓ Đã nhận hồ sơ điện tử
                  </span>
                </div>
                <div class="flex justify-between border-t border-slate-100 pt-2 text-slate-400 text-[10px]">
                  <span>Thời gian nộp:</span>
                  <span>${new Date(submittedTicket.createdAt).toLocaleString("vi-VN")}</span>
                </div>
              </div>
            </div>

            <div class="space-y-1 max-w-sm mx-auto text-xs text-slate-500 leading-relaxed bg-blue-50/50 p-4 rounded-xl">
              <span class="block font-bold text-blue-900 flex items-center gap-1 justify-center mb-1"><i class="bi bi-sparkles text-blue-600"></i> Các bước tiếp theo:</span>
              <p>1. Phòng đào tạo sẽ gọi lại để kích hoạt lịch thi thử và xếp lớp.</p>
              <p>2. Học viên mang CCCD gốc đến địa chỉ trung tâm để nộp hồ sơ giấy đối chiếu.</p>
            </div>

            <button onclick="resetRegistrationForm()" class="px-6 py-3 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition-all">
              Đăng ký cho học viên khác
            </button>
          </div>
        `;
      } else {
        // Render input fields
        const selectedId = prefilledCourseId || COURSES_DATA[0].id;
        const selectedCourse = COURSES_DATA.find(c => c.id === selectedId) || COURSES_DATA[0];

        container.innerHTML = `
          <form onsubmit="submitRegistration(event)" class="p-6 sm:p-10 space-y-6">
            <div id="form-error-banner" class="hidden bg-rose-50 border border-rose-200 text-rose-700 text-xs sm:text-sm rounded-xl p-3.5 font-medium"></div>

            <div class="grid sm:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Họ và Tên Học Viên <span class="text-red-500">*</span></label>
                <input id="reg-student-name" type="text" placeholder="Ví dụ: Nguyễn Văn A" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all text-slate-800">
              </div>

              <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Sinh Ngày <span class="text-red-500">*</span></label>
                <input id="reg-dob" type="date" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all text-slate-800">
              </div>

              <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Điện Thoại Liên Hệ <span class="text-red-500">*</span></label>
                <input id="reg-phone" type="tel" placeholder="Ví dụ: 0325XXXXXX" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all text-slate-800">
              </div>

              <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Ngành Nghề Muốn Học <span class="text-red-500">*</span></label>
                <select id="reg-course-select" onchange="onRegistrationCourseChange(this.value)" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all text-slate-800">
                  ${COURSES_DATA.map(c => `
                    <option value="${c.id}" ${c.id === selectedId ? 'selected' : ''}>${c.name} (${c.level})</option>
                  `).join('')}
                </select>
              </div>

              <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Số CCCD <span class="text-red-500">*</span></label>
                <input id="reg-ccid" type="text" placeholder="Ví dụ: 030012345678" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all text-slate-800">
              </div>

              <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Ngày Cấp CCCD <span class="text-red-500">*</span></label>
                <input id="reg-ccid-date" type="date" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all text-slate-800">
              </div>
            </div>

            <div class="space-y-2">
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Ghi Chú & Nguyện Vọng Riêng (Nếu có)</label>
              <textarea id="reg-notes" placeholder="Nhập nguyện vọng học tập bổ sung, thời gian rảnh hoặc ghi chú định hướng nghề nghiệp..." rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all text-slate-800"></textarea>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 space-y-2.5">
              <span class="text-[10px] font-bold font-mono tracking-wider text-slate-400 block uppercase">Yêu cầu sát hạch tối thiểu</span>
              <div class="flex flex-wrap justify-between items-center gap-2">
                <div>
                  <span class="text-xs text-slate-500 block">Chương trình đào tạo:</span>
                  <span id="form-summary-name" class="text-sm font-bold text-slate-800 leading-none">${selectedCourse.name}</span>
                </div>
                <div class="text-right">
                  <span class="text-xs font-bold text-emerald-600 block">✓ Đủ điều kiện đăng ký</span>
                  <span id="form-summary-level" class="text-[11px] text-slate-500 font-medium block">${selectedCourse.level} - Yêu cầu tuổi: 18+</span>
                </div>
              </div>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-tr from-blue-700 to-indigo-600 hover:from-blue-600 hover:to-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer">
              <i class="bi bi-send-fill"></i> Nộp Phiếu Đăng Ký Nhập Học
            </button>

            <div class="flex items-center justify-center gap-2 text-xs text-slate-400">
              <i class="bi bi-shield-lock text-emerald-500 text-sm"></i> Thông tin hồ sơ được bảo mật theo quy định tuyển sinh.
            </div>
          </form>
        `;
      }
    }

    function onRegistrationCourseChange(courseId) {
      prefilledCourseId = courseId;
      const course = COURSES_DATA.find(c => c.id === courseId);
      document.getElementById('form-summary-name').textContent = course.name;
      document.getElementById('form-summary-level').textContent = `${course.level} - Yêu cầu tuổi: 18+`;
    }

    function submitRegistration(e) {
      e.preventDefault();
      const errorBanner = document.getElementById('form-error-banner');
      errorBanner.classList.add('hidden');

      const name = document.getElementById('reg-student-name').value.trim();
      const dob = document.getElementById('reg-dob').value;
      const phone = document.getElementById('reg-phone').value.trim();
      const courseId = document.getElementById('reg-course-select').value;
      const ccid = document.getElementById('reg-ccid').value.trim();
      const ccidDate = document.getElementById('reg-ccid-date').value;
      const notes = document.getElementById('reg-notes').value.trim();

      // Simple Validation
      if (!name) { showFormError("Vui lòng nhập họ và tên học viên."); return; }
      if (!dob) { showFormError("Vui lòng nhập ngày tháng năm sinh."); return; }
      if (!phone || phone.length < 8) { showFormError("Vui lòng nhập số điện thoại hợp lệ."); return; }
      if (!ccid || ccid.length < 9) { showFormError("Vui lòng nhập số CCCD hợp lệ (tối thiểu 9 số)."); return; }
      if (!ccidDate) { showFormError("Vui lòng nhập ngày cấp CCCD."); return; }

      const newRecord = {
        id: "REG-" + Math.floor(100000 + Math.random() * 900000),
        studentName: name,
        dob,
        phone,
        selectedCourseId: courseId,
        ccid,
        ccidDate,
        notes,
        createdAt: new Date().toISOString(),
        status: "Pending"
      };

      const existing = JSON.parse(localStorage.getItem("vietvoc_registrations") || "[]");
      localStorage.setItem("vietvoc_registrations", JSON.stringify([newRecord, ...existing]));

      submittedTicket = newRecord;
      renderRegistrationForm();
    }

    function showFormError(msg) {
      const banner = document.getElementById('form-error-banner');
      banner.textContent = `⚠️ ${msg}`;
      banner.classList.remove('hidden');
    }

    function resetRegistrationForm() {
      submittedTicket = null;
      prefilledCourseId = '';
      renderRegistrationForm();
    }

    // ================= FAQ ACCORDION LOGIC =================
    function toggleFaqAccordion(faqId) {
      openFaqId = openFaqId === faqId ? null : faqId;
      renderFaqs();
    }

    function renderFaqs() {
      const container = document.getElementById('faq-container-list');
      container.innerHTML = FAQS_DATA.map(faq => {
        const isOpen = openFaqId === faq.id;
        return `
          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden transition-all">
            <button onclick="toggleFaqAccordion('${faq.id}')" class="w-full p-5 flex justify-between items-center text-left text-sm sm:text-base font-bold text-slate-800 hover:text-blue-600 transition-colors">
              <span>${faq.question}</span>
              <i class="bi ${isOpen ? 'bi-chevron-up text-blue-600' : 'bi-chevron-down text-slate-400'} shrink-0 ml-4"></i>
            </button>
            
            ${isOpen ? `
              <div class="px-5 pb-5 pt-1 text-xs sm:text-sm text-slate-650 leading-relaxed border-t border-slate-50 fade-in text-slate-600">
                ${faq.answer}
              </div>
            ` : ''}
          </div>
        `;
      }).join('');
    }

    // ================= ADMIN REGISTERED STUDENTS PANEL =================
    let adminSearchQuery = '';
    let adminStatusFilter = 'all';

    function loadAdminRegistrations() {
      const records = JSON.parse(localStorage.getItem("vietvoc_registrations") || "[]");
      renderAdminTable(records);
    }

    function renderAdminTable(records) {
      const tbody = document.getElementById('admin-table-tbody');
      const emptyState = document.getElementById('admin-empty-state');
      
      const filtered = records.filter(r => {
        const query = adminSearchQuery.toLowerCase();
        const matchesSearch = r.studentName.toLowerCase().includes(query) ||
                              r.phone.includes(query) ||
                              r.id.toLowerCase().includes(query);
        const matchesStatus = adminStatusFilter === 'all' || r.status === adminStatusFilter;
        return matchesSearch && matchesStatus;
      });

      if (filtered.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
      } else {
        emptyState.classList.add('hidden');
        tbody.innerHTML = filtered.map(item => {
          const course = COURSES_DATA.find(c => c.id === item.selectedCourseId) || { name: 'Chưa xác định' };
          
          let actionBtn = '';
          if (item.status !== 'Approved') {
            actionBtn = `
              <button onclick="approveRegistration('${item.id}')" class="p-1 px-2 text-[11px] hover:bg-blue-605 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 rounded-lg transition-colors inline-flex items-center gap-1">
                <i class="bi bi-person-check"></i> Duyệt
              </button>
            `;
          }

          const statusBadge = item.status === 'Approved'
            ? `<span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-[11px] px-2.5 py-0.5 rounded-full font-semibold border border-green-200">
                <i class="bi bi-check2-circle"></i> Đã xét duyệt
               </span>`
            : `<span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-[11px] px-2.5 py-0.5 rounded-full font-semibold border border-amber-200">
                <i class="bi bi-hourglass-split"></i> Chờ xét tuyển
               </span>`;

          return `
            <tr class="hover:bg-slate-50/70 transition-colors">
              <td class="p-4 pl-6 space-y-1">
                <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded text-xs">${item.id}</span>
                <div class="flex items-center gap-1 text-[11px] text-slate-400 mt-1">
                  <i class="bi bi-clock"></i> ${new Date(item.createdAt).toLocaleDateString("vi-VN")}
                </div>
              </td>

              <td class="p-4 space-y-1">
                <span class="font-semibold text-slate-900 block text-sm">${item.studentName}</span>
                <div class="space-y-0.5 text-slate-500 text-xs">
                  <p class="flex items-center gap-1.5"><i class="bi bi-telephone text-slate-400"></i> ${item.phone}</p>
                  <p class="flex items-center gap-1.5"><i class="bi bi-calendar3 text-slate-400"></i> Sinh nhật: ${new Date(item.dob).toLocaleDateString("vi-VN")}</p>
                </div>
              </td>

              <td class="p-4">
                <span class="font-semibold text-slate-800 text-xs block max-w-[220px] truncate" title="${course.name}">
                  ${course.name}
                </span>
              </td>

              <td class="p-4 space-y-0.5">
                <span class="font-mono font-bold text-slate-800 block text-xs">${item.ccid}</span>
                <span class="text-[11px] text-slate-400 block">Cấp ngày: ${item.ccidDate ? new Date(item.ccidDate).toLocaleDateString("vi-VN") : "N/A"}</span>
              </td>

              <td class="p-4 space-y-1.5">
                <div>${statusBadge}</div>
                ${item.notes ? `<p class="text-[11px] text-slate-500 italic max-w-[200px] truncate" title="${item.notes}">"${item.notes}"</p>` : ''}
              </td>

              <td class="p-4 pr-6 text-right space-x-1.5 whitespace-nowrap">
                ${actionBtn}
                <button onclick="deleteRegistration('${item.id}')" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Xóa hồ sơ">
                  <i class="bi bi-trash text-lg"></i>
                </button>
              </td>
            </tr>
          `;
        }).join('');
      }
    }

    function filterAdminRecords() {
      adminSearchQuery = document.getElementById('admin-search-input').value;
      adminStatusFilter = document.getElementById('admin-status-filter').value;
      loadAdminRegistrations();
    }

    function approveRegistration(id) {
      const records = JSON.parse(localStorage.getItem("vietvoc_registrations") || "[]");
      const updated = records.map(r => {
        if (r.id === id) {
          return { ...r, status: 'Approved' };
        }
        return r;
      });
      localStorage.setItem("vietvoc_registrations", JSON.stringify(updated));
      loadAdminRegistrations();
    }

    function deleteRegistration(id) {
      if (confirm("Bạn có chắc chắn muốn xóa hồ sơ tuyển sinh này?")) {
        const records = JSON.parse(localStorage.getItem("vietvoc_registrations") || "[]");
        const filtered = records.filter(r => r.id !== id);
        localStorage.setItem("vietvoc_registrations", JSON.stringify(filtered));
        loadAdminRegistrations();
      }
    }

    function clearAllRegistrations() {
      if (confirm("Cảnh báo: Bạn muốn xóa toàn bộ danh sách đăng ký thử nghiệm khỏi trình duyệt?")) {
        localStorage.setItem("vietvoc_registrations", "[]");
        loadAdminRegistrations();
      }
    }

    // ================= INTEGRATION SNIPPET LOGIC =================
    function switchSnippet(snippetKey) {
      activeSnippet = snippetKey;
      
      // Toggle button styles
      ['migration', 'model', 'controller', 'routes'].forEach(k => {
        const btn = document.getElementById(`snippet-btn-${k}`);
        if (k === snippetKey) {
          btn.className = "flex items-center gap-2.5 px-4 py-3 text-xs font-bold rounded-xl whitespace-nowrap transition-all w-full text-left bg-orange-500 text-white shadow-md shadow-orange-500/20";
        } else {
          btn.className = "flex items-center gap-2.5 px-4 py-3 text-xs font-bold rounded-xl whitespace-nowrap transition-all w-full text-left bg-white/5 text-slate-300 hover:bg-white/10";
        }
      });

      renderSnippet();
    }

    function renderSnippet() {
      const codeBlock = document.getElementById('code-content-block');
      const fileName = document.getElementById('code-file-name');
      
      codeBlock.textContent = LARAVEL_11_SNIPPETS[activeSnippet];

      let name = '';
      if (activeSnippet === 'migration') name = 'database/migrations/2026_06_15_create_vocational_tables.php';
      if (activeSnippet === 'model') name = 'app/Models/Registration.php';
      if (activeSnippet === 'controller') name = 'app/Http/Controllers/Api/RegistrationController.php';
      if (activeSnippet === 'routes') name = 'routes/api.php';

      fileName.textContent = name;
    }

    function copySnippetToClipboard() {
      const codeText = LARAVEL_11_SNIPPETS[activeSnippet];
      navigator.clipboard.writeText(codeText).then(() => {
        const btnText = document.getElementById('copy-btn-text');
        btnText.textContent = 'Coppy thành công!';
        setTimeout(() => {
          btnText.textContent = 'Copy Mã Nguồn Của Tab';
        }, 2000);
      });
    }

    // ================= INITIAL RUN =================
    document.addEventListener("DOMContentLoaded", () => {
      renderFilterTabs();
      renderCourseGrid();
      renderRegistrationForm();
      renderFaqs();
    });
  </script>

</body>

</html>
