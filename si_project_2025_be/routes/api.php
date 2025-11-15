<?php

use App\Http\Controllers\Api\InternshipController;
use App\Http\Controllers\Api\ContactPersonController;
use App\Http\Controllers\Api\InternshipDocumentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InternshipVerificationController;

// ----------------------------
// Public routes
// ----------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/set-password', [AuthController::class, 'setPassword']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

Route::post('/internships/verify', [InternshipVerificationController::class, 'verifyInternship']);
Route::get('/internships/get-verification-details',
    [InternshipVerificationController::class, 'getVerificationDetails']
);

// ----------------------------
// Authenticated routes
// ----------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // User-specific and helper endpoints
    Route::get('/user/internships', [InternshipController::class, 'getInternshipsByUser']);
    Route::get('/internships/companies', [InternshipController::class, 'getCompanies']);
    Route::get('/internships/garants', [InternshipController::class, 'getGarants']);
    Route::get('/internships/students', [InternshipController::class, 'getStudents']);

    // CRUD
    Route::apiResource('internships', InternshipController::class);
    Route::apiResource('internships.contact-persons', ContactPersonController::class)
        ->parameters(['contact-persons' => 'contactPerson']);

    // Internship verification
    Route::post('/internships/{internship}/send-verification',
        [InternshipVerificationController::class, 'sendVerificationEmail']);

    Route::prefix('internships/{id}')->group(function () {
        Route::get('/documents', [InternshipDocumentController::class, 'index']);
        Route::post('/documents', [InternshipDocumentController::class, 'store']);
        Route::delete('/documents/{documentId}', [InternshipDocumentController::class, 'destroy']);
        Route::get('/contract', [InternshipDocumentController::class, 'generateContractPdf']);
    });
});
