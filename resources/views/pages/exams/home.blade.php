@extends('layouts.master')
@section('content')
  <div class="container py-4">
    <div class="row mb-4">
      <div class="col-md-7">
        <h2><i class="bi bi-journal-text"></i> Danh sách đề thi ôn tập</h2>
        <p class="text-muted">Chọn đề thi và bắt đầu ôn luyện</p>
      </div>
      <div class="col-md-5 text-end d-flex justify-content-end align-items-center gap-2">
        @if(auth()->check() && auth()->user()->isAdmin())
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary" title="Quản lý học viên">
            <i class="bi bi-people"></i>
          </a>
        @endif
        @if(auth()->check() && auth()->user()->canManageContent())
          <a href="{{ route('exams.create') }}" class="btn btn-primary" title="Tạo đề thi mới">
            <i class="bi bi-plus-circle"></i> Tạo đề
          </a>
        @endif

        @if(auth()->check() && auth()->user()->isAdmin())
          <a href="{{ route('change-password') }}" class="btn btn-outline-secondary" title="Đổi mật khẩu">
            <i class="bi bi-key"></i>
          </a>
        @endif

        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-outline-danger"
            onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')" title="Đăng xuất">
            <i class="bi bi-box-arrow-right"></i>
          </button>
        </form>
      </div>
    </div>

    <!-- Lọc theo danh mục -->
    <!-- @if(auth()->check() && auth()->user()->isAdmin())
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex gap-2 flex-wrap">
              <a href="{{ route('exams.index') }}"
                class="btn btn-sm {{ request()->is('/') ? 'btn-primary' : 'btn-outline-primary' }}">
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
      @endif -->

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
                  <i class="bi bi-play-circle"></i> Thi thử
                </a>
                <a href="{{ route('exams.show', $exam->id) }}" class="btn btn-outline-primary">
                  <i class="bi bi-eye"></i> Ôn tập
                </a>
                @if(auth()->check() && auth()->user()->canManageContent())
                  <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-pencil"></i>
                  </a>
                @endif
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
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .bg-gradient-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
  </style>
@endsection