<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteCompanyRegistrationRequest;
use App\Http\Requests\RegisterCompanyEmailRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\SetPasswordRequest;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected UserService $userService;
    protected AuthService $authService;

    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function setPassword(SetPasswordRequest $request): JsonResponse
    {
        try {
            $this->userService->setPassword($request->validated());
            return response()->json(['message' => 'Heslo bolo úspešne nastavené. Môžete sa prihlásiť.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Nastavenie hesla zlyhalo.', 'error' => $e->getMessage(),], 500);
        }
    }

    public function register(RegisterUserRequest $request, UserService $userService): JsonResponse
    {
        try {
            $user = $userService->register($request->validated());
            return response()->json(['message' => 'Registrácia úspešná.', 'email' => $user->email,]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Registrácia zlyhala.', 'error' => $e->getMessage(),], 500);
        }
    }

    public function registerCompanyEmail(RegisterCompanyEmailRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->registerCompanyEmail($request->validated());
            return response()->json([
                'message' => 'Registrácia úspešná. Na email bol odoslaný odkaz na nastavenie hesla.',
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registrácia zlyhala.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function completeCompanyRegistration(CompleteCompanyRegistrationRequest $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user->role->name !== 'firma') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            if ($user->company()->exists()) {
                return response()->json(['message' => 'Firma už bola priradená k tomuto účtu.'], 400);
            }

            $user = $this->userService->completeCompanyRegistration($user, $request->validated());

            return response()->json([
                'message' => 'Registrácia firmy úspešne dokončená.',
                'user' => $user->load(['company.address', 'role']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Dokončenie registrácie zlyhalo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request, AuthService $authService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return $authService->login($request->only('email', 'password'));
    }

    public function logout(Request $request, AuthService $authService): JsonResponse
    {
        return $authService->logout($request->user());
    }
}
