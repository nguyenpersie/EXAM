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
                                <label for="role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required onchange="toggleCategory()">
                                    <option value="student" selected>Học viên</option>
                                    <option value="teacher">Giáo viên</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div class="mb-3" id="category-group">
                                <label for="category" class="form-label">Hạng thi <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category"
                                    name="category">
                                    <option value="">-- Chọn hạng thi --</option>
                                    <option value="LPT" {{ old('category') == 'LPT' ? 'selected' : '' }}>LPT</option>
                                    <option value="TT" {{ old('category') == 'TT' ? 'selected' : '' }}>TT</option>
                                    <option value="TM" {{ old('category') == 'TM' ? 'selected' : '' }}>TM</option>
                                    <option value="ĐKCT" {{ old('category') == 'ĐKCT' ? 'selected' : '' }}>ĐKCT</option>
                                    <option value="ATVB" {{ old('category') == 'ATVB' ? 'selected' : '' }}>ATVB</option>
                                    <option value="ATXD" {{ old('category') == 'ATXD' ? 'selected' : '' }}>ATXD</option>
                                    <option value="T4" {{ old('category') == 'T4' ? 'selected' : '' }}>T4</option>
                                    <option value="T3" {{ old('category') == 'T3' ? 'selected' : '' }}>T3</option>
                                    <option value="T2" {{ old('category') == 'T2' ? 'selected' : '' }}>T2</option>
                                    <option value="T1" {{ old('category') == 'T1' ? 'selected' : '' }}>T1</option>
                                    <option value="M3" {{ old('category') == 'M3' ? 'selected' : '' }}>M3</option>
                                    <option value="M2" {{ old('category') == 'M2' ? 'selected' : '' }}>M2</option>
                                    <option value="M1" {{ old('category') == 'M1' ? 'selected' : '' }}>M1</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Chỉ bắt buộc đối với Học viên.</div>
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
                                    <i class="bi bi-save"></i> Tạo tài khoản
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCategory() {
            var role = document.getElementById('role').value;
            var categoryGroup = document.getElementById('category-group');
            var categorySelect = document.getElementById('category');
            
            if (role === 'student') {
                categoryGroup.style.display = 'block';
                categorySelect.setAttribute('required', 'required');
            } else {
                categoryGroup.style.display = 'none';
                categorySelect.removeAttribute('required');
                categorySelect.value = '';
            }
        }
        
        // Run on load
        document.addEventListener('DOMContentLoaded', function() {
            toggleCategory();
        });
    </script>
@endsection