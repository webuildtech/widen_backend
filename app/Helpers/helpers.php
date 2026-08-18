<?php

use App\Data\Core\Pricing\PriceDetailsData;
use Illuminate\Support\Str;

if (!function_exists('applyDiscountAndCalculatePriceDetails')) {
    function applyDiscountAndCalculatePriceDetails(float $priceWithVat, float $discount = 0): PriceDetailsData
    {
        $priceWithoutVAT = $priceWithVat / 1.21;

        $discountAmount = $priceWithoutVAT * ($discount / 100);
        $discountedTotal = $priceWithoutVAT - $discountAmount;

        $vat = round($discountedTotal * 0.21, 2);
        $priceWithVat = round($discountedTotal, 2) + $vat;

        return new PriceDetailsData($discountedTotal, $discountAmount, $vat, $priceWithVat);
    }
}

if (!function_exists('publicName')) {
    function publicName(?string $firstName, ?string $lastName, string $email): string
    {
        $firstName = trim((string)$firstName);
        $lastName = trim((string)$lastName);

        if ($firstName === '') {
            return Str::before($email, '@');
        }

        return $lastName === ''
            ? $firstName
            : $firstName . ' ' . Str::upper(Str::substr($lastName, 0, 1)) . '.';
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice(float $price): string
    {
        return number_format($price, 2, ',', ' ') . ' €';
    }
}
