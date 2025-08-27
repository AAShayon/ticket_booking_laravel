<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
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
            'operator_id' => $this->operator_id,
            'model_number' => $this->model_number,
            'type' => $this->type,
            'capacity' => $this->capacity,
            'image' => $this->image,
            'routes' => RouteResource::collection($this->whenLoaded('routes')),
        ];
    }
}
