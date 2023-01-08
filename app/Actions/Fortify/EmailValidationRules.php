<?php

namespace App\Actions\Fortify;

use App\Actions\Fortify\Rules\RestrictedDomain;

trait EmailValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function emailRules()
    {
        return ['required', 'string', 'email', new RestrictedDomain, 'max:255', 'unique:users'];
    }
}
