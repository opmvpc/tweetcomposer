<?php

use App\Http\Controllers\Profile\SocialApiKeyController;
use App\Http\Controllers\Tweets\ComposeTweetController;
use App\Http\Controllers\Tweets\ThreadController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/profile/social-api-key', [SocialApiKeyController::class, 'index'])->name('social-api-key.index');

    Route::put('/profile/social-api-key', [SocialApiKeyController::class, 'update'])->name('social-api-key.update');

    Route::get('/compose/{threadId?}', [ComposeTweetController::class, 'index'])->name('compose.index');
    Route::put('/compose/{threadId?}', [ComposeTweetController::class, 'update'])->name('compose.update');
    Route::post('/compose/{threadId}/add-reply', [ComposeTweetController::class, 'addReply'])->name('compose.add-reply');

    Route::resource('/tweets', ThreadController::class)->only(['index']);
});
