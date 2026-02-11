<?php

namespace App\Repositories;

use App\Models\UserExam;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    /**
     * Get paginated users
     */
    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return UserExam::orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): UserExam
    {
        return UserExam::findOrFail($id);
    }

    /**
     * Find user by student code
     */
    public function findByStudentCode(string $studentCode): ?UserExam
    {
        return UserExam::where('student_code', $studentCode)->first();
    }

    /**
     * Create new user
     */
    public function create(array $data): UserExam
    {
        return UserExam::create($data);
    }

    /**
     * Update user password
     */
    public function updatePassword(int $id, string $hashedPassword): UserExam
    {
        $user = $this->findById($id);
        $user->password = $hashedPassword;
        $user->save();

        return $user;
    }

    /**
     * Delete user
     */
    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        return $user->delete();
    }
}
