<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordService;
use Illuminate\Http\Request;


class ForgotPasswordController extends Controller
{
    protected PasswordService $passwordService;

    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    public function sendResetLinkEmail(Request $request, PasswordService $passwordService)
    {
        $request->validate(['email' => 'required|email']);

        $sent = $passwordService->sendResetLink($request->email);

        if (!$sent) {
            return response()->json(['message' => 'Používateľ neexistuje alebo e-mail nebolo možné odoslať.'], 500);
        }

        return response()->json(['message' => 'Odkaz na reset hesla bol odoslaný na váš email.']);
    }
}
