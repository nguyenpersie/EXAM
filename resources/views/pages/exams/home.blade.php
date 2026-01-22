@extends('layouts.master')
@section('content')
{{-- <div class="container-fluid py-3">
      <div class="row g-3">
        <div class="col-lg-3 col-md-3">
          <div class="student-card">
            <div class="row">
              <div class="col-4 px-1">
                <img
                  src="https://cafefcdn.com/thumb_w/640/203337114487263232/2022/3/3/photo1646280815645-1646280816151764748403.jpg"
                  class="student-avatar"
                  alt="Avatar"
                />
              </div>
              <div class="col-8">
                <div class="mb-1">
                  <span class="info-label">Số báo danh:</span> <span class="info-value">LPT.SH.001</span>
                </div>
                <div class="mb-1">
                  <span class="info-label">Họ tên:</span>
                  <span class="info-value text-uppercase">Đặng Thành Nguyên</span>
                </div>
                <div class="mb-1"><span class="info-label">Giới tính:</span> <span class="info-value">Nam</span></div>
                <div class="mb-1">
                  <span class="info-label">Ngày sinh:</span> <span class="info-value">26/02/2026</span>
                </div>
                <div class="mb-1">
                  <span class="info-label">Đơn vị:</span> <span class="info-value">TTDN Đường Thủy Sông Hậu</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-md-6">
          <div class="question-box shadow-sm">
            <div class="q-header">
              <h5 class="q-title" id="display-q-num">Nội dung câu hỏi 1</h5>
              <button class="btn btn-outline-warning btn-sm" id="btn-flag" onclick="toggleFlag()">
                <i class="bi bi-flag"></i> Đánh dấu
              </button>
            </div>

            <div class="q-body">
              <div id="q-content-area">
                <p class="text-center text-muted mt-5">Đang tải dữ liệu...</p>
              </div>
            </div>
          </div>

          <div class="nav-actions">
            <button class="btn btn-nav" id="btn-prev" onclick="changeQuestion(-1)">
              <i class="bi bi-chevron-left"></i> Trở lại
            </button>
            <button class="btn btn-nav" id="btn-next" onclick="changeQuestion(1)">
              Tiếp tục <i class="bi bi-chevron-right"></i>
            </button>
          </div>
        </div>

        <div class="col-lg-3 col-md-3">
          <div class="timer-box">
            <div class="fw-bold border-bottom pb-1 mb-2">Đang thi</div>
            <div class="d-flex justify-content-between"><span>Thời gian:</span> <span>45 phút</span></div>
            <div class="d-flex justify-content-between"><span>Bù giờ:</span> <span>0 phút</span></div>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <span class="fw-bold">Còn lại:</span>
              <span class="timer-countdown" id="timer-display">45:00</span>
            </div>
          </div>

          <div class="sheet-wrapper">
            <table class="table-sheet" id="answer-table">
              <thead>
                <tr>
                  <th>Câu</th>
                  <th>A</th>
                  <th>B</th>
                  <th>C</th>
                  <th>D</th>
                </tr>
              </thead>
              <tbody id="sheet-body"></tbody>
            </table>
          </div>

          <button class="btn btn-submit shadow" onclick="confirmSubmit()">NỘP BÀI</button>
        </div>
      </div>
</div> --}}


<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-journal-text"></i> Danh sách đề thi ôn tập</h2>
            <p class="text-muted">Chọn đề thi và bắt đầu ôn luyện</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('exams.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tạo đề thi mới
            </a>
        </div>
    </div>

    <!-- Lọc theo danh mục -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('exams.index') }}" class="btn btn-sm {{ request()->is('/') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Tất cả
                </a>
                @php
                    $categories = \App\Models\Exam::select('category')->distinct()->whereNotNull('category')->pluck('category');
                @endphp
                @foreach($categories as $cat)
                    <a href="{{ route('exams.category', $cat) }}"
                       class="btn btn-sm {{ request('category') == $cat ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Danh sách đề thi -->
    <div class="row">
        @forelse($exams as $exam)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">{{ $exam->title }}</h5>
                    <small>Mã: {{ $exam->code }}</small>
                </div>
                <div class="card-body">
                    @if($exam->category)
                    <span class="badge bg-info mb-2">{{ $exam->category }}</span>
                    @endif

                    <div class="mb-2">
                        <i class="bi bi-question-circle text-primary"></i>
                        <strong>{{ $exam->questions_count }}</strong> câu hỏi
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-clock text-warning"></i>
                        <strong>{{ $exam->duration_formatted }}</strong>
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-trophy text-success"></i>
                        Điểm đạt: <strong>{{ $exam->passing_score }}/{{ $exam->total_score }}</strong>
                    </div>

                    @if($exam->description)
                    <p class="text-muted small mt-2">{{ Str::limit($exam->description, 100) }}</p>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex gap-2">
                        <a href="{{ route('exams.test', $exam->id) }}" class="btn btn-success flex-fill">
                            <i class="bi bi-play-circle"></i> Bắt đầu
                        </a>
                        <a href="{{ route('exams.show', $exam->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle fs-1"></i>
                <p class="mb-0 mt-2">Chưa có đề thi nào. Hãy tạo đề thi mới!</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
