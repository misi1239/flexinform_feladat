<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'car_id' => $this->car_id,
            'type' => $this->type,
            'registered' => $this->registered,
            'ownbrand' => $this->ownbrand,
            'accident' => $this->accidents,
        ];
    }
}
