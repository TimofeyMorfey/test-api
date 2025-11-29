<?php

namespace App\Console\Commands;

use App\Models\Income;
use App\Services\ApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchIncomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-incomes {--dateFrom=2024-01-01} {--dateTo=2024-01-02}';

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

        $incomesData = $this->apiService->getIncomes($dateFrom, $dateTo);

        // Log::info('Sales data received from API', ['salesData' => $salesData]);


        if (empty($incomesData)) {
            $this->error('Ups...');
            return 1;
        }
        foreach ($incomesData['data'] as $incomeData) {

            try {
                $preparedData =
                    [
                        "income_id" => $incomeData['income_id'] ?? 0,
                        "number" => $incomeData['number'] ?? null,
                        "date" => $saleData['date'] ?? '2025-01-01',
                        "last_change_date" => $saleData['last_change_date'] ?? '2025-01-01',
                        "supplier_article" => $saleData['supplier_article'] ?? '',
                        "tech_size" => $saleData['tech_size'] ?? '',
                        "barcode" => $saleData['barcode'] ?? 0,
                        "quantity" => $incomeData['quantity'] ?? 0,
                        "total_price" => $saleData['total_price'] ?? 0,
                        "date_close" => $incomeData['date_close'] ?? '0000-00-00',
                        "warehouse_name" => $saleData['warehouse_name'] ?? '',
                        "nm_id" => $saleData['nm_id'] ?? 0,
                    ];
                $this->info($preparedData['income_id']);
                Income::updateOrCreate(['income_id' => $preparedData['income_id']], $preparedData);
                Log::info('Получение доходов прошло успешно');

            } catch (\Exception $e) {
                Log::error("Error procesing income: " . $e->getMessage(), [
                    'income_id' => $saleData['income_id'] ?? 'unknow'
                ]);
                $this->warn('Error processing income: ' . $e->getMessage());
            }
        }

        return 0;

    }
}
