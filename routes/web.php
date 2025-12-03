<?php

use App\Http\Controllers\LampiranCutiController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

// Route untuk download lampiran cuti
Route::get('/cuti/{cuti}/lampiran/{filename}', [LampiranCutiController::class, 'download'])
    ->middleware(['auth'])
    ->name('cuti.lampiran.download');

//Route::get('/email/verify', function () {
//    return view('auth.verify-email');
//})->middleware('auth')->name('verification.notice');
//
//Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//    $request->fulfill();
//    return redirect('/dashboard');
//})->middleware(['auth', 'signed'])->name('verification.verify');
//
//Route::post('/email/verification-notification', function (Request $request) {
//    $request->user()->sendEmailVerificationNotification();
//    return back()->with('message', 'Verification link sent!');
//})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
