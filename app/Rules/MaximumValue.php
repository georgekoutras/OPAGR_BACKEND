<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaximumValue implements Rule
{
    protected $max;
    protected $equal = false;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($max)
    {
        $this->max = $max;
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
        if (!is_null($this->max)) {

            if ($this->max === $value) {
                $this->equal = true;
                return false;
            } else return !($this->max < $value);
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
            return ':attribute value equals to maximum value. Must be at least lower than ' . $this->max;
        }
        return ':attribute value exceeds maximum value. Must be at least lower than ' . $this->max;
    }
}
