<?php

namespace App\Services;

use App\Models\UserExam;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get paginated users
     */
    public function getPaginatedUsers(int $perPage = 10): object
    {
        return $this->userRepository->getPaginated($perPage);
    }

    /**
     * Create new user
     */
    public function createUser(array $data): UserExam
    {
        // Handle category for non-students
        if ($data['role'] !== 'student' && empty($data['category'])) {
            $data['category'] = '';
        }

        // Hash password
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    /**
     * Delete user
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->findById($id);

        // Prevent deleting admin
        if ($user->isAdmin()) {
            throw new \Exception('Không thể xóa tài khoản Admin.');
        }

        return $this->userRepository->delete($id);
    }

    /**
     * Reset user password
     */
    public function resetPassword(int $id, string $newPassword): UserExam
    {
        $user = $this->userRepository->findById($id);

        // Prevent resetting admin password here
        if ($user->isAdmin()) {
            throw new \Exception('Không thể đổi mật khẩu tài khoản Admin tại đây.');
        }

        $hashedPassword = Hash::make($newPassword);
        return $this->userRepository->updatePassword($id, $hashedPassword);
    }

    /**
     * Change user's own password
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): UserExam
    {
        $user = $this->userRepository->findById($userId);

        // Verify current password
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \Exception('Mật khẩu hiện tại không đúng.');
        }

        $hashedPassword = Hash::make($newPassword);
        return $this->userRepository->updatePassword($userId, $hashedPassword);
    }

    /**
     * Validate student category requirement
     */
    public function validateStudentCategory(string $role, ?string $category): bool
    {
        return $role !== 'student' || !empty($category);
    }
}
