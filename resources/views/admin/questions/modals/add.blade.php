@extends('layouts.master')

@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card shadow">
          <div class="card-header bg-success text-white">
            <div class="d-flex justify-content-between align-items-center">
              <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Thêm câu hỏi mới</h4>
              <a href="{{ route('questions.index', $examId) }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
              </a>
            </div>
          </div>
          <div class="card-body">
            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('questions.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="exam_id" value="{{ $examId }}">

              <!-- Nội dung câu hỏi -->
              <div class="mb-4">
                <label class="form-label fw-bold">Nội dung câu hỏi <span class="text-danger">*</span></label>
                <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="5"
                  required>{{ old('content') }}</textarea>
                @error('content')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Có thể dùng HTML để định dạng</small>
              </div>

              <!-- Hình ảnh câu hỏi -->
              <div class="mb-4">
                <label class="form-label fw-bold">Hình ảnh câu hỏi (tùy chọn)</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*"
                  id="questionImage">
                @error('image')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="mt-2" id="imagePreview"></div>
              </div>

              <!-- 4 Đáp án -->
              <div class="mb-4">
                <label class="form-label fw-bold">Các đáp án <span class="text-danger">*</span></label>

                @foreach(['A', 'B', 'C', 'D'] as $index => $letter)
                  <div class="card mb-3 border-secondary">
                    <div class="card-header bg-light bg-opacity-10">
                      <div class="form-check">
                        <input type="radio" class="form-check-input" name="correct_answer" value="{{ $index }}"
                          id="correct_{{ $index }}" {{ old('correct_answer') == $index ? 'checked' : '' }} required>
                        <label class="form-check-label fw-bold" for="correct_{{ $index }}">
                          Đáp án {{ $letter }} (Click để đánh dấu đúng)
                        </label>
                      </div>
                    </div>
                    <div class="card-body">
                      <textarea class="form-control mb-2" name="options[{{ $index }}][content]" rows="2" required
                        placeholder="Nội dung đáp án {{ $letter }}">{{ old("options.$index.content") }}</textarea>
                    </div>
                  </div>
                @endforeach
              </div>

              <div class="row">
                <!-- Phần/Chương -->
                <div class="col-md-4 mb-3">
                  <label class="form-label">Phần/Chương</label>
                  <input type="text" class="form-control" name="section" value="{{ old('section') }}"
                    placeholder="VD: Phần 1, Chương 2" required>
                </div>

                <!-- Độ khó -->
                <div class="col-md-4 mb-3">
                  <label class="form-label">Độ khó</label>
                  <select class="form-select" name="level">
                    <option value="1" {{ old('level') == '1' ? 'selected' : '' }}>Độ 1 (Dễ nhất)</option>
                    <option value="2" {{ old('level') == '2' ? 'selected' : '' }}>Độ 2</option>
                    <option value="3" {{ old('level') == '3' ? 'selected' : '' }}>Độ 3</option>
                    <option value="4" {{ old('level') == '4' ? 'selected' : '' }}>Độ 4</option>
                    <option value="5" {{ old('level') == '5' ? 'selected' : '' }}>Độ 5 (Khó nhất)</option>
                  </select>
                </div>

                <!-- Danh mục -->
                <div class="col-md-4 mb-3">
                  <label class="form-label">Danh mục</label>
                  <input type="text" class="form-control" name="category" value="{{ old('category') }}"
                    placeholder="VD: Biển báo">
                </div>
              </div>

              <!-- Buttons -->
              <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('questions.index', $examId) }}" class="btn btn-secondary">
                  <i class="bi bi-x-circle"></i> Hủy
                </a>
                <button type="submit" class="btn btn-success">
                  <i class="bi bi-save"></i> Lưu câu hỏi
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Preview hình ảnh câu hỏi
    document.getElementById('questionImage')?.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById('imagePreview').innerHTML =
            `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">`;
        }
        reader.readAsDataURL(file);
      }
    });

    // Đổi màu card khi chọn đáp án đúng
    document.querySelectorAll('input[name="correct_answer"]').forEach(radio => {
      radio.addEventListener('change', function () {
        document.querySelectorAll('.card').forEach(card => {
          if (card.querySelector('input[type="radio"]')) {
            card.classList.remove('border-success');
            card.classList.add('border-secondary');
            card.querySelector('.card-header').classList.remove('bg-success');
            card.querySelector('.card-header').classList.add('bg-light');
          }
        });

        const card = this.closest('.card');
        card.classList.remove('border-secondary');
        card.classList.add('border-success');
        card.querySelector('.card-header').classList.remove('bg-light');
        card.querySelector('.card-header').classList.add('bg-success');
      });
    });
  </script>
@endsection