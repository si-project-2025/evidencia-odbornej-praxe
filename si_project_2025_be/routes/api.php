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

Route::post('/internships/action', [InternshipVerificationController::class, 'handleInternshipAction']);
Route::get('/internships/get-verification-details',
    [InternshipVerificationController::class, 'getVerificationDetails']
);

// ----------------------------
// External system routes
// ----------------------------
Route::prefix('external')
    ->middleware(['client:internship:defend'])
    ->group(function () {
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
    Route::get('/user/internships', [InternshipController::class, 'getInternshipsByUser']);
    Route::get('/internships/companies', [InternshipController::class, 'getCompanies']);
    Route::post('/internships/companies', [\App\Http\Controllers\Api\CompanyController::class, 'store']);
    Route::get('/internships/garants', [InternshipController::class, 'getGarants']);
    Route::get('/internships/students', [InternshipController::class, 'getStudents']);
    Route::get('/statuses', function () { return Status::select( 'type')->get(); });

    // CRUD (internships + contact persons)
    Route::apiResource('internships', InternshipController::class);
    Route::apiResource('internships.contact-persons', ContactPersonController::class)
        ->parameters(['contact-persons' => 'contactPerson']);

    // Internship verification
    Route::post('/internships/{internship}/send-verification', [InternshipVerificationController::class, 'sendVerificationEmail']);

});
    // Document-related actions
    Route::prefix('internships/{id}')->group(function () {
        Route::get('/documents', [InternshipDocumentController::class, 'index']);
        Route::post('/documents', [InternshipDocumentController::class, 'store']);
        Route::delete('/documents/{documentId}', [InternshipDocumentController::class, 'destroy']);
        Route::delete('/documents/{documentId}/verify', [InternshipDocumentController::class, 'verifyDocument']);
        Route::get('/documents/{documentId}/download', [InternshipDocumentController::class, 'download']);
        Route::get('/contract', [InternshipDocumentController::class, 'generateContractPdf']);
    });
