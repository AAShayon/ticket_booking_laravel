import { apiClient } from '@/lib/api/client';
import { BookingCreatePayload, BookingResponse } from '@/lib/types/booking';
import { SeatMapResponse } from '@/lib/types/seat';

export const getSeatMap = async (routeId: number, journeyDate: string): Promise<SeatMapResponse> => {
  const response = await apiClient.get(`/routes/${routeId}/seat-map?journey_date=${journeyDate}`);
  return response.data;
};

export const createBooking = async (bookingData: BookingCreatePayload): Promise<BookingResponse> => {
  const response = await apiClient.post('/bookings', bookingData);
  return response.data;
};

export const getUserBookings = async (): Promise<BookingResponse[]> => {
  const response = await apiClient.get('/bookings');
  return response.data;
};