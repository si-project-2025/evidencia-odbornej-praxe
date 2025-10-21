<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetPasswordRequest;
use App\Services\PasswordService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\User;

class ResetPasswordController extends Controller
{
    protected PasswordService $passwordService;

    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }
    public function reset(SetPasswordRequest $request, PasswordService $passwordService)
    {
        try {
            $passwordService->resetPassword($request->email, $request->token, $request->password);
            return response()->json(['message' => 'Heslo bolo úspešne zmenené.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
