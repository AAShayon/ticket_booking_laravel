import * as z from 'zod';

export const PassengerDetailsSchema = z.object({
  name: z.string().min(2, 'Name must be at least 2 characters'),
  phone: z.string().regex(/^\+?[1-9]\d{1,14}$/, 'Invalid phone number format'),
  email: z.string().email('Invalid email').optional(),
});

export const BookingCreateSchema = z.object({
  route_id: z.number(),
  from_station: z.string().min(1),
  to_station: z.string().min(1),
  journey_date: z.string().min(1),
  seat_type: z.string().min(1),
  number_of_seats: z.number().min(1),
  seat_number: z.array(z.string()).min(1),
  total_fare: z.number().min(0),
  payment_method: z.string().min(1),
  payment_name: z.string().min(1),
  transaction_id: z.string().optional(),
});

export const SeatSelectionSchema = z.object({
  selectedSeats: z.array(z.string()).min(1, 'Please select at least one seat'),
});

export const PaymentMethodSchema = z.object({
  payment_method: z.string().min(1, 'Please select a payment method'),
  payment_name: z.string().min(1, 'Please select a payment method'),
});