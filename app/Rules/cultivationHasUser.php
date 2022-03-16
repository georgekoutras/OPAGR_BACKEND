<?php

namespace App\Rules;

use App\Models\Cultivation;
use Illuminate\Contracts\Validation\Rule;

class cultivationHasUser implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // if type is null return true
        if (empty($value)) return true;

        // check if value is array type
        if (!is_array($value)) $value = array($value);
        $attributes = collect();

        // check the cultivation ids and then the relation of each cultivation with the user
        foreach ($value as $cul_id) {

            if (!is_null($cultivation = Cultivation::find($cul_id))) {

                $user_state = $cultivation->user()->first()->state;
                if ($user_state === 'active') {
                    $attributes->push(true);
                } else {
                    $attributes->push(false);
                }
            } else {
                $attributes->push(false);
            }
        }
        if(in_array(false, $attributes->toArray())) return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute does not match with an active user';
    }
}
