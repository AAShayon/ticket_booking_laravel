'use client';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { CheckCircle } from 'lucide-react';
import { PassengerDetails } from '@/lib/types/booking';

interface BookingConfirmationProps {
  pnr: string;
  route: {
    from_station: string;
    to_station: string;
    departure_time: string;
    arrival_time: string;
  };
  selectedSeats: string[];
  passengers: PassengerDetails[];
  totalFare: number;
  onDownloadTicket: () => void;
  onGoToDashboard: () => void;
}

export function BookingConfirmation({
  pnr,
  route,
  selectedSeats,
  passengers,
  totalFare,
  onDownloadTicket,
  onGoToDashboard,
}: BookingConfirmationProps) {
  return (
    <Card className="w-full max-w-2xl">
      <CardHeader className="text-center">
        <div className="flex justify-center mb-4">
          <CheckCircle className="h-16 w-16 text-green-500" />
        </div>
        <CardTitle className="text-2xl">Booking Confirmed!</CardTitle>
        <p className="text-muted-foreground">
          Your ticket has been successfully booked
        </p>
      </CardHeader>
      <CardContent className="space-y-6">
        <div className="text-center p-4 bg-green-50 rounded-lg">
          <p className="text-sm text-muted-foreground">Booking Reference</p>
          <p className="text-2xl font-bold text-green-700">{pnr}</p>
        </div>

        <div>
          <h3 className="text-lg font-semibold">Journey Details</h3>
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
          <h3 className="text-lg font-semibold">Seat Numbers</h3>
          <div className="mt-2 flex flex-wrap gap-2">
            {selectedSeats.map((seat) => (
              <Badge key={seat} variant="secondary" className="text-sm py-2 px-3">
                {seat}
              </Badge>
            ))}
          </div>
        </div>

        <div>
          <h3 className="text-lg font-semibold">Passenger Details</h3>
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

        <div className="flex justify-between font-semibold text-lg">
          <span>Total Amount Paid</span>
          <span>₹{totalFare.toFixed(2)}</span>
        </div>
      </CardContent>
      <CardFooter className="flex flex-col sm:flex-row gap-3 justify-between">
        <Button variant="outline" onClick={onDownloadTicket} className="w-full sm:w-auto">
          Download Ticket
        </Button>
        <Button onClick={onGoToDashboard} className="w-full sm:w-auto">
          Go to Dashboard
        </Button>
      </CardFooter>
    </Card>
  );
}