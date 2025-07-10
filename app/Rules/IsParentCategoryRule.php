<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsParentCategoryRule implements ValidationRule
{
    protected $parentCategoryId;

    protected $isParent;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($parentCategoryId, $isParent)
    {
        $this->parentCategoryId = $parentCategoryId;
        $this->isParent = $isParent;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->parentCategoryId && $this->isParent != 0) {
            $fail('The :attribute must be "No" if a parent category is selected.');
        }
    }
}
