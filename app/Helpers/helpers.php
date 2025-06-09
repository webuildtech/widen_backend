<?php

use App\Data\Core\Pricing\PriceDetailsData;

if (!function_exists('applyDiscountAndCalculatePriceDetails')) {
    function applyDiscountAndCalculatePriceDetails(float $priceWithVat, float $discount): PriceDetailsData
    {
        $priceWithoutVAT = $priceWithVat / 1.21;

        $discountAmount = $priceWithoutVAT * ($discount / 100);
        $discountedTotal = $priceWithoutVAT - $discountAmount;

        $vat = round($discountedTotal * 0.21, 2);
        $priceWithVat = round($discountedTotal, 2) + $vat;

        return new PriceDetailsData($discountedTotal, $discountAmount, $vat, $priceWithVat);
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice(float $price): string
    {
        return number_format($price, 2, ',', ' ') . ' €';
    }
}
