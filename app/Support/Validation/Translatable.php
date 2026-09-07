<?php

namespace App\Support\Validation;

use App\Enums\Locale;
use App\Rules\TranslatedLocales;
use Attribute;
use Spatie\LaravelData\Attributes\Validation\CustomValidationAttribute;
use Spatie\LaravelData\Support\Validation\ValidationPath;

/**
 * Validates a bag of translations keyed by locale. Only the default locale is ever required -
 * it is the one every other locale falls back to.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Translatable extends CustomValidationAttribute
{
    public function __construct(
        public bool $required = true,
        public int  $max = 255,
    )
    {
    }

    public function getRules(ValidationPath $path): array
    {
        return [
            $this->required ? 'required' : 'nullable',
            'array:' . implode(',', array_column(Locale::cases(), 'value')),
            new TranslatedLocales(requireDefaultLocale: $this->required, max: $this->max),
        ];
    }
}
