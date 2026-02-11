<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Attempt to authenticate user
     */
    public function attempt(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Get authenticated user
     */
    public function user(): ?\App\Models\UserExam
    {
        return Auth::user();
    }

    /**
     * Logout user
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    /**
     * Regenerate session
     */
    public function regenerateSession(Request $request): void
    {
        $request->session()->regenerate();
    }

    /**
     * Check if user can manage content
     */
    public function canManageContent($user): bool
    {
        return $user && $user->canManageContent();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin($user): bool
    {
        return $user && $user->isAdmin();
    }
}
