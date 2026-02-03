<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function ViewLogin(): View
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'student_code' => 'required|string|max:50',
            'password' => 'required|string|min:3',
        ]);

        $credentials = $request->only('student_code', 'password');
        $remember = $request->filled('remember');

        // Thử đăng nhập bằng student_code thay vì email
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Phân quyền chuyển hướng
            return redirect()->intended(route('home'))
                ->with('success', 'Đăng nhập thành công!');
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'student_code' => 'Mã học viên hoặc mật khẩu không đúng.',
        ])->onlyInput('student_code');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đã đăng xuất thành công.');
    }
    public function showChangePasswordForm(): View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
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

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
