<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TimeHigherThanNow implements Rule
{
    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $now = now();
        $nowHour = $now->format('H');
        $nowMinute = $now->format('i');

        $valueHour = substr($value, 0, 2);
        $valueMinute = substr($value, 3, 2);

        if ($valueHour > $nowHour) {
            return true;
        }
        if ($valueHour === $nowHour) {
            if ($valueMinute > $nowMinute) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be higher than now.';
    }
}
