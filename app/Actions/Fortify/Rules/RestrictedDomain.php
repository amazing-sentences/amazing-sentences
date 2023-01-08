<?php

namespace App\Actions\Fortify\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class RestrictedDomain implements Rule
{
    protected $restrictedDomains = ['amazing-sentences.com'];

    /**
     * The message that should be used when validation fails.
     *
     * @var string
     */
    protected $message;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $stringValue = is_scalar($value) ? (string) $value : '';

        return ! Str::contains($stringValue, $this->restrictedDomains, true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->message) {
            return $this->message;
        }

        return __('The :attribute must not belong to a restricted domain.');
    }

    /**
     * Set the message that should be used when the rule fails.
     *
     * @param  string  $message
     * @return $this
     */
    public function withMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }
}
