<?php

namespace App\Http\Controllers\api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchClientRequest;
use App\Http\Resources\SearchResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;

class SearchAction extends Controller
{
    public function __invoke(SearchClientRequest $request): JsonResponse
    {
        $query = Client::query();
        if ($request->filled('name')) {
            $clients = $query->where('name', 'like', '%' . $request->name . '%')->get();

            if ($clients->count() > 1) {
                return response()->json([
                    'error' => 'Túl sok találat van az ügyfél név alapján! Kérlek pontosítsd a keresést.'
                ], 422);
            }
        }

        if ($request->filled('idcard')) {
            $query->where('card_number', $request->idcard);
        }

        $clients = $query->get();

        if ($clients->isEmpty()) {
            return response()->json(['error' => 'Nincs találat a megadott adatokkal!'], 404);
        }

        return response()->json([
            'clients' => SearchResource::collection($clients),
        ]);
    }
}
