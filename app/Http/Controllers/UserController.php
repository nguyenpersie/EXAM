<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function ViewLogin(): View
    {
        return view ('admin.login');
    }

    public function login(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'student_code' => 'required|string|max:50',
            'password'     => 'required|string|min:3',
        ]);

        $credentials = $request->only('student_code', 'password');
        $remember = $request->filled('remember');

        // Thử đăng nhập bằng student_code thay vì email
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Phân quyền chuyển hướng
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.questions.index'))
                    ->with('success', 'Đăng nhập thành công! Chào mừng Admin.');
            }

            // Học viên (student)
            return redirect()->intended(route('pages.test'))
                ->with('success', 'Đăng nhập thành công! Chọn hạng thi của bạn.');
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
}
