<?php

use App\Http\Controllers\Api\InternshipController;
use App\Http\Controllers\Api\ContactPersonController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InternshipVerificationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/set-password', [AuthController::class, 'setPassword']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
Route::post('/internships/verify', [InternshipVerificationController::class, 'verifyInternship']);

Route::get('/internships/get-verification-details',
    [InternshipVerificationController::class, 'getVerificationDetails']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user/internships', [InternshipController::class, 'getInternshipsByUser']);
    Route::get('/internships/companies', [InternshipController::class, 'getCompanies']);
    Route::get('/internships/garants', [InternshipController::class, 'getGarants']);
    Route::get('/internships/students', [InternshipController::class, 'getStudents']);

    Route::apiResource('internships', InternshipController::class);
    Route::apiResource('internships.contact-persons', ContactPersonController::class)
        ->parameters(['contact-persons' => 'contactPerson']);

    Route::post('/internships/{internship}/send-verification',
        [InternshipVerificationController::class, 'sendVerificationEmail']);
});
