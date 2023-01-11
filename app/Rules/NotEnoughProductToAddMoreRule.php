<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\InvokableRule;

class NotEnoughProductToAddMoreRule implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $errorCart = $value;
        $productColor = $errorCart->productColor;
        $fail('There is not enough quantity for '.$errorCart->product->name.($productColor?' with color '.$productColor->Color->name:''));
    }
}
