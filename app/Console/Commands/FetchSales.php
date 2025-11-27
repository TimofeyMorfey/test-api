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

    private ApiService $apiService;


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

        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');

        $salesData = $this->apiService->getSales($dateFrom, $dateTo);

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
                    "g_number" => $saleData['g_number'] ?? null,
                    "date" => $saleData['date'] ?? null,
                    "last_change_date" => $saleData['last_change_date'],
                    "supplier_article" => $saleData['supplier_article'],
                    "tech_size" => $saleData['tech_size'],
                    "barcode" => $saleData['barcode'],
                    "total_price" => $saleData['total_price'],
                    "discount_percent" =>$saleData['discount_percent'],
                    "is_supply" =>$saleData['is_supply'],
                    "is_realization" =>$saleData['is_realization'],
                    "promo_code_discount" =>$saleData['promo_code_discount'],
                    "warehouse_name" =>$saleData['warehouse_name'],
                    "country_name" =>$saleData['country_name'],
                    "oblast_okrug_name" =>$saleData['oblast_okrug_name'],
                    "region_name" =>$saleData['region_name'],
                    "income_id" =>$saleData['income_id'],
                    "sale_id" =>$saleData['sale_id'],
                    "odid" =>$saleData['odid'],
                    "spp" =>$saleData['spp'],
                    "for_pay" =>$saleData['for_pay'],
                    "finished_price" =>$saleData['finished_price'],
                    "price_with_disc" =>$saleData['price_with_disc'],
                    "nm_id" =>$saleData['nm_id'],
                    "subject" =>$saleData['subject'],
                    "category" =>$saleData['category'],
                    "brand" =>$saleData['brand'],
                    "is_storno" =>$saleData['is_storno'],
                ];

                $sale = Sale::firstOrNew(['sale_id' => $preparedData['sale_id']]);

                if ($sale->exists) {
                    $sale->update($preparedData);
                } else {
                    $sale->fill($preparedData)->save();
                }
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
