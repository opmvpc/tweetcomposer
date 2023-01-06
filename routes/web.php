<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Profile\ExternalApiKeyController;
use App\Http\Controllers\Tweets\ComposeTweetController;
use App\Http\Controllers\Tweets\ThreadController;
use App\Http\Controllers\TwitterProfileController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

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
    return Redirect::route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::middleware([
        'hasTwitterProfile',
    ])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile/external-api-key', [ExternalApiKeyController::class, 'index'])->name('external-api-key.index');
        Route::put('/profile/external-api-key', [ExternalApiKeyController::class, 'update'])->name('external-api-key.update');

        Route::put('/threads/{threadId}/status', [ThreadController::class, 'updateStatus'])->name('threads.status.update');
        Route::resource('/threads', ThreadController::class)->only(['index', 'update']);

        Route::get('/compose/{threadId?}', [ComposeTweetController::class, 'index'])->name('compose.index');
        Route::put('/compose/{threadId?}', [ComposeTweetController::class, 'update'])->name('compose.update');
        Route::post('/compose/{threadId}/add-reply', [ComposeTweetController::class, 'addReply'])->name('compose.add-reply');
        Route::post('media/{tweetId}', [ComposeTweetController::class, 'uploadMedia'])->name('media.upload');
    });

    // Twitter Profile Routes
    Route::get('/twitter-profile', [TwitterProfileController::class, 'index'])->name('twitter-profile.index');
    Route::get('/auth/twitter/redirect', [TwitterProfileController::class, 'addTwitterAccount'])->name('twitter-profile.create');
    Route::get('/auth/twitter/callback', [TwitterProfileController::class, 'store'])->name('twitter-profile.store');
    Route::put('/twitter-profile/select', [TwitterProfileController::class, 'updateSelectedProfile'])->name('twitter-profile.select.update');
    Route::delete('/twitter-profile/{twitterProfileId}', [TwitterProfileController::class, 'destroy'])->name('twitter-profile.destroy');
});

// Route::get('/test', function () {
//     $thread = App\Models\Thread::find(11);
//     SendThread::dispatch($thread);

//     return redirect()->back();
// });
