<?php

use hexa_package_prompt_center\Prompts\Categories\Http\Controllers\PromptCenterController;
use hexa_package_prompt_center\Prompts\Settings\Http\Controllers\SettingsController;

Route::middleware(['web', 'auth', 'locked'])->group(function () {
    Route::get('/prompt-center', [PromptCenterController::class, 'index'])->name('prompt-center.index');
    Route::get('/prompt-center/create', [PromptCenterController::class, 'create'])->name('prompt-center.create');
    Route::post('/prompt-center', [PromptCenterController::class, 'store'])->name('prompt-center.store');
    Route::get('/prompt-center/{id}/edit', [PromptCenterController::class, 'edit'])->name('prompt-center.edit');
    Route::put('/prompt-center/{id}', [PromptCenterController::class, 'update'])->name('prompt-center.update');
    Route::delete('/prompt-center/{id}', [PromptCenterController::class, 'delete'])->name('prompt-center.delete');
    Route::post('/prompt-center/{id}/set-default', [PromptCenterController::class, 'setDefault'])->name('prompt-center.set-default');

    Route::get('/prompt-center/settings', [SettingsController::class, 'index'])->name('prompt-center.settings');

    Route::get('/raw-prompt-center', function () {
        return view('prompt-center::raw.index');
    })->name('prompt-center.raw');
});
