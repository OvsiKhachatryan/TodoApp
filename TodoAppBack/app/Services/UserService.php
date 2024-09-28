<?php

namespace App\Services;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\VerificationCodeMail;
use App\Models\EmailVerification;
use App\Models\User;
use App\Notifications\CustomResetPassword;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(RegisterRequest $registerRequest)
    {
        $data = $registerRequest->validated();
        $data['password'] = bcrypt($data['password']);

        $user = $this->userRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        $verificationCode = mt_rand(100000, 999999);
        EmailVerification::create([
            'user_id' => $user->id,
            'verification_code' => $verificationCode
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function loginUser(LoginRequest $loginRequest)
    {
        $data = $loginRequest->validated();
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return [
                'status' => 401,
                'message' => 'Invalid credentials',
            ];
        }

        if (!$user->email_verified_at) {
            return [
                'status' => 403,
                'message' => 'Please verify your email. We sent a verification link.',
                'redirect_url' => 'http://localhost:5173/verify-email',
            ];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function getAllUsers(): Collection
    {
        return $this->userRepository->all();
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function getAuthenticatedUser()
    {
        return $this->userRepository->getAuthenticatedUser();
    }

    public function logout()
    {
        $this->userRepository->logout();
    }

    public function forgotPassword(ForgotPasswordRequest $forgotPasswordRequest)
    {
        // Find the user by email
        $user = $this->userRepository->findByEmail($forgotPasswordRequest->email);

        if (!$user) {
            return ['message' => 'User not found', 'status' => 404];
        }

        $status = Password::sendResetLink(
            $forgotPasswordRequest->only('email'),
            function ($user, $token) {
                $user->notify(new CustomResetPassword($token));
            }
        );

        if ($status === Password::RESET_LINK_SENT) {
            return ['message' => 'Password reset link sent', 'status' => 200];
        }

        return ['message' => 'Unable to send reset link', 'status' => 500];
    }

    public function resetPassword(ResetPasswordRequest $resetPasswordRequest)
    {
        $response = Password::reset(
            $resetPasswordRequest->only('email', 'token', 'password', 'password_confirmation'),
            function ($user, $password) {
                $this->userRepository->updatePassword($user, $password);
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been reset successfully'], 200);
        }

        if ($response == Password::INVALID_TOKEN) {
            return response()->json(['message' => 'Token is invalid or has expired'], 400);
        }

        return response()->json(['message' => 'An error occurred'], 500);
    }

    public function checkResetToken($token, $email)
    {
        return [
            'token' => $token,
            'email' => $email,
        ];
    }

    public function verifyCode($verificationRequest)
    {
        $verificationCode = $verificationRequest->verification_code;

        if (empty($verificationCode)) {
            return response()->json(['message' => 'The verification code field is required.'], 422);
        }

        $verification = $this->userRepository->findByCode($verificationCode);

        if ($verification) {
            $user = $this->userRepository->find($verification->user_id);

            if ($user) {
                $user->email_verified_at = now();
                $user->save();

                $this->userRepository->deleteVerification($verification);

                return response()->json(['message' => 'Email verified successfully.']);
            } else {
                return response()->json(['message' => 'User not found.'], 404);
            }
        }

        return response()->json(['message' => 'Invalid verification code.'], 422);
    }

}
