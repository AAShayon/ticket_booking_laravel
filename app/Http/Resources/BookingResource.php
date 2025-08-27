<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'user_id' => $this->user_id,
            'from_station' => $this->from_station,
            'to_station' => $this->to_station,
            'journey_date' => $this->journey_date,
            'seat_type' => $this->seat_type,
            'number_of_seats' => $this->number_of_seats,
            'total_fare' => $this->total_fare,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            'pnr' => $this->whenLoaded('pnr'),
            'route_id' => $this->whenLoaded('route', fn () => $this->route->id),
        ];
    }
}
