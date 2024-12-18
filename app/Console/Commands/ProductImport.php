<?php

namespace App\Console\Commands;

use App\Services\ConvertJson;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\StreamInterface;

class ProductImport extends Command
{
    protected $signature = 'app:product-import';

    protected $description = 'Command description';

    const API_URL = "https://api.manorivile.lt/client/v2";

    public Client $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Client([
            'headers' => [
                'ApiKey' => env('RIVILE_API'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    public function handle()
    {
        $this->getPagedData(function ($products, $page) {
            dd('aa');
        }, 'GET_N08_LIST');

    }

    private function post($body)
    {
        try {
            $response = $this->client->post(self::API_URL, [
                'body' => json_encode($body)
            ]);
        } catch (GuzzleException $e) {
            Log::channel('abuse')->info('POST error:', ['error' => $e]);
            return false;
        }
        return $response;
    }

    public function getStock(Closure $callback)
    {
        $this->getPagedData($callback, 'GET_I17_LIST');
    }

    public function getProducts(Closure $callback)
    {
        $this->getPagedData($callback, 'GET_N17_LIST');
    }

    public function getPrice(Closure $callback)
    {
        $this->getPagedData($callback, 'GET_I33_LIST');
    }

    private function getPagedData(Closure $callback, string $method)
    {
        $pageNumber = 1;

        $response = $this->getMethod($method, $pageNumber);

        if (!is_null($response)) {
            $products = new ConvertJson($response);
//            $callback($products, $pageNumber);
//            $pageNumber++;
        } else {
//            $pageNumber = 0;
        }

        file_put_contents('myfile3.json', json_encode($products));

//        while ($pageNumber > 0) {
//            $response = $this->getMethod($method, $pageNumber);
//            if (!is_null($response)) {
//                $products = new ConvertJson($response);
//                $callback($products, $pageNumber);
//                $pageNumber++;
//            } else {
//                $pageNumber = 0;
//            }
//        }
    }

    private function getMethod($method, $pageNumber): ?StreamInterface
    {
        if ($method == 'GET_I17_LIST') {
            $list = '';
            $fil = "I17_KODAS_PS IN (SELECT i33_kodas_ps FROM I33_PKAI WHERE i33_kaina>0 AND i33_kodas_is='06' AND I33_POZ_DATE=0)";
        } else if ($method == 'GET_N17_LIST') {
            $list = 'A';
            $fil = "N17_KODAS_PS IN (SELECT i33_kodas_ps FROM I33_PKAI WHERE i33_kaina>0 AND i33_kodas_is='06' AND I33_POZ_DATE=0)";
        } else {
            $list = '';
            $fil = '';
        }
        $response = $this->post([
            'method' => $method,
            'params' => [
                'list' => $list,
                'pagenumber' => $pageNumber,
                'fil' => $fil
            ]
        ]);

        if ($response->getBody()->getContents() == '{}') {
            dd('aaa');
            return $response = null;
        }

        return $response->getBody();
    }
}
