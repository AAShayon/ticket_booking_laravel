export interface Route {
  id: number
  origin: string
  destination: string
  fare_per_seat: number
  departure_time: string
  arrival_time: string
  duration: string
  time_of_day: 'morning' | 'afternoon' | 'evening' | 'night'
  available_seats: number
  total_seats: number
  vehicle: {
    id: number
    name: string
    type: string
    registration_number: string
    image?: string
  }
  operator: {
    id: number
    name: string
  }
}

export interface RouteSearchParams {
  origin: string
  destination: string
  journey_date: string
  minFare?: number
  maxFare?: number
  type?: string
  time_of_day?: string
  page?: number
  per_page?: number
}

export interface RouteSearchResponse {
  data: Route[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
}

export type VehicleType = 'ac' | 'non-ac' | 'sleeper' | 'hyundai' | 'scania' | 'volvo'
export type TimeOfDay = 'morning' | 'afternoon' | 'evening' | 'night'