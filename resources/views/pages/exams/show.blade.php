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
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">Danh sách đề thi</a></li>
                        <li class="breadcrumb-item active">{{ $exam->code }}</li>
                    </ol>
                </nav>
                <h2><i class="bi bi-file-earmark-text"></i> {{ $exam->title }}</h2>
                <p class="text-muted">Mã đề: <strong>{{ $exam->code }}</strong></p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('exams.test', $exam->id) }}" class="btn btn-success btn-lg">
                    <i class="bi bi-play-circle"></i> Bắt đầu làm bài
                </a>
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
                                    <span class="badge bg-success">Dễ: {{ $byLevel->get('easy', collect())->count() }}</span>
                                    <span class="badge bg-warning">TB: {{ $byLevel->get('medium', collect())->count() }}</span>
                                    <span class="badge bg-danger">Khó: {{ $byLevel->get('hard', collect())->count() }}</span>
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
                @if(auth()->check() && auth()->user()->isAdmin())
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
                                    <small class="text-muted">Nếu để trống, sẽ lấy từ trường "Danh mục:" trong file Word</small>
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

                <!-- Hướng dẫn -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Định dạng file Word (.docx)</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Mỗi câu hỏi theo cấu trúc:</strong></p>
                        <div class="border p-3 bg-light small" style="font-family: monospace;">
                            Câu 1: Biển báo nào cấm đi ngược chiều?<br>
                            A. Biển P.123<br>
                            B. Biển P.124<br>
                            C. Biển P.125<br>
                            D. Biển W.201<br>
                            Đáp án: A<br>
                            Phần: Phần 1 - Biển báo<br>
                            Độ khó: easy<br>
                            Danh mục: Biển báo<br>
                            ---<br>
                            <br>
                            Câu 2: Tốc độ tối đa trong khu dân cư là bao nhiêu?<br>
                            A. 40 km/h<br>
                            B. 50 km/h<br>
                            C. 60 km/h<br>
                            D. 70 km/h<br>
                            Đáp án: C<br>
                            ---
                        </div>

                        <div class="alert alert-warning mt-3 mb-0 small">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Lưu ý:</strong><br>
                            • Mỗi câu phân cách bằng dấu --- hoặc ===<br>
                            • Đáp án đúng: ghi A, B, C hoặc D<br>
                            • Phần, Độ khó, Danh mục là tùy chọn<br>
                            • Độ khó: easy (dễ), medium (trung bình), hard (khó)
                        </div>
                    </div>
                </div>


                <!-- Quản lý -->
                @if(auth()->check() && auth()->user()->isAdmin())
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
@endsection