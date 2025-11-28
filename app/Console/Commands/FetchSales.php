<?php

namespace App\Console\Commands;

use App\Models\Sale;
use App\Services\ApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-sales {--dateFrom=2024-01-01} 
                                            {--dateTo=2024-01-02}';

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

        $salesData = $this->apiService->getSales($dateFrom, $dateTo);

        Log::info('Sales data received from API', ['salesData' => $salesData]);


        if (empty($salesData)) 
        {
            $this->error('Ups...');
            return 1;
        }
        foreach ($salesData as $saleData) 
        {

            try 
            {
                $preparedData = 
                [
                    "g_number" => $saleData['g_number'] ?? '',
                    "date" => $saleData['date'] ?? '2025-01-01',
                    "last_change_date" => $saleData['last_change_date'] ?? '2025-01-01',
                    "supplier_article" => $saleData['supplier_article'] ?? '',
                    "tech_size" => $saleData['tech_size'] ?? '',
                    "barcode" => $saleData['barcode'] ?? 0,
                    "total_price" => $saleData['total_price'] ?? 0,
                    "discount_percent" =>$saleData['discount_percent'] ?? 0,
                    "is_supply" =>$saleData['is_supply'] ?? false,
                    "is_realization" =>$saleData['is_realization'] ?? false,
                    "promo_code_discount" =>$saleData['promo_code_discount'] ?? 0,
                    "warehouse_name" =>$saleData['warehouse_name'] ?? '',
                    "country_name" =>$saleData['country_name'] ?? '',
                    "oblast_okrug_name" =>$saleData['oblast_okrug_name'] ?? '',
                    "region_name" =>$saleData['region_name'] ?? '',
                    "income_id" =>$saleData['income_id'] ?? 0,
                    "sale_id" =>$saleData['sale_id'] ?? '',
                    "odid" =>$saleData['odid'] ?? null,
                    "spp" =>$saleData['spp'] ?? 0,
                    "for_pay" =>$saleData['for_pay'] ?? 0,
                    "finished_price" =>$saleData['finished_price'] ?? 0,
                    "price_with_disc" =>$saleData['price_with_disc'] ?? 0,
                    "nm_id" =>$saleData['nm_id'] ?? 0,
                    "subject" =>$saleData['subject'] ?? '',
                    "category" =>$saleData['category'] ?? '',
                    "brand" =>$saleData['brand'] ?? '',
                    "is_storno" =>$saleData['is_storno'] ?? false,
                ];
                $this->info($preparedData['g_number']);
                Sale::updateOrCreate(['sale_id' => $preparedData['sale_id']], $preparedData);

            } catch (\Exception $e) 
            {
                Log::error("Error procesing sale: " . $e->getMessage(), [
                    'sale_id' => $saleData['sale_id'] ?? 'unknow'
                ]);
                $this->warn('Error processing sale: ' . $e->getMessage());
            }
        }

        return 0;
            
    }
}
