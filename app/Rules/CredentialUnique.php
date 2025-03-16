<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CredentialUnique implements Rule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // public function validate(string $attribute, mixed $value, Closure $fail): void
    // {

    // }
    public function passes($attribute, $value)
    {
        if (is_numeric($value)) {
            // Check if phone is unique
            return !DB::table('customers')->where('phone', $value)->exists();
        } else {
            // Check if email is unique
            return !DB::table('customers')->where('email', $value)->exists();
        }
    }

    public function message()
    {
        return 'The credential has already been taken.';
    }
}
