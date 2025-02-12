<?php

namespace App\Http\Controllers\api\ClientWithCarAndServiceAction;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientWithCarAndServiceResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ListAction extends Controller
{
    public function __invoke(): JsonResponse|AnonymousResourceCollection
    {
        try {
            $clients = Client::with(['cars', 'services'])->get();

            if ($clients->isEmpty()) {
                return response()->json([
                    'message' => 'No clients found',
                    'status' => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }

            return ClientWithCarAndServiceResource::collection($clients);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching clients',
                'error' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
