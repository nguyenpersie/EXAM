@extends('layouts.master')

@section('title', 'Chi tiết: ' . $exam->title)

@section('content')
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-x-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <nav class="breadcrumb">
                    <ul class="breadcrumb-links">
                        <li>
                            <a href="{{ route('exams.index') }}" class="breadcrumb-box">
                                <svg class="breadcrumb-icon-home" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="breadcrumb-text">Danh sách đề thi</span>
                            </a>
                        </li>
                        <li>
                            <div class="breadcrumb-box">
                                <svg class="breadcrumb-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="breadcrumb-text">{{ $exam->code }}</span>
                            </div>
                        </li>
                    </ul>
                </nav>
                <h2><i class="bi bi-file-earmark-text"></i> {{ $exam->title }}</h2>
                <p class="text-muted">Mã đề: <strong>{{ $exam->code }}</strong></p>
            </div>
        </div>

        <!-- Mode Selection Tabs -->
        <ul class="nav nav-tabs mb-4" id="modeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="test-tab" data-bs-toggle="tab" data-bs-target="#test-mode" type="button"
                    role="tab" aria-controls="test-mode" aria-selected="true">
                    <i class="bi bi-trophy"></i> Thi Thử
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="practice-tab" data-bs-toggle="tab" data-bs-target="#practice-mode"
                    type="button" role="tab" aria-controls="practice-mode" aria-selected="false">
                    <i class="bi bi-book"></i> Ôn Tập Theo Phần
                </button>
            </li>
        </ul>

        <div class="tab-content" id="modeTabContent">
            <!-- Test Mode Tab -->
            <div class="tab-pane fade show active" id="test-mode" role="tabpanel" aria-labelledby="test-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-trophy text-warning" style="font-size: 4rem;"></i>
                                <h3 class="mt-3">Thi Thử - 30 Câu</h3>
                                <p class="text-muted mb-4">
                                    Làm bài thi với 30 câu hỏi, có chấm điểm và đánh giá kết quả
                                </p>
                                <a href="{{ route('exams.test', $exam->id) }}" class="btn btn-success btn-lg">
                                    <i class="bi bi-play-circle"></i> Bắt Đầu Thi Thử
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Practice Mode Tab -->
            <div class="tab-pane fade" id="practice-mode" role="tabpanel" aria-labelledby="practice-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Chế độ ôn tập:</strong> Chọn phần để học, xem đáp án ngay lập tức, không có chấm điểm
                        </div>
                        <div id="sections-list" class="row">
                            <div class="col-12 text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Đang tải...</span>
                                </div>
                                <p class="mt-2 text-muted">Đang tải danh sách phần...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Thông tin đề thi -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin đề thi</h5>
                    </div>
                    <div class="card-body">
                        @if($exam->category)
                            <div class="mb-3">
                                <label class="text-muted small">Danh mục</label>
                                <div><span class="badge bg-info">{{ $exam->category }}</span></div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="text-muted small">Tổng câu hỏi trong ngân hàng</label>
                            <div class="fs-4 text-primary">
                                <i class="bi bi-database"></i>
                                <strong>{{ $exam->questions->count() }}</strong> câu
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Số câu mỗi lần thi</label>
                            <div class="fs-5 text-info">
                                <i class="bi bi-shuffle"></i>
                                <strong>30</strong> câu (random)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Thời gian làm bài</label>
                            <div class="fs-5 text-warning">
                                <i class="bi bi-clock"></i>
                                <strong>{{ $exam->duration_minutes }}</strong> phút
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Điểm tối đa</label>
                            <div class="fs-5 text-success">
                                <i class="bi bi-trophy"></i>
                                <strong>{{ $exam->total_score }}</strong> điểm
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Điểm đạt yêu cầu</label>
                            <div class="fs-5 text-danger">
                                <i class="bi bi-check-circle"></i>
                                <strong>{{ $exam->passing_score }}</strong> điểm
                            </div>
                        </div>

                        @if($exam->description)
                            <div class="mb-3">
                                <label class="text-muted small">Mô tả</label>
                                <p class="mb-0">{{ $exam->description }}</p>
                            </div>
                        @endif

                        <div class="text-muted small">
                            <i class="bi bi-calendar"></i> Tạo lúc: {{ $exam->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Thống kê -->
                @if($exam->questions->count() > 0)
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Thống kê câu hỏi</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $categories = $exam->questions->pluck('category')->filter()->unique();
                                $byLevel = $exam->questions->groupBy('level');
                            @endphp

                            <div class="mb-3">
                                <strong>Theo độ khó:</strong>
                                <div class="d-flex gap-2 mt-2">
                                    <span class="badge bg-success">Dễ: {{ $byLevel->get('1', collect())->count() }}</span>
                                    <span class="badge bg-warning">TB: {{ $byLevel->get('2', collect())->count() }}</span>
                                    <span class="badge bg-danger">Khó: {{ $byLevel->get('3', collect())->count() }}</span>
                                </div>
                            </div>

                            @if($categories->count() > 0)
                                <div>
                                    <strong>Theo danh mục:</strong>
                                    <div class="mt-2">
                                        @foreach($categories as $cat)
                                            <span class="badge bg-info me-1 mb-1">
                                                {{ $cat }}: {{ $exam->questions->where('category', $cat)->count() }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Import câu hỏi -->
            <div class="col-lg-7 mb-4">
                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'center']))
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-upload"></i> Import câu hỏi</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('questions.import', $exam->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Chọn file Word <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file" accept=".docx,.doc" required>
                                    <small class="text-muted">Hỗ trợ: .docx, .doc (tối đa 10MB)</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Danh mục câu hỏi (tùy chọn)</label>
                                    <input type="text" class="form-control" name="category"
                                        placeholder="VD: Biển báo, An toàn giao thông...">
                                    <small class="text-muted">Nếu để trống, sẽ lấy từ trường "Danh mục:" trong file
                                        Word</small>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-upload"></i> Import câu hỏi
                                </button>

                                <a href="{{ asset('templates/question-template.docx') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-download"></i> Tải file mẫu
                                </a>
                            </form>
                        </div>
                    </div>
                @endif

            </div>


            <!-- Quản lý -->
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'center']))
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-gear"></i> Quản lý</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Chỉnh sửa thông tin đề thi
                            </a>
                            <a href="{{ route('questions.index', $exam->id) }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-list-check"></i> Quản lý câu hỏi ({{ $exam->questions->count() }})
                            </a>

                            @if($exam->questions->count() > 0)
                                <form action="{{ route('questions.destroyAll', $exam->id) }}" method="POST"
                                    onsubmit="return confirm('Xóa TẤT CẢ {{ $exam->questions->count() }} câu hỏi? Hành động này không thể hoàn tác!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-warning w-100">
                                        <i class="bi bi-trash"></i> Xóa tất cả câu hỏi ({{ $exam->questions->count() }})
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('exams.destroy', $exam->id) }}" method="POST"
                                onsubmit="return confirm('Xóa đề thi này? Tất cả câu hỏi cũng sẽ bị xóa!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-trash"></i> Xóa đề thi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    </div>

    <script>
        // Fetch sections when practice tab is clicked
        document.getElementById('practice-tab').addEventListener('shown.bs.tab', function () {
            const sectionsList = document.getElementById('sections-list');

            // Only fetch if not already loaded
            if (sectionsList.dataset.loaded === 'true') return;

            fetch('/exams/{{ $exam->id }}/sections')
                .then(response => response.json())
                .then(sections => {
                    if (sections.length === 0) {
                        sectionsList.innerHTML = `
                                                                                <div class="col-12 text-center py-5">
                                                                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                                                                    <p class="mt-3 text-muted">Chưa có phần nào để ôn tập</p>
                                                                                </div>
                                                                            `;
                        return;
                    }

                    let html = '';
                    sections.forEach(section => {
                        html += `
                                                                                <div class="col-md-6 col-lg-4 mb-3">
                                                                                    <div class="card h-100 shadow-sm">
                                                                                        <div class="card-body">
                                                                                            <h5 class="card-title">
                                                                                                <i class="bi bi-bookmark-fill text-primary"></i>
                                                                                                Phần ${section.section}
                                                                                            </h5>
                                                                                            <p class="card-text text-muted">
                                                                                                <i class="bi bi-question-circle"></i> ${section.count} câu hỏi
                                                                                            </p>
                                                                                            <a href="/exams/{{ $exam->id }}/test?mode=practice&section=${section.section}" 
                                                                                               class="btn btn-primary btn-sm w-100">
                                                                                                <i class="bi bi-book"></i> Bắt Đầu Ôn Tập
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            `;
                    });

                    sectionsList.innerHTML = html;
                    sectionsList.dataset.loaded = 'true';
                })
                .catch(error => {
                    console.error('Error:', error);
                    sectionsList.innerHTML = `
                                                                            <div class="col-12 text-center py-5">
                                                                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                                                                                <p class="mt-3 text-danger">Lỗi tải danh sách phần. Vui lòng thử lại!</p>
                                                                            </div>
                                                                        `;
                });
        });
    </script>
@endsection