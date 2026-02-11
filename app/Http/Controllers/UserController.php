<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    protected AuthService $authService;
    protected UserService $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    /**
     * Hiển thị trang login
     */
    public function ViewLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'student_code' => 'required|string|max:50',
            'password' => 'required|string|min:3',
        ]);

        $credentials = $request->only('student_code', 'password');
        $remember = $request->filled('remember');

        if ($this->authService->attempt($credentials, $remember)) {
            $this->authService->regenerateSession($request);

            return redirect()
                ->intended(route('home'))
                ->with('success', 'Đăng nhập thành công!');
        }

        return back()
            ->withErrors(['student_code' => 'Mã học viên hoặc mật khẩu không đúng.'])
            ->onlyInput('student_code');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout($request);

        return redirect('/')
            ->with('success', 'Đã đăng xuất thành công.');
    }

    /**
     * Hiển thị form đổi mật khẩu
     */
    public function showChangePasswordForm(): View
    {
        if (!$this->authService->isAdmin(auth()->user())) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        return view('auth.change-password');
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request): RedirectResponse
    {
        if (!$this->authService->isAdmin(auth()->user())) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed|different:current_password',
        ], [
            'new_password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'new_password.different' => 'Mật khẩu mới không được trùng với mật khẩu cũ.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.'
        ]);

        try {
            $this->userService->changePassword(
                auth()->id(),
                $request->current_password,
                $request->new_password
            );

            return back()->with('success', 'Đổi mật khẩu thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['current_password' => $e->getMessage()]);
        }
    }
}
