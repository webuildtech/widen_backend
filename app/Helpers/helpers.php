<?php

if (!function_exists('applyDiscountAndCalculatePriceDetails')) {
    /**
     * @return stdClass{price: float, discount: float, vat: float, priceWithVat: float}
     */
    function applyDiscountAndCalculatePriceDetails(float $priceWithVat, float $discount): stdClass
    {
        $priceWithoutVAT = round($priceWithVat / 1.21, 2);

        $discountAmount = round($priceWithoutVAT * ($discount / 100), 2);
        $discountedTotal = $priceWithoutVAT - $discountAmount;

        $vat = round($discountedTotal * 0.21, 2);
        $priceWithVat = $discountedTotal + $vat;

        return (object) [
            'price' => $discountedTotal,
            'discount' => $discountAmount,
            'vat' => $vat,
            'priceWithVat' => $priceWithVat,
        ];
    }
}
