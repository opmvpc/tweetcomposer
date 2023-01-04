<?php

namespace App\Http\Requests\TwitterProfile;

use Illuminate\Foundation\Http\FormRequest;

class SelectProfileRequest extends FormRequest
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
            'selectedProfileId' => ['required', 'integer', 'exists:twitter_profiles,id'],
        ];
    }
}
