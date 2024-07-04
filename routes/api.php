<?php

use App\Http\Controllers\HooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/hooks/deploy/{repoName}', [HooksController::class, 'handleGithubWebhookForDeploy']);
