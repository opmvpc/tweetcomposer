<?php

namespace App\Http\Requests\Tweets;

use App\Models\Tweet;
use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
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
        $mediaCount = Tweet::findOrFail($this->tweetId)->media()->count();

        return [
            'files' => ['required', 'array', sprintf('max:%s', 4 - $mediaCount)],
            'files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,gif', 'max:10240'],
        ];
    }

    public function attributes()
    {
        return [
            'files' => 'uploaded files list',
        ];
    }
}
