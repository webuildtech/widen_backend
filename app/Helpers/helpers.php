<?php

if (!function_exists('applyDiscountAndCalculatePriceDetails')) {
    /**
     * @return stdClass{price: float, discount: float, vat: float, priceWithVat: float}
     */
    function applyDiscountAndCalculatePriceDetails(float $priceWithVat, float $discount, bool $isFreeFromPlan = false): stdClass
    {
        if ($isFreeFromPlan) {
            return (object)['price' => 0, 'discount' => 0, 'vat' => 0, 'priceWithVat' => 0];
        }

        $priceWithoutVAT = $priceWithVat / 1.21;

        $discountAmount = $priceWithoutVAT * ($discount / 100);
        $discountedTotal = $priceWithoutVAT - $discountAmount;

        $vat = round($discountedTotal * 0.21, 2);
        $priceWithVat = round($discountedTotal, 2) + $vat;

        return (object)[
            'price' => round($discountedTotal, 2),
            'discount' => round($discountAmount, 2),
            'vat' => $vat,
            'price_with_vat' => round($priceWithVat, 2),
        ];
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice(float $price): string
    {
        return number_format($price, 2, ',', ' ') . ' €';
    }
}
