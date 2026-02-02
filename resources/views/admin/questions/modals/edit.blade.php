@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Chỉnh sửa câu hỏi #{{ $question->id }}</h4>
                        <a href="{{ route('questions.index', $question->exam_id) }}" class="btn btn-light btn-sm">
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

                    <form action="{{ route('questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nội dung câu hỏi -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nội dung câu hỏi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      name="content" rows="5" required>{{ old('content', $question->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Có thể dùng HTML để định dạng</small>
                        </div>

                        <!-- Hình ảnh câu hỏi -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Hình ảnh câu hỏi (tùy chọn)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   name="image" accept="image/*" id="questionImage">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if($question->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $question->image) }}" 
                                     alt="Hình câu hỏi" class="img-thumbnail" style="max-height: 200px;">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" name="remove_image" id="removeImage">
                                    <label class="form-check-label" for="removeImage">Xóa hình ảnh này</label>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mt-2" id="imagePreview"></div>
                        </div>

                        <!-- 4 Đáp án -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Các đáp án <span class="text-danger">*</span></label>
                            
                            @foreach(['A', 'B', 'C', 'D'] as $index => $letter)
                            @php
                                $option = $question->options[$index] ?? null;
                            @endphp
                            <div class="card mb-3 border-{{ $option && $option->is_correct ? 'success' : 'secondary' }}">
                                <div class="card-header bg-{{ $option && $option->is_correct ? 'success' : 'light' }} bg-opacity-10">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" 
                                               name="correct_answer" value="{{ $index }}" 
                                               id="correct_{{ $index }}"
                                               {{ $option && $option->is_correct ? 'checked' : '' }} required>
                                        <label class="form-check-label fw-bold" for="correct_{{ $index }}">
                                            Đáp án {{ $letter }} (Click để đánh dấu đúng)
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <textarea class="form-control mb-2" 
                                              name="options[{{ $index }}][content]" 
                                              rows="2" required 
                                              placeholder="Nội dung đáp án {{ $letter }}">{{ old("options.$index.content", $option->content ?? '') }}</textarea>
                                    
                                    <input type="file" class="form-control" 
                                           name="options[{{ $index }}][image]" 
                                           accept="image/*"
                                           onchange="previewOptionImage(this, {{ $index }})">
                                    <small class="text-muted">Hình ảnh cho đáp án {{ $letter }} (tùy chọn)</small>
                                    
                                    @if($option && $option->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $option->image) }}" 
                                             alt="Hình đáp án {{ $letter }}" 
                                             class="img-thumbnail" style="max-height: 150px;">
                                        <div class="form-check mt-1">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="options[{{ $index }}][remove_image]" 
                                                   id="removeOptionImage{{ $index }}">
                                            <label class="form-check-label" for="removeOptionImage{{ $index }}">
                                                Xóa hình này
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="mt-2" id="optionPreview{{ $index }}"></div>
                                    
                                    <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id ?? '' }}">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <!-- Phần/Chương -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phần/Chương</label>
                                <input type="text" class="form-control" name="section" 
                                       value="{{ old('section', $question->section) }}"
                                       placeholder="VD: Phần 1, Chương 2">
                            </div>

                            <!-- Độ khó -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Độ khó</label>
                                <select class="form-select" name="level">
                                    <option value="1" {{ $question->level == '1' ? 'selected' : '' }}>Độ 1 (Dễ nhất)</option>
                                    <option value="2" {{ $question->level == '2' ? 'selected' : '' }}>Độ 2</option>
                                    <option value="3" {{ $question->level == '3' ? 'selected' : '' }}>Độ 3</option>
                                    <option value="4" {{ $question->level == '4' ? 'selected' : '' }}>Độ 4</option>
                                    <option value="5" {{ $question->level == '5' ? 'selected' : '' }}>Độ 5 (Khó nhất)</option>
                                </select>
                            </div>

                            <!-- Danh mục -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Danh mục</label>
                                <input type="text" class="form-control" name="category" 
                                       value="{{ old('category', $question->category) }}"
                                       placeholder="VD: Biển báo">
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Lưu thay đổi
                                </button>
                                <a href="{{ route('exams.show', $question->exam_id) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Hủy
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex justify-content-end mt-n5" style="margin-top: -38px;">
                         <form action="{{ route('questions.destroy', $question->id) }}" method="POST" 
                               onsubmit="return confirm('Xóa câu hỏi này?')" class="d-inline">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="btn btn-danger">
                                 <i class="bi bi-trash"></i> Xóa câu hỏi
                             </button>
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview hình ảnh câu hỏi
document.getElementById('questionImage')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').innerHTML = 
                `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">`;
        }
        reader.readAsDataURL(file);
    }
});

// Preview hình ảnh đáp án
function previewOptionImage(input, index) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('optionPreview' + index).innerHTML = 
                `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px;">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Đổi màu card khi chọn đáp án đúng
document.querySelectorAll('input[name="correct_answer"]').forEach(radio => {
    radio.addEventListener('change', function() {
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