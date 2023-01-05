<?php

namespace App\Http\Requests\Tweets;

use Illuminate\Foundation\Http\FormRequest;

class TweetsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tweets.*.id' => 'required|integer|exists:tweets,id',
            'tweets.*.content' => 'nullable|string|max:280',
        ];
    }

    public function attributes()
    {
        return [
            'tweets.*.id' => 'tweet id',
            'tweets.*.content' => 'tweet content',
        ];
    }
}
