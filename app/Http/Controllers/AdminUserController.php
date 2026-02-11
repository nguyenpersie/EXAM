<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminUserController extends Controller
{
    protected UserService $userService;
    protected AuthService $authService;

    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * Hiển thị danh sách users
     */
    public function index(): View|RedirectResponse
    {
        if (!$this->authService->canManageContent(auth()->user())) {
            abort(403);
        }

        $users = $this->userService->getPaginatedUsers(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form tạo user
     */
    public function create(): View|RedirectResponse
    {
        if (!$this->authService->isAdmin(auth()->user())) {
            abort(403, 'Chỉ Admin mới có quyền tạo tài khoản.');
        }

        return view('admin.users.create');
    }

    /**
     * Lưu user mới
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$this->authService->isAdmin(auth()->user())) {
            abort(403, 'Chỉ Admin mới có quyền tạo tài khoản.');
        }

        $validated = $request->validate([
            'student_code' => 'required|string|max:50|unique:users',
            'full_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:student,teacher,center,admin',
            'category' => 'nullable|string|max:20',
        ]);

        // Validate category for students
        if (!$this->userService->validateStudentCategory($validated['role'], $validated['category'] ?? null)) {
            return back()
                ->withErrors(['category' => 'Hạng thi là bắt buộc đối với học viên.'])
                ->withInput();
        }

        try {
            $this->userService->createUser($validated);
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Tạo tài khoản thành công!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Xóa user
     */
    public function destroy(int $id): RedirectResponse
    {
        if (!$this->authService->isAdmin(auth()->user())) {
            abort(403, 'Bạn không có quyền xóa tài khoản.');
        }

        try {
            $this->userService->deleteUser($id);
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Xóa tài khoản thành công!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reset mật khẩu user
     */
    public function resetPassword(Request $request, int $id): RedirectResponse
    {
        if (!$this->authService->isAdmin(auth()->user())) {
            abort(403, 'Bạn không có quyền đổi mật khẩu người khác.');
        }

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = $this->userService->resetPassword($id, $request->password);
            return back()->with('success', 'Đổi mật khẩu thành công cho: ' . $user->full_name);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
