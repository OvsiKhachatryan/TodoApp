<?php

namespace App\Repositories;

use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Model;

    public function create(array $data): Model;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
    public function findByEmail(string $email): ?Model;
    public function getAuthenticatedUser();
    public function logout();
    public function updatePassword($user, $password);
    public function findByCode($code);
    public function deleteVerification($verification);
}
