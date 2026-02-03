@extends('layouts.master')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Đổi mật khẩu</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('change-password.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control" id="current_password" name="current_password"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control" id="new_password_confirmation"
                                    name="new_password_confirmation" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('home') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection