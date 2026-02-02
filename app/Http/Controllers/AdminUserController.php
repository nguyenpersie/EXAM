<?php

namespace App\Http\Controllers;

use App\Models\UserExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = UserExam::where('role', 'student')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_code' => 'required|string|max:50|unique:users',
            'full_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'category' => 'required|string|max:20',
        ]);

        $validated['role'] = 'student';
        $validated['password'] = Hash::make($validated['password']);

        UserExam::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Tạo tài khoản học viên thành công!');
    }

    public function destroy($id)
    {
        $user = UserExam::findOrFail($id);

        // Prevent deleting self or other admins (though query filters students, extra safety)
        if ($user->isAdmin()) {
            return back()->with('error', 'Không thể xóa tài khoản Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Xóa tài khoản thành công!');
    }
}
