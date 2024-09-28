<?php

namespace App\Services;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerificationRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function registerUser(RegisterRequest $registerRequest);
    public function loginUser(LoginRequest $loginRequest);
    public function getAllUsers(): Collection;
    public function getUserById(int $id): ?User;
    public function createUser(array $data): User;
    public function updateUser(int $id, array $data): bool;
    public function deleteUser(int $id): bool;
    public function getUserByEmail(string $email): ?User;
    public function getAuthenticatedUser();
    public function logout();
    public function forgotPassword(ForgotPasswordRequest $forgotPasswordRequest);
    public function resetPassword(ResetPasswordRequest $resetPasswordRequest);

    public function checkResetToken($token, $email);
    public function verifyCode(VerificationRequest $verificationRequest);
}
