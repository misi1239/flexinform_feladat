<?php

namespace App\Jobs;

use App\Models\Car;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoadJsonDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly string $fileName, private readonly string $model)
    {
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        $this->loadData($this->fileName, $this->model);
    }

    private function loadData(string $fileName, $model): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            $data = json_decode(file_get_contents(database_path('json/' . $fileName)), true);
            if ($data === null) {
                throw new \Exception('Failed to decode JSON data from ' . $fileName);
            }

            foreach ($data as $item) {
                $model::create($this->mapFields($item, $model));
            }
        } catch (\Exception $e) {
            Log::error('Error loading data into model ' . $model . ': ' . $e->getMessage());
            throw new \Exception('Error loading data into model ' . $model . ': ' . $e->getMessage());
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function mapFields(array $data, string|Model $model): array
    {
        $mapping = [
            Client::class => [
                'id' => 'id',
                'name' => 'name',
                'idcard' => 'card_number',
            ],
            Service::class => [
                'id' => 'id',
                'client_id' => 'client_id',
                'car_id' => 'car_id',
                'lognumber' => 'log_number',
                'event' => 'event',
                'eventtime' => 'event_time',
                'document_id' => 'document_id',
            ],
            Car::class => [
                'id' => 'id',
                'client_id' => 'client_id',
                'car_id' => 'car_id',
                'type' => 'type',
                'registered' => 'registered',
                'ownbrand' => 'ownbrand',
                'accident' => 'accidents',
            ],
        ];

        $mappedData = [];
        if (isset($mapping[$model])) {
            foreach ($mapping[$model] as $jsonKey => $dbColumn) {
                if (isset($data[$jsonKey])) {
                    $mappedData[$dbColumn] = $data[$jsonKey];
                }
            }
        }
        return $mappedData;
    }
}
