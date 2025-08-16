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
            'route_id' => $this->id,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'fare_per_seat' => $this->fare_per_seat,
            'departure_time' => $this->departure_time,
            'estimated_travel_time' => $this->estimated_travel_time,
            'vehicle_number' => $this->vehicle_number,
            'time_of_day' => $this->time_of_day,
            'vehicle' => new \App\Http\Resources\VehicleResource($this->whenLoaded('vehicle')),
            'operator_name' => $this->whenLoaded('operator', function () {
                return $this->operator->name;
            }),
        ];
    }
}
