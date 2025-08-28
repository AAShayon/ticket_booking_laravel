export interface PassengerDetails {
  name: string;
  phone: string;
  email?: string;
}

export interface BookingCreatePayload {
  route_id: number;
  from_station: string;
  to_station: string;
  journey_date: string;
  seat_type: string;
  number_of_seats: number;
  seat_number: string[];
  total_fare: number;
  payment_method: string;
  payment_name: string;
  transaction_id?: string;
}

export interface BookingResponse {
  id: number;
  pnr: string;
  route_id: number;
  from_station: string;
  to_station: string;
  journey_date: string;
  seat_type: string;
  number_of_seats: number;
  seat_numbers: string[];
  total_fare: number;
  payment_method: string;
  payment_name: string;
  transaction_id?: string;
  status: string;
  created_at: string;
}

export enum BookingStep {
  SEAT_SELECTION = 'seat-selection',
  PASSENGER_DETAILS = 'passenger-details',
  BOOKING_SUMMARY = 'booking-summary',
  CONFIRMATION = 'confirmation',
}