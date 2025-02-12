<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientWithCarAndServiceResource extends JsonResource
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
            'name' => $this->name,
            'idcard' => $this->card_number,
            'cars' => CarResource::collection($this->whenLoaded('cars')),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
        ];
    }
}
