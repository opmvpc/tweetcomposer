<?php

namespace App\Http\Requests\Tweets;

use App\Models\Thread;
use App\Rules\TimeHigherThanNow;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateThreadRequest extends FormRequest
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
        $scheduledAtDateRules = [Rule::requiredIf('scheduled' === Thread::findOrFail($this->thread)->status)];

        if (null !== $this->scheduledAtDate) {
            $scheduledAtDateRules[] = 'date_format:Y-m-d';
            $scheduledAtDateRules[] = 'after_or_equal:today';
        }

        $scheduledAtTimeRules = [Rule::requiredIf('scheduled' === Thread::findOrFail($this->thread)->status)];

        if (null !== $this->scheduledAtTime) {
            $scheduledAtTimeRules[] = 'date_format:H:i';
        }
        if (now()->format('Y-m-d') === $this->scheduledAtDate) {
            $scheduledAtTimeRules[] = new TimeHigherThanNow();
        }

        return [
            'selectedProfileId' => ['required', 'integer', 'exists:twitter_profiles,id'],
            'postAsThread' => ['required', 'boolean'],
            'scheduledAtDate' => $scheduledAtDateRules,
            'scheduledAtTime' => $scheduledAtTimeRules,
        ];
    }
}
