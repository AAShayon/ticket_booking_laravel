'use client';

import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { PassengerDetails } from '@/lib/types/booking';
import { PAYMENT_METHODS } from '@/lib/constants/seats';

interface BookingSummaryProps {
  route: {
    id: number;
    from_station: string;
    to_station: string;
    departure_time: string;
    arrival_time: string;
    fare: number;
  };
  selectedSeats: string[];
  passengers: PassengerDetails[];
  totalFare: number;
  onEditSeats: () => void;
  onEditPassengers: () => void;
  onConfirmBooking: (paymentMethod: { id: string; name: string }) => void;
  onPaymentChange: (paymentMethod: { id: string; name: string }) => void;
  selectedPaymentMethod: { id: string; name: string } | null;
}

export function BookingSummary({
  route,
  selectedSeats,
  passengers,
  totalFare,
  onEditSeats,
  onEditPassengers,
  onConfirmBooking,
  onPaymentChange,
  selectedPaymentMethod,
}: BookingSummaryProps) {
  return (
    <Card className="w-full max-w-2xl">
      <CardHeader>
        <CardTitle>Booking Summary</CardTitle>
        <p className="text-sm text-muted-foreground">
          Please review your booking details before confirming
        </p>
      </CardHeader>
      <CardContent className="space-y-6">
        <div>
          <div className="flex justify-between items-center">
            <h3 className="text-lg font-semibold">Journey Details</h3>
            <Button variant="outline" size="sm" onClick={onEditSeats}>
              Edit
            </Button>
          </div>
          <div className="mt-2 p-4 bg-muted rounded-lg">
            <div className="flex justify-between">
              <div>
                <p className="font-medium">{route.from_station}</p>
                <p className="text-sm text-muted-foreground">{route.departure_time}</p>
              </div>
              <div className="text-center">
                <p className="text-sm text-muted-foreground">to</p>
              </div>
              <div className="text-right">
                <p className="font-medium">{route.to_station}</p>
                <p className="text-sm text-muted-foreground">{route.arrival_time}</p>
              </div>
            </div>
          </div>
        </div>

        <div>
          <div className="flex justify-between items-center">
            <h3 className="text-lg font-semibold">Selected Seats</h3>
            <Button variant="outline" size="sm" onClick={onEditSeats}>
              Edit
            </Button>
          </div>
          <div className="mt-2 flex flex-wrap gap-2">
            {selectedSeats.map((seat) => (
              <Badge key={seat} variant="secondary" className="text-sm py-2 px-3">
                {seat}
              </Badge>
            ))}
          </div>
        </div>

        <div>
          <div className="flex justify-between items-center">
            <h3 className="text-lg font-semibold">Passenger Details</h3>
            <Button variant="outline" size="sm" onClick={onEditPassengers}>
              Edit
            </Button>
          </div>
          <div className="mt-2 space-y-3">
            {passengers.map((passenger, index) => (
              <div key={index} className="p-3 border rounded-lg">
                <p className="font-medium">Passenger {index + 1}</p>
                <p className="text-sm">Name: {passenger.name}</p>
                <p className="text-sm">Phone: {passenger.phone}</p>
                {passenger.email && <p className="text-sm">Email: {passenger.email}</p>}
              </div>
            ))}
          </div>
        </div>

        <Separator />

        <div>
          <h3 className="text-lg font-semibold">Fare Details</h3>
          <div className="mt-2 space-y-2">
            <div className="flex justify-between">
              <span>Base Fare ({selectedSeats.length} seat{selectedSeats.length > 1 ? 's' : ''})</span>
              <span>₹{route.fare * selectedSeats.length}</span>
            </div>
            <div className="flex justify-between">
              <span>Taxes & Fees</span>
              <span>₹{(totalFare - route.fare * selectedSeats.length).toFixed(2)}</span>
            </div>
            <Separator />
            <div className="flex justify-between font-semibold text-lg">
              <span>Total Amount</span>
              <span>₹{totalFare.toFixed(2)}</span>
            </div>
          </div>
        </div>

        <div>
          <h3 className="text-lg font-semibold">Payment Method</h3>
          <div className="mt-2 grid grid-cols-2 gap-3">
            {PAYMENT_METHODS.map((method) => (
              <Button
                key={method.id}
                variant={selectedPaymentMethod?.id === method.id ? "default" : "outline"}
                className="h-16 flex flex-col items-center justify-center"
                onClick={() => onPaymentChange(method)}
              >
                <span>{method.name}</span>
              </Button>
            ))}
          </div>
        </div>
      </CardContent>
      <CardFooter className="flex justify-end">
        <Button 
          onClick={() => selectedPaymentMethod && onConfirmBooking(selectedPaymentMethod)} 
          size="lg"
          disabled={!selectedPaymentMethod}
        >
          Confirm Booking
        </Button>
      </CardFooter>
    </Card>
  );
}