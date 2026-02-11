@extends('layouts.master')

@section('title', 'Quản lý học viên')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-people"></i> Quản lý học viên</h2>
            </div>
            <div>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-house"></i> Trang chủ
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Thêm người dùng
                    </a>
                @endif
            </div>
        </div>


        @include('partials.alerts')


        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Mã người dùng</th>
                                <th>Họ và tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Hạng thi</th>
                                <th>Ngày tạo</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td><strong>{{ $user->student_code }}</strong></td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email ?? '-' }}</td>
                                    <td>
                                        @if($user->isAdmin())
                                            <span class="badge bg-danger">Admin</span>
                                        @elseif($user->isTeacher())
                                            <span class="badge bg-warning text-dark">Giáo viên</span>
                                        @elseif($user->role === 'center')
                                            <span class="badge bg-success">Trung tâm</span>
                                        @else
                                            <span class="badge bg-secondary">Học viên</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info">{{ $user->category }}</span></td>
                                    <td>{{ optional($user->created_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td class="text-end">
                                        @if(auth()->user()->isAdmin())
                                            <button type="button" class="btn btn-sm btn-outline-warning me-1"
                                                onclick="openResetModal('{{ $user->id }}', '{{ $user->full_name }}')">
                                                <i class="bi bi-key"></i> Đổi MK
                                            </button>

                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i> Xóa
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">Chưa có học viên nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="resetPasswordForm" method="POST" action="">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Đổi mật khẩu cho: <span id="modalUserName" class="fw-bold"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Mật khẩu mới</label>
                            <input type="text" class="form-control" id="newPassword" name="password" required minlength="6"
                                placeholder="Nhập mật khẩu mới...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openResetModal(userId, userName) {
            // Set action url
            let form = document.getElementById('resetPasswordForm');
            form.action = "{{ route('admin.users.index') }}/" + userId + "/reset-password";

            // Set user name
            document.getElementById('modalUserName').innerText = userName;

            // Clear input
            document.getElementById('newPassword').value = '';

            // Show modal
            var myModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
            myModal.show();
        }
    </script>
@endsection