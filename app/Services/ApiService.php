<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService
{
    /**
     * Create a new class instance.
     */
    private string $baseUrl;

    private string $apikey;

    public function __construct()
    {
        $this->baseUrl = env('API_HOST', '109.73.206.144:6969');
        $this->apikey = env('API_KEY', 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie');
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @return array|null
     */
    public function getSales(string $dateFrom, string $dateTo): ?array
    {
        try {
            $url = $this->baseUrl . '/api/sales';

            $response = Http::timeout(100)
            ->retry(3)
            ->get($url, [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => 1,
                'key' => $this->apikey,
                'limit' => 100,
            ]);

            // Log::info("message", ['response' => $response]);
            
            $fullUrl = $response->effectiveUri();
            // Log::info("message", ['fullUrl' => $fullUrl]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('API SALES Error: ' . $response->status(), [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('API SALES Exeption: ' . $e->getMessage());
        }
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @return array|null
     */
    public function getOrders(string $dateFrom, string $dateTo): ?array
    {
        try {
            $url = $this->baseUrl . '/api/orders';

            $response = Http::timeout(100)
                ->retry(3)
                ->get($url, [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'page' => 1,
                    'key' => $this->apikey,
                    'limit' => 100,
                ]);

            // Log::info("message", ['response' => $response]);

            $fullUrl = $response->effectiveUri();
            Log::info("message", ['fullUrl' => $fullUrl]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('API ORDERS Error: ' . $response->status(), [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('API ORDERS Exeption: ' . $e->getMessage());
        }
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @return array|null
     */
    public function getIncomes(string $dateFrom, string $dateTo): ?array
    {
        try {
            $url = $this->baseUrl . '/api/incomes';

            $response = Http::timeout(100)
                ->retry(3)
                ->get($url, [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'page' => 1,
                    'key' => $this->apikey,
                    'limit' => 100,
                ]);

            // Log::info("message", ['response' => $response]);

            $fullUrl = $response->effectiveUri();
            Log::info("message", ['fullUrl' => $fullUrl]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('API INCOMES Error: ' . $response->status(), [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('API INCOMES Exeption: ' . $e->getMessage());
        }
    }

    /**
     * @param string $dateFrom
     * @return array|null
     */
    public function getStocks(string $dateFrom): ?array
    {
        try {
            $url = $this->baseUrl . '/api/stocks';

            $response = Http::timeout(100)
                ->retry(3)
                ->get($url, [
                    'dateFrom' => $dateFrom,
                    'page' => 1,
                    'key' => $this->apikey,
                    'limit' => 100,
                ]);

            // Log::info("message", ['response' => $response]);

            $fullUrl = $response->effectiveUri();
            Log::info("message", ['fullUrl' => $fullUrl]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('API STOCKS Error: ' . $response->status(), [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('API STOCKS Exeption: ' . $e->getMessage());
            return null;
        }
    }
}
