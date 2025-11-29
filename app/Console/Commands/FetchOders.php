<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\ApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchOders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-orders {--dateFrom=2024-01-01} {--dateTo=2024-01-01}';

    /**
     * The console command description.
     *
     * @var string
     */


    protected $description = 'Command description';

    private Apiservice $apiService;


    public function __construct(ApiService $apiService) 
    {
        parent::__construct();
        $this->apiService = $apiService;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting...');

        $dateFrom = $this->option('dateFrom');

        Log::info('DateFrom: ', ['dateFrom' => $dateFrom]);

        $dateTo = $this->option('dateTo');

        Log::info('DateTo: ', ['dateTo' => $dateTo]);

        $ordersData = $this->apiService->getOrders($dateFrom, $dateTo);

        Log::info('ordersData: ', ['ordersData' => $ordersData]);

        if (empty($ordersData)) 
        {
            $this->error('Ups...');
            return 1;
        }
        foreach ($ordersData['data'] as $orderData) 
        {

            try 
            {
                $preparedData = 
                [
                    'g_number' => $orderData['g_number'] ?? '',
                    'date' => $orderData['date'] ?? '',
                    'last_change_date' => $orderData['last_change_date'] ?? '',
                    'supplier_article' => $orderData['supplier_article'] ?? '',
                    'tech_size' => $orderData['tech_size'] ?? '',
                    'barcode' => $orderData['barcode'] ?? 0,
                    'total_price' => $orderData['total_price'] ?? 0,
                    'discount_percent' => $orderData['discount_percent'] ?? 0,
                    'warehouse_name' => $orderData['warehouse_name'] ?? '',
                    'oblast' => $orderData['oblast'] ?? '',
                    'income_id' => $orderData['income_id'] ?? 0,
                    'odid' => $orderData['odid'] ?? null,
                    'nm_id' => $orderData['nm_id'] ?? 0,
                    'subject' => $orderData['subject'] ?? '',
                    'category' => $orderData['category'] ?? '',
                    'brand' => $orderData['brand'] ?? '',
                    'is_cancel' => $orderData['is_cancel'] ?? false,
                    'cancel_dt' => $orderData['cancel_dt'] ?? '',
                ];
                $this->info($preparedData['nm_id']);
                Order::updateOrCreate(['nm_id' => $preparedData['nm_id']], $preparedData);
                Log::info('Получение заказов прошло успешно');

            } catch (\Exception $e) 
            {
                Log::error("Error procesing sale: " . $e->getMessage(), [
                    'nm_id' => $orderData['nm_id'] ?? 'unknow'
                ]);
                $this->warn('Error processing order: ' . $e->getMessage());
            }
        }

        return 0;
            
    }
}

