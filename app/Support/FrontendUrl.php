<?php

namespace App\Support;

use App\Enums\Locale;

class FrontendUrl
{
    /**
     * Frontend uses the "prefix_except_default" i18n strategy, so only
     * non-default locales are prefixed in the URL.
     */
    public const DEFAULT_LOCALE = Locale::LT;

    public static function to(string $path, Locale|string|null $locale = null): string
    {
        $locale = self::locale($locale);

        $prefix = $locale === self::DEFAULT_LOCALE ? '' : '/' . $locale->value;

        return rtrim(env('APP_FRONTEND_URL'), '/') . $prefix . '/' . ltrim($path, '/');
    }

    public static function locale(Locale|string|null $locale = null): Locale
    {
        if ($locale instanceof Locale) {
            return $locale;
        }

        return Locale::tryFrom($locale ?? app()->getLocale()) ?? self::DEFAULT_LOCALE;
    }
}
