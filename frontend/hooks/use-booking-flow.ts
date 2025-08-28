'use client';

import { useState } from 'react';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { createBooking } from '@/lib/api/bookings';
import { BookingCreatePayload, BookingResponse, PassengerDetails } from '@/lib/types/booking';

export const useBookingFlow = () => {
  const queryClient = useQueryClient();
  const [passengers, setPassengers] = useState<PassengerDetails[]>([]);
  const [bookingData, setBookingData] = useState<BookingCreatePayload | null>(null);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  const { mutate, isPending, isError, error, isSuccess, data } = useMutation({
    mutationFn: (bookingPayload: BookingCreatePayload) => createBooking(bookingPayload),
    onSuccess: (data) => {
      // Handle successful booking
      console.log('Booking successful:', data);
      setErrorMessage(null);
    },
    onError: (error: any) => {
      // Handle booking error
      console.error('Booking failed:', error);
      
      // Set error message for display
      let message = 'Booking failed. Please try again.';
      if (error.response?.status === 409) {
        message = 'One or more seats are no longer available. Please select different seats.';
      } else if (error.response?.status === 422) {
        message = 'There was an issue with your booking. Please check your details and try again.';
      } else if (error.response?.data?.message) {
        message = error.response.data.message;
      }
      
      setErrorMessage(message);
      
      // If it's a seat conflict error (409 or 422), invalidate the seat map to refetch
      if (error.response?.status === 409 || error.response?.status === 422) {
        // Invalidate seat map query to refetch latest data
        queryClient.invalidateQueries({ queryKey: ['seatMap'] });
      }
    }
  });

  const createBookingHandler = (bookingPayload: BookingCreatePayload) => {
    setErrorMessage(null);
    mutate(bookingPayload);
  };

  const clearError = () => {
    setErrorMessage(null);
  };

  return {
    passengers,
    setPassengers,
    bookingData,
    setBookingData,
    createBooking: createBookingHandler,
    isBookingLoading: isPending,
    bookingError: isError ? error : null,
    errorMessage,
    clearError,
    bookingSuccess: isSuccess ? data : null
  };
};