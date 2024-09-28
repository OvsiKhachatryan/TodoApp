<?php

namespace App\Repositories;

use App\Http\Requests\ForgotPasswordRequest;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = $this->find($id);
        return $user ? $user->update($data) : false;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function findByEmail(string $email): ?Model
    {
        return $this->model->where('email', $email)->first();
    }

    public function getAuthenticatedUser()
    {
        return auth()->user();
    }

    public function logout()
    {
        return auth()->user()->currentAccessToken()->delete();
    }

    public function updatePassword($user, $password)
    {
        $user->forceFill([
            'password' => Hash::make($password),
        ])->save();
    }

    public function findByCode($code)
    {
        return EmailVerification::where('verification_code', $code)->first();
    }

    public function deleteVerification($verification)
    {
        $verification->delete();
    }
}
