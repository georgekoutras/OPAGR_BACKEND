<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class refreshTimeLoading implements Rule
{

    protected $loading;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($refresh_time_loading)
    {
        $this->loading = $refresh_time_loading;
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
        return !$this->loading;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Refresh Index cannot be changed! A previous modification must end first.';
    }
}
