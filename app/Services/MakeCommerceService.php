<?php

namespace App\Services;

use App\Models\Payment;
use Maksekeskus\Maksekeskus;

class MakeCommerceService
{
    private Maksekeskus $client;

    public function __construct()
    {
        $this->client = new Maksekeskus(
            env('MAKECOMMERCE_SHOP_ID'),
            env('MAKECOMMERCE_PUBLIC_KEY'),
            env('MAKECOMMERCE_SECRET_KEY'),
            env('MAKECOMMERCE_ENVIRONMENT') === 'test',
        );
    }

    public function createTransaction(Payment $payment, string $email, string $ip): string
    {
        $transaction = [
            'amount' => $payment->paid_amount,
            'currency' => 'EUR',
            'reference' => $payment->id,
            'transaction_url' => [
                'return_url' => [
                    'url' => env('APP_FRONTEND_URL') . '/payment',
                    'method' => 'get',
                ],
                'cancel_url' => [
                    'url' => env('APP_FRONTEND_URL') . '/payment',
                    'method' => 'get',
                ]
            ]
        ];

        if (env('APP_ENV') === 'production') {
            $transaction['transaction_url']['notifications_url'] = [
                'url' => env('APP_URL') . '/api/payments/callback',
                'method' => 'post',
            ];
        }

        $transaction = $this->client->createTransaction([
            'transaction' => $transaction,
            'customer' => [
                'email' => $email,
                'ip' => $ip,
                'country' => 'LT',
                'locale' => 'LT'
            ]
        ]);

        $payment->update(['transaction_id' => $transaction->id]);

        return $transaction->payment_methods->other[0]->url;
    }

    public function verify(string $json, string $mac): bool
    {
        return $this->client->verifyMac(['json' => $json, 'mac' => $mac]);
    }

    public function extractData(string $json): array
    {
        return $this->client->extractRequestData(['json' => $json]);
    }
}
