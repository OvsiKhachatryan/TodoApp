<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerificationRequest;
use App\Mail\VerificationCodeMail;
use App\Models\EmailVerification;
use App\Services\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $registerRequest)
    {
        $response = $this->userService->registerUser($registerRequest);

        return response()->json($response);
    }

    public function login(LoginRequest $loginRequest)
    {
        $response = $this->userService->loginUser($loginRequest);

        return response()->json($response, $response['status']); // Return the response with the status code
    }
    public function getUser(Request $request): JsonResponse
    {
        $user = $this->userService->getAuthenticatedUser();

        return response()->json($user);
    }

    public function logout(): JsonResponse
    {
        $this->userService->logout();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function forgotPassword(ForgotPasswordRequest $forgotPasswordRequest)
    {
        $response = $this->userService->forgotPassword($forgotPasswordRequest);

        return response()->json($response);
    }

    public function resetPassword(ResetPasswordRequest $resetPasswordRequest)
    {
        $response = $this->userService->resetPassword($resetPasswordRequest);

        return response()->json($response);
    }

    public function checkExpire(Request $request)
    {
        $data = $this->userService->checkResetToken($request->input('token'), $request->input('email'));

        return redirect("http://localhost:5173/reset-password?token={$data['token']}&email={$data['email']}");
    }


    public function verifyCode(VerificationRequest $verificationRequest)
    {
        return $this->userService->verifyCode($verificationRequest);
    }
}
