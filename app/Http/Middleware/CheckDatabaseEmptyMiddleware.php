<?php

namespace App\Http\Middleware;

use App\Models\Car;
use App\Models\Client;
use App\Models\Service;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class CheckDatabaseEmptyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

            $this->loadDataIfEmpty(Client::class, 'clients.json', [
                'id' => 'id',
                'name' => 'name',
                'idcard' => 'card_number',
            ]);

            $this->loadDataIfEmpty(Car::class, 'cars.json', [
                'id' => 'id',
                'client_id' => 'client_id',
                'car_id' => 'car_id',
                'type' => 'type',
                'registered' => 'registered',
                'ownbrand' => 'ownbrand',
                'accident' => 'accidents',
            ]);

            $this->loadDataIfEmpty(Service::class, 'services.json', [
                'id' => 'id',
                'client_id' => 'client_id',
                'car_id' => 'car_id',
                'lognumber' => 'log_number',
                'event' => 'event',
                'eventtime' => 'event_time',
                'document_id' => 'document_id',
            ]);

        return $next($request);
    }

    private function loadDataIfEmpty($model, string $fileName, array $mapping): void
    {
        if ($model::count() === 0) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            try {
                DB::beginTransaction();

                $data = json_decode(file_get_contents(database_path('json/' . $fileName)), true);
                if ($data === null) {
                    throw new \Exception("Failed to decode JSON data from file: {$fileName}");
                }

                foreach ($data as $index => $item) {
                    $mappedData = $this->mapFields($item, $mapping);
                    try {
                        $model::create($mappedData);
                    } catch (\Exception $e) {
                        throw new \Exception(
                            "Error inserting data into model: {$model} (File: {$fileName}, Index: {$index}) - " .
                            json_encode($mappedData) .
                            " | Error: " . $e->getMessage()
                        );
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Database load failed for model: {$model}, file: {$fileName}. Error: " . $e->getMessage());
                throw new \Exception("Failed to load data for {$model}. Error: " . $e->getMessage());
            } finally {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        }
    }

    private function mapFields(array $data, array $mapping): array
    {
        $mappedData = [];
        foreach ($mapping as $jsonKey => $dbColumn) {
            if (isset($data[$jsonKey])) {
                $mappedData[$dbColumn] = $data[$jsonKey];
            }
        }
        return $mappedData;
    }
}
