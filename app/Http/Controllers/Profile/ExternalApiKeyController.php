<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExternalApiKeysRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExternalApiKeyController extends Controller
{
    public function index()
    {
        return Inertia::render('Profile/ExternalApiKey', [
            'user' => Auth::user(),
        ]);
    }

    public function update(ExternalApiKeysRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'gpt_api_key' => $request->validated()['gpt_api_key'],
        ]);

        return redirect()->route('external-api-key.index');
    }
}
