<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý câu hỏi - {{ $exam->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

    @include('partials.style')
</head>

<body>
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">Danh sách đề thi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('exams.show', $exam->id) }}">{{ $exam->title }}</a></li>
                <li class="breadcrumb-item active">Quản lý câu hỏi</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1"><i class="bi bi-list-check"></i> Quản lý câu hỏi</h1>
                <p class="text-muted mb-0">{{ $exam->title }} - Mã: {{ $exam->code }} | Tổng: {{ $questions->total() }}
                    câu</p>
            </div>
            <div>
                <a href="{{ route('questions.create', $exam->id) }}" class="btn btn-success me-2">
                    <i class="bi bi-plus-circle"></i> Thêm câu hỏi
                </a>
                <a href="{{ route('exams.show', $exam->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- Alerts -->
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

        <!-- Bộ lọc -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('questions.index', $exam->id) }}" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="category" class="form-select">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="level" class="form-select">
                            <option value="">Độ khó</option>
                            @for($i = 1; $i <= 3; $i++)
                                <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }}>
                                    Độ {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="section" class="form-select">
                            <option value="">Tất cả phần</option>
                            @foreach($sections as $sec)
                                <option value="{{ $sec }}" {{ request('section') == $sec ? 'selected' : '' }}>
                                    {{ $sec }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng câu hỏi -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Danh sách câu hỏi ({{ $questions->total() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th>Nội dung câu hỏi</th>
                            <th style="width: 100px;">Phần</th>
                            <th style="width: 80px;">Độ khó</th>
                            <th style="width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $index => $question)
                            <tr>
                                <td class="text-center">{{ $questions->firstItem() + $index }}</td>
                                <td style="width: 55%;">
                                    <div class="mb-1">
                                        @if($question->category)
                                            <span class="badge bg-info">{{ $question->category }}</span>
                                        @endif
                                        @if($question->image)
                                            <i class="bi bi-image text-primary"></i>
                                        @endif
                                    </div>
                                    <div>{{ Str::limit(strip_tags($question->content), 150) }}</div>

                                    <!-- Hiển thị đáp án nhỏ -->
                                    <div class="small text-muted mt-2">
                                        @foreach($question->options as $optIndex => $option)
                                            <span class="{{ $option->is_correct ? 'text-success fw-bold' : '' }}">
                                                {{ chr(65 + $optIndex) }}.{{ Str::limit($option->content, 30) }}
                                                @if($option->is_correct) ✓ @endif
                                            </span>
                                            @if(!$loop->last) | @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td style="width: 20%;">{{ $question->section ?? '-' }}</td>
                                <td style="width: 10%;">
                                    <span
                                        class="badge bg-{{ $question->level <= 2 ? 'success' : ($question->level <= 4 ? 'warning' : 'danger') }}">
                                        Độ {{ $question->level }}
                                    </span>
                                </td>
                                <td style="width: 10%;">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('questions.edit', $question->id) }}?{{ http_build_query(request()->only(['page', 'search', 'category', 'level', 'section'])) }}"
                                            class="btn btn-outline-primary" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Xóa câu hỏi này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Không có câu hỏi nào</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($questions->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $questions->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>