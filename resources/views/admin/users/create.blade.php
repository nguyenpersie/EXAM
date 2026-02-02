@extends('layouts.master')

@section('title', 'Thêm học viên mới')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-person-plus"></i> Thêm học viên mới</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="student_code" class="form-label">Mã học viên (SBD) <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('student_code') is-invalid @enderror"
                                    id="student_code" name="student_code" value="{{ old('student_code') }}" required>
                                @error('student_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ và tên <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                    id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Hạng thi <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category"
                                    name="category" required>
                                    <option value="">-- Chọn hạng thi --</option>
                                    <option value="A1" {{ old('category') == 'A1' ? 'selected' : '' }}>A1</option>
                                    <option value="A2" {{ old('category') == 'A2' ? 'selected' : '' }}>A2</option>
                                    <option value="B1" {{ old('category') == 'B1' ? 'selected' : '' }}>B1</option>
                                    <option value="B2" {{ old('category') == 'B2' ? 'selected' : '' }}>B2</option>
                                    <option value="C" {{ old('category') == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ old('category') == 'D' ? 'selected' : '' }}>D</option>
                                    <option value="E" {{ old('category') == 'E' ? 'selected' : '' }}>E</option>
                                    <option value="F" {{ old('category') == 'F' ? 'selected' : '' }}>F</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email (Tùy chọn)</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" value="123456" required>
                                <small class="text-muted">Mặc định: 123456</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Lưu học viên
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection