<?php

namespace App\Http\Controllers\api\ServiceAction;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetMaxLogNumberForServicesAction extends Controller
{
    public function __invoke(int $clientId): JsonResponse
    {
        try {
            $client = Client::find($clientId);
            if (!$client) {
                return response()->json([
                    'message' => 'Client not found',
                    'status' => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }

            $services = Service::where('client_id', $clientId)
                ->orderByDesc('log_number')
                ->get()
                ->groupBy('car_id')
                ->map(fn($group) => $group->first());

            return response()->json($services->map(fn($service) => new ServiceResource($service)));

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching services',
                'error' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
