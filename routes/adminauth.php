<?php

use App\Http\Controller\AdminAuth\AuthenticatedSessionController;
use App\Http\Controller\AdminAuth\ConfirmablePasswordController;
use App\Http\Controller\AdminAuth\EmailVerifivicationNotificationController;
use App\Http\Controller\AdminAuth\EmailVerifivicationPromptController;
use App\Http\Controller\AdminAuth\NewPasswordController;
use App\Http\Controller\AdminAuth\PasswordController;
use App\Http\Controller\AdminAuth\PasswordResetLinkController;
use App\Http\Controller\AdminAuth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function(){

    Route::get('admin:login', [AuthenticatedSessionController::class, 'create'])
                ->name('admin.login');

    Route::post([AuthenticatedSessionController::class, 'store']);

    Route::post('admin/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('admin.password.email');

    Route::get('admin/reset-password/{tokrn}', [NewPasswordController::class, 'create'])
    ->name('admin.password.reset');

    Route::post('admin/reset-password', [NewPasswordController::class])
                ->name('admin.password.store');

});

Route::middleware('auth:admin')->group(function(){
    Route::get('admin/verify-email', EmailVerifivicationPromptController::class)
                ->name('admin.verification.notice');

Route::get('admin/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('admin.verivication.verify');

Route::post('admin/email/verification-notification', [EmailVerifivicationNotificationController::class, store])
                ->middleware(['throttle:6,1'])
                ->name('admin.verification.send');

Route::get('admin/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('admin.password.confirm');

Route::post('admin/confirm-password', [ConfirmablePasswordController::class, 'store']);

Route::put('admin/password', [PasswordController::class, 'update'])
                ->name('admin.password.update');

Route::post('admin/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('admin.logout');
                
});