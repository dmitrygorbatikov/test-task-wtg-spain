<?php

use App\UI\Api\Http\Controllers\Auth\LoginUserWithEmailController;
use App\UI\Api\Http\Controllers\Auth\LogoutUserController;
use App\UI\Api\Http\Controllers\Chat\CreateChatController;
use App\UI\Api\Http\Controllers\Chat\GetChatItemController;
use App\UI\Api\Http\Controllers\Chat\GetChatListController;
use App\UI\Api\Http\Controllers\Message\CreateMessageController;
use App\UI\Api\Http\Controllers\Message\GetMessageListController;
use App\UI\Api\Http\Controllers\Message\ReadMessageController;
use App\UI\Api\Http\Controllers\User\GetAuthorizedUsersController;
use App\UI\Api\Http\Controllers\User\Me\GetUserGeneralInfoController;
use Illuminate\Support\Facades\Route;
use App\UI\Api\Http\Controllers\Auth\Code\{
    FinishEmailRegistrationController,
    InitializeEmailRegistrationController,
    ResendRegistrationVerificationController
};

Route::prefix('code')->name('code.')->group(function (): void {
    Route::post('initialize', InitializeEmailRegistrationController::class)
//        ->middleware('throttle:auth_routes_with_email')
        ->name('initialize');

    Route::post('resend-verification', ResendRegistrationVerificationController::class)
//        ->middleware('throttle:auth_resend_verification')
        ->name('resend-verification');

    Route::post('finish', FinishEmailRegistrationController::class)->name('finish');
//        ->middleware('throttle:auth_finish');
    Route::post('login', LoginUserWithEmailController::class)
//        ->middleware('throttle:auth_routes_with_email')
        ->name('initialize');

});

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('logout', LogoutUserController::class)->name('logout');

    Route::prefix('users')->name('users.')->group(function (): void {
        Route::get('me', GetUserGeneralInfoController::class)->name('me');
        Route::get('', GetAuthorizedUsersController::class)->name('list');
    });

    Route::prefix('chats')->name('chats.')->group(function (): void {
        Route::post('', CreateChatController::class)->name('create');
        Route::get('', GetChatListController::class)->name('list');
        Route::get('{chatId}', GetChatItemController::class)->name('item');
    });

    Route::prefix('messages')->name('messages.')->group(function (): void {
        Route::post('', CreateMessageController::class)->name('create');
        Route::get('', GetMessageListController::class)->name('list');
        Route::post('read', ReadMessageController::class)->name('read');
    });
});


