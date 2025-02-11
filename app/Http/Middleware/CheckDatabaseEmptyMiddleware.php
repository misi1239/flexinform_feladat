<?php

namespace App\Http\Middleware;

use App\Jobs\LoadJsonDataJob;
use App\Models\Car;
use App\Models\Client;
use App\Models\Service;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckDatabaseEmptyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (Client::all()->isEmpty()) {
                LoadJsonDataJob::dispatch('clients.json', Client::class);
            }

            if (Car::all()->isEmpty()) {
                LoadJsonDataJob::dispatch('cars.json', Car::class);
            }

            if (Service::all()->isEmpty()) {
                LoadJsonDataJob::dispatch('services.json', Service::class);
            }

        } catch (\Exception $e) {
            Log::error('Error dispatching data load jobs: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while queuing data load jobs.'], 500);
        }
        return $next($request);
    }
}
