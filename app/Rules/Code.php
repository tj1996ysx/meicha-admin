<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Code implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $start_code = request()->input('start_code');
        $end_code = request()->input('end_code');
        if ($start_code >= $end_code) {
            return false;
        }

        if ($end_code - $start_code > 1000) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '开始码必须小于结束码,并且两者之差不能大于1000';
    }
}
