<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinimumValue implements Rule
{
    protected $min;
    protected $equal = false;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min)
    {
        $this->min = $min;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_null($this->min)) {
            if ($this->min === $value) {
                $this->equal = true;
                return false;
            } else return !($this->min > $value);
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
        if ($this->equal) {
            return ':attribute value equals minimum value. Must be at least greater than ' . $this->min;
        } else return ':attribute value is below minimum value. Must be at least greater than ' . $this->min;
    }
}
