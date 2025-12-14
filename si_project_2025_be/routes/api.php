<?php

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\InternshipController;
use App\Http\Controllers\Api\ContactPersonController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InternshipVerificationController;
use App\Models\Status;
use App\Http\Controllers\Api\ExternalInternshipController;

// ----------------------------
// Public routes
// ----------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/set-password', [AuthController::class, 'setPassword']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

Route::prefix('public/internships')->group(function () {
    Route::post('/action', [InternshipVerificationController::class, 'handleInternshipAction']);
    Route::get('/get-verification-details', [InternshipVerificationController::class, 'getVerificationDetails']);
    Route::post('{id}/documents', [DocumentController::class, 'store']);
    Route::get('/{id}/documents/{documentId}/download', [DocumentController::class, 'download']);
    Route::patch('/{id}/documents/{documentId}/verify', [DocumentController::class, 'verifyDocument']);
});

// ----------------------------
// External system routes
// ----------------------------
Route::prefix('external')
    ->middleware(['client:internship:defend'])
    ->group(function () {
        Route::get('/internships',
            [ExternalInternshipController::class, 'index']
        )->name('api.external.internships.index');

        Route::patch('/internships/{internship}/defend',
            [ExternalInternshipController::class, 'defend']
        )->name('api.external.internships.defend');
    });

// ----------------------------
// Authenticated routes
// ----------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // User-specific and helper endpoints
    Route::apiResource('internships', InternshipController::class);
    Route::get('/internships/companies', [InternshipController::class, 'getCompanies']);
    Route::get('/internships/garants', [InternshipController::class, 'getGarants']);
    Route::get('/internships/students', [InternshipController::class, 'getStudents']);
    Route::get('/statuses', function () { return Status::select( 'type')->get(); });
    Route::get('/user/internships', [InternshipController::class, 'getInternshipsByUser']);

    Route::get('contact-persons', [ContactPersonController::class, 'index']);
    Route::post('contact-persons', [ContactPersonController::class, 'store']);
    Route::post('/companies', [CompanyController::class, 'store']);

    // Internship verification
    Route::post('/internships/{internship}/send-verification', [InternshipVerificationController::class, 'sendVerificationEmail']);

    // Document-related actions
    Route::prefix('internships/{id}')->group(function () {
        Route::get('/documents', [DocumentController::class, 'index']);
        Route::post('/documents', [DocumentController::class, 'store']);
        Route::delete('/documents/{documentId}', [DocumentController::class, 'destroy']);
        Route::patch('/documents/{documentId}/verify', [DocumentController::class, 'verifyDocument']);
        Route::get('/documents/{documentId}/download', [DocumentController::class, 'download']);
        Route::get('/contract', [DocumentController::class, 'generateContractPdf']);
    });
});
