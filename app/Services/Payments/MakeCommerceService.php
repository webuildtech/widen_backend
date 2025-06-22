<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Maksekeskus\Maksekeskus;

class MakeCommerceService
{
    private Maksekeskus $client;

    public function __construct()
    {
        $config = config('makecommerce');

        $this->client = new Maksekeskus(
            $config['shop_id'],
            $config['public_key'],
            $config['secret_key'],
            $config['environment'] === 'test',
        );
    }

    public function createTransaction(Payment $payment, string $ip): string
    {
        $frontendUrl = env('APP_URL') . '/payment';

        $transaction = [
            'amount' => $payment->paid_amount,
            'currency' => 'EUR',
            'reference' => $payment->id,
            'transaction_url' => [
                'return_url' => ['url' => $frontendUrl, 'method' => 'get'],
                'cancel_url' => ['url' => $frontendUrl, 'method' => 'get'],
            ]
        ];

        if (env('APP_ENV') === 'production') {
            $transaction['transaction_url']['notification_url'] = [
                'url' => env('APP_URL') . '/api/payments/callback',
                'method' => 'post',
            ];
        }

        $transaction = $this->client->createTransaction([
            'transaction' => $transaction,
            'customer' => [
                'email' => $payment->owner->email,
                'ip' => $ip,
                'country' => 'LT',
                'locale' => 'LT'
            ]
        ]);

        $payment->update(['transaction_id' => $transaction->id]);

        return $transaction->payment_methods->other[0]->url;
    }

    public function verify(array $values): bool
    {
        return $this->client->verifyMac($values);
    }

    public function extractData(string $json): array
    {
        return $this->client->extractRequestData(['json' => $json]);
    }
}
