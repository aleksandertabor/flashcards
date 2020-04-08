<?php

namespace App\Rules;

use App\Deck;
use Illuminate\Contracts\Validation\Rule;

class MaximumFiftyCards implements Rule
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
        return Deck::find($value)->cards()->count() < 50;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Deck cannot have more than 50 cards.';
    }
}
