<?php

namespace App\Rules;

use App\Enums\Locale;
use App\Support\FrontendUrl;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as LaravelValidator;

/**
 * Reports each locale of a translatable field under its own key (`name.lt`), which is what the
 * admin panel looks for to highlight the language tab that failed. Laravel cannot declare
 * sibling keys from a rule object, hence the nested validator merged into the outer message bag.
 */
class TranslatedLocales implements ValidationRule, ValidatorAwareRule
{
    protected LaravelValidator $validator;

    public function __construct(
        protected bool $requireDefaultLocale = true,
        protected int  $max = 255,
    )
    {
    }

    public function setValidator(LaravelValidator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            return;
        }

        $nested = Validator::make(
            ['value' => $value],
            $this->rules(),
            $this->messages($attribute),
            $this->attributes($attribute),
        );

        foreach ($nested->errors()->messages() as $key => $messages) {
            $locale = substr($key, strlen('value.'));

            foreach ($messages as $message) {
                $this->validator->errors()->add("{$attribute}.{$locale}", $message);
            }
        }
    }

    /**
     * @return array<string, array<string>>
     */
    private function rules(): array
    {
        $rules = [];

        foreach (Locale::cases() as $locale) {
            $isDefault = $locale === FrontendUrl::DEFAULT_LOCALE;

            $rules["value.{$locale->value}"] = [
                $this->requireDefaultLocale && $isDefault ? 'required' : 'nullable',
                'string',
                "max:{$this->max}",
            ];
        }

        return $rules;
    }

    private function messages(string $attribute): array
    {
        $messages = [];

        foreach ($this->validator->customMessages as $key => $message) {
            if (str_starts_with($key, "{$attribute}.")) {
                $messages['value.' . substr($key, strlen("{$attribute}."))] = $message;
            }
        }

        return $messages;
    }

    private function attributes(string $attribute): array
    {
        $field = last(explode('.', $attribute));

        $label = Lang::has("validation.attributes.{$field}") ? __("validation.attributes.{$field}") : $field;

        $attributes = [];

        foreach (Locale::cases() as $locale) {
            $attributes["value.{$locale->value}"] = $label . ' (' . strtoupper($locale->value) . ')';
        }

        return $attributes;
    }
}
