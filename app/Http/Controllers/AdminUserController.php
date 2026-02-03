<?php

namespace App\Http\Controllers;

use App\Models\UserExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        // Allow Admins and Teachers to view
        if (!auth()->user()->canManageContent()) {
            abort(403);
        }

        $users = UserExam::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Chỉ Admin mới có quyền tạo tài khoản.');
        }
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Chỉ Admin mới có quyền tạo tài khoản.');
        }

        $validated = $request->validate([
            'student_code' => 'required|string|max:50|unique:users',
            'full_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:student,teacher,admin',
            'category' => 'nullable|string|max:20',
        ]);

        if ($validated['role'] == 'student' && empty($validated['category'])) {
            return back()->withErrors(['category' => 'Hạng thi là bắt buộc đối với học viên.'])->withInput();
        }

        // Handle NOT NULL constraint for non-students
        if ($validated['role'] !== 'student' && empty($validated['category'])) {
            $validated['category'] = '';
        }

        $validated['password'] = Hash::make($validated['password']);

        UserExam::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Tạo tài khoản thành công!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền xóa tài khoản.');
        }

        $user = UserExam::findOrFail($id);

        // Prevent deleting self or other admins (though query filters students, extra safety)
        if ($user->isAdmin()) {
            return back()->with('error', 'Không thể xóa tài khoản Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Xóa tài khoản thành công!');
    }
    public function resetPassword(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền đổi mật khẩu người khác.');
        }

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = UserExam::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->with('error', 'Không thể đổi mật khẩu tài khoản Admin tại đây.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công cho học viên: ' . $user->full_name);
    }
}
