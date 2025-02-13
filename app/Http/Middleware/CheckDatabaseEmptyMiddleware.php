<?php

namespace App\Http\Middleware;

use App\Models\Car;
use App\Models\Client;
use App\Models\Service;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CheckDatabaseEmptyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (Cache::has('db_loading_in_progress') && Cache::get('db_loading_in_progress')) {
                return $next($request);
            }

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
        } catch (\Exception $e) {
            Log::error('Error loading data: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while loading data.'], 500);
        }

        return $next($request);
    }

    private function loadDataIfEmpty($model, string $fileName, array $mapping): void
    {
        if ($model::count() === 0) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            try {
                $data = json_decode(file_get_contents(database_path('json/' . $fileName)), true);
                if ($data === null) {
                    throw new \Exception('Failed to decode JSON data from ' . $fileName);
                }

                foreach ($data as $item) {
                    $model::create($this->mapFields($item, $mapping));
                }
            } catch (\Exception $e) {
                Log::error('Error loading data into model ' . $model . ': ' . $e->getMessage());
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
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
