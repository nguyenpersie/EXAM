@extends('layouts.master')

@section('title', 'Chỉnh sửa đề thi')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Chỉnh sửa đề thi</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('exams.update', $exam->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Mã đề thi -->
                        <div class="mb-3">
                            <label for="code" class="form-label">Mã đề thi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $exam->code) }}" 
                                   placeholder="VD: LPT01, TM01, TT01..." required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mã duy nhất để phân biệt các đề thi</small>
                        </div>

                        <!-- Tên đề thi -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên đề thi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $exam->title) }}" 
                                   placeholder="VD: Lý thuyết lái xe hạng A1" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Danh mục -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Danh mục/Hạng mục</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                   id="category" name="category" value="{{ old('category', $exam->category) }}" 
                                   placeholder="VD: Lái xe, Toán học, Tin học...">
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Để phân loại đề thi</small>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Mô tả ngắn về đề thi...">{{ old('description', $exam->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Thời gian làm bài -->
                            <div class="col-md-4 mb-3">
                                <label for="duration_minutes" class="form-label">Thời gian (phút) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" 
                                       id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" 
                                       min="1" required>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tổng điểm -->
                            <div class="col-md-4 mb-3">
                                <label for="total_score" class="form-label">Tổng điểm <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('total_score') is-invalid @enderror" 
                                       id="total_score" name="total_score" value="{{ old('total_score', $exam->total_score) }}" 
                                       min="0" step="0.01" required>
                                @error('total_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Điểm đạt -->
                            <div class="col-md-4 mb-3">
                                <label for="passing_score" class="form-label">Điểm đạt <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                                       id="passing_score" name="passing_score" value="{{ old('passing_score', $exam->passing_score) }}" 
                                       min="0" step="0.01" required>
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Trạng thái -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', $exam->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Kích hoạt đề thi
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Cập nhật đề thi
                            </button>
                            <a href="{{ route('exams.show', $exam->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
