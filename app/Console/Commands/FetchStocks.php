<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Services\ApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-stocks {--dateFrom=2024-01-01}';

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

        $stocksData = $this->apiService->getStocks($dateFrom);

        if (empty($stocksData)) {
            $this->error('Ups...');
            return 1;
        }
        foreach ($stocksData['data'] as $stockData) {

            try {

                // $uniqueKey = $stockData['supplier_article'] . '_' . $stockData['warehouse_name'] . '_' . $stockData['barcode'];

                $preparedData =
                    [
                        "date" => $stockData['date'] ?? null,
                        "last_change_date" => $stockData['last_change_date'] ?? '',
                        "supplier_article" => $stockData['supplier_article'] ?? '',
                        "tech_size" => $stockData['tech_size'] ?? '',
                        "barcode" => $stockData['barcode'] ?? 0,
                        "quantity" => $stockData['quantity'] ?? 0,
                        "is_supply" => $stockData['is_supply'] ?? false,
                        "is_realization" => $stockData['is_realization'] ?? false,
                        "quantity_full" => $stockData['quantity_full'] ?? 0,
                        "warehouse_name" => $stockData['warehouse_name'] ?? '',
                        "in_way_to_client" => $stockData['in_way_to_client'] ?? 0,
                        "in_way_from_client" => $stockData['in_way_from_client'] ?? 0,
                        "nm_id" => $stockData['nm_id'] ?? 0,
                        "subject" => $stockData['subject'] ?? '',
                        "category" => $stockData['category'] ?? '',
                        "brand" => $stockData['brand'] ?? '',
                        "sc_code" => $stockData['sc_code'] ?? 0,
                        "price" => $stockData['price'] ?? 0,
                        "discount" => $stockData['discount'] ?? 0,
                    ];
                
                    // $stock = Stock::where('supplier_article', $preparedData['supplier_article'])
                    // ->where('warehouse_name', $preparedData['warehouse_name'])
                    // ->where('barcode', $preparedData['barcode']);

                $this->info($preparedData['sc_code']);
                Stock::updateOrCreate( $preparedData);
                Log::info('Получение заказов прошло успешно');

            } catch (\Exception $e) {
                Log::error("Error procesing stock: " . $e->getMessage(), [
                    'sc_code' => $saleData['sc_code'] ?? 'unknow'
                ]);
                $this->warn('Error processing stock: ' . $e->getMessage());
            }
        }

        return 0;

    }
}
