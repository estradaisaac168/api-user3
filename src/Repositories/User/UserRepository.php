<?php


namespace App\Repositories\User;

use App\Models\User;

class UserRepository
{

    public function existEmail(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function verifyToken(string $token): ?User
    {
        return User::where("verification_token", $token)
            ->where("verification_expires", ">", date('Y-m-d H:i:s'))
            ->first();
    }

    public function updateVerification(User $user): bool
    {
        return $user->update([
            'is_verified' => 1,
            'verification_token' => null,
            'verification_expires' => null,
        ]);
    }
}
