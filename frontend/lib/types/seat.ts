export type SeatStatus = 'available' | 'booked' | 'selected';

export interface Seat {
  number: string;
  status: SeatStatus;
}

export interface SeatMapResponse {
  total_capacity: number;
  booked_seats_count: number;
  available_seats_count: number;
  vehicle_type: string;
  seat_map: Record<string, SeatStatus>;
}

export interface SeatLayout {
  rows: number;
  columns: number;
  aislePositions: number[];
}

export interface SeatSelectionState {
  selectedSeats: string[];
  totalFare: number;
}