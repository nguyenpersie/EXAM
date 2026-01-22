@extends('layouts.master')

@section('title', 'Chi tiết: ' . $exam->title)

@section('content')
<div class="container py-4">
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
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
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
                        <label class="text-muted small">Tổng số câu hỏi</label>
                        <div class="fs-4 text-primary">
                            <i class="bi bi-question-circle"></i> 
                            <strong>{{ $exam->questions->count() }}</strong> câu
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

                    <div class="mb-3">
                        <label class="text-muted small">Trạng thái</label>
                        <div>
                            @if($exam->is_active)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Đang hoạt động
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle"></i> Không hoạt động
                                </span>
                            @endif
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
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc muốn xóa đề thi này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> Xóa đề thi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách câu hỏi -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ol"></i> Danh sách câu hỏi ({{ $exam->questions->count() }})</h5>
                    <a href="{{ route('questions.create', $exam->id) }}" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle"></i> Thêm câu hỏi
                    </a>
                </div>
                <div class="card-body p-0" style="max-height: 70vh; overflow-y: auto;">
                    @forelse($exam->questions as $index => $question)
                    <div class="border-bottom p-3 hover-bg">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-2">
                                    <span class="badge bg-primary">Câu {{ $index + 1 }}</span>
                                    @if($question->level)
                                        <span class="badge bg-{{ $question->level == 'easy' ? 'success' : ($question->level == 'medium' ? 'warning' : 'danger') }}">
                                            {{ $question->level == 'easy' ? 'Dễ' : ($question->level == 'medium' ? 'TB' : 'Khó') }}
                                        </span>
                                    @endif
                                    @if($question->section)
                                        <span class="badge bg-info">{{ $question->section }}</span>
                                    @endif
                                </h6>
                                <div class="mb-2">{!! nl2br(e($question->content)) !!}</div>
                                
                                <!-- Đáp án -->
                                <div class="ms-3">
                                    @foreach($question->options as $optIndex => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled 
                                               {{ $option->is_correct ? 'checked' : '' }}>
                                        <label class="form-check-label {{ $option->is_correct ? 'text-success fw-bold' : '' }}">
                                            <strong>{{ chr(65 + $optIndex) }}.</strong> {{ $option->content }}
                                            @if($option->is_correct)
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @endif
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="ms-2">
                                <div class="btn-group-vertical btn-group-sm">
                                    <a href="{{ route('questions.edit', $question->id) }}" 
                                       class="btn btn-outline-primary" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                          onsubmit="return confirm('Xóa câu hỏi này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Chưa có câu hỏi nào</p>
                        <a href="{{ route('questions.create', $exam->id) }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Thêm câu hỏi đầu tiên
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê nhanh -->
    @if($exam->questions->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Thống kê câu hỏi</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-box">
                                <i class="bi bi-check-circle text-success fs-1"></i>
                                <h3 class="mt-2">{{ $exam->questions->where('level', 'easy')->count() }}</h3>
                                <p class="text-muted mb-0">Câu dễ</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <i class="bi bi-dash-circle text-warning fs-1"></i>
                                <h3 class="mt-2">{{ $exam->questions->where('level', 'medium')->count() }}</h3>
                                <p class="text-muted mb-0">Câu trung bình</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <i class="bi bi-x-circle text-danger fs-1"></i>
                                <h3 class="mt-2">{{ $exam->questions->where('level', 'hard')->count() }}</h3>
                                <p class="text-muted mb-0">Câu khó</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <i class="bi bi-list-ol text-primary fs-1"></i>
                                <h3 class="mt-2">{{ $exam->questions->count() }}</h3>
                                <p class="text-muted mb-0">Tổng câu hỏi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.hover-bg:hover {
    background-color: #f8f9fa;
}
.stat-box {
    padding: 1rem;
}
</style>
@endsection