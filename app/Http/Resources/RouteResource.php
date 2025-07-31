<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
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
            'origin' => $this->origin,
            'destination' => $this->destination,
            'fare' => $this->fare,
            'estimated_travel_time' => $this->estimated_travel_time,
            'vehicle_number' => $this->vehicle_number,
            'time_of_day' => $this->time_of_day,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'operator' => new OperatorResource($this->whenLoaded('operator')),
        ];
    }
}
