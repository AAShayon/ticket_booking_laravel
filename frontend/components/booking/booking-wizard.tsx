'use client';

import { useState, useEffect } from 'react';
import { useSearchParams, useRouter } from 'next/navigation';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { BookingStep } from '@/lib/types/booking';
import { useSeatMap } from '@/hooks/use-seat-map';
import { useSeatSelection } from '@/hooks/use-seat-selection';
import { useBookingFlow } from '@/hooks/use-booking-flow';
import { SeatMap } from './seat-map';
import { PassengerForm } from './passenger-form';
import { BookingSummary } from './booking-summary';
import { BookingConfirmation } from './booking-confirmation';
import { BookingSkeleton } from './booking-skeleton';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-react';
import { calculateTotalFare, validateSeatSelection } from '@/lib/utils';
import { BookingCreateSchema } from '@/lib/validations/booking';
import { routesApi } from '@/lib/api/routes';

interface BookingWizardProps {
  routeId: number;
}

export function BookingWizard({ routeId }: BookingWizardProps) {
  const router = useRouter();
  const searchParams = useSearchParams();
  const journeyDate = searchParams.get('date') || new Date().toISOString().split('T')[0];
  
  const [activeStep, setActiveStep] = useState<BookingStep>(BookingStep.SEAT_SELECTION);
  const [selectedPaymentMethod, setSelectedPaymentMethod] = useState<{ id: string; name: string } | null>(null);
  const [routeData, setRouteData] = useState<any>(null);
  const [isLoadingRoute, setIsLoadingRoute] = useState(true);
  
  const { 
    data: seatMapData, 
    isLoading: isSeatMapLoading, 
    isError: isSeatMapError 
  } = useSeatMap(routeId, journeyDate);
  
  const { 
    selectedSeats, 
    toggleSeatSelection, 
    clearSelection,
    deselectSeats
  } = useSeatSelection();
  
  const { 
    passengers, 
    setPassengers,
    bookingData,
    setBookingData,
    createBooking,
    isBookingLoading,
    bookingSuccess,
    bookingError,
    errorMessage,
    clearError
  } = useBookingFlow();
  
  // Fetch route data
  useEffect(() => {
    const fetchRouteData = async () => {
      try {
        // In a real implementation, we would fetch the specific route data
        // For now, we'll mock the data or use default values
        setRouteData({
          id: routeId,
          origin: 'Dhaka',
          destination: 'Chittagong',
          fare_per_seat: 500,
          departure_time: '08:00 AM',
          arrival_time: '12:00 PM'
        });
      } catch (error) {
        console.error('Failed to fetch route data:', error);
      } finally {
        setIsLoadingRoute(false);
      }
    };
    
    fetchRouteData();
  }, [routeId]);
  
  // Handle booking confirmation
  useEffect(() => {
    if (bookingSuccess) {
      setActiveStep(BookingStep.CONFIRMATION);
    }
  }, [bookingSuccess]);
  
  // Reconcile selected seats with seat map updates
  useEffect(() => {
    if (seatMapData && selectedSeats.length > 0) {
      // Check if any selected seats are now booked
      const bookedSelectedSeats = selectedSeats.filter(seat => 
        seatMapData.seat_map[seat] === 'booked'
      );
      
      // If any selected seats are now booked, deselect them
      if (bookedSelectedSeats.length > 0) {
        // In a real implementation, we would show a toast notification
        console.log(`The following seats are no longer available: ${bookedSelectedSeats.join(', ')}`);
        // Deselect the booked seats
        deselectSeats(bookedSelectedSeats);
      }
    }
  }, [seatMapData, selectedSeats, deselectSeats]);
  
  // Navigate to passenger details step
  const handleContinueToPassengerDetails = () => {
    if (selectedSeats.length > 0) {
      // Validate seat selection
      const { isValid, message } = validateSeatSelection(selectedSeats, 6);
      if (!isValid) {
        // In a real implementation, we would show a toast notification
        console.error(message);
        return;
      }
      setActiveStep(BookingStep.PASSENGER_DETAILS);
    }
  };
  
  // Navigate to booking summary step
  const handleContinueToSummary = (passengerData: { passengers: any[] }) => {
    setPassengers(passengerData.passengers);
    setActiveStep(BookingStep.BOOKING_SUMMARY);
  };
  
  // Handle booking confirmation
  const handleConfirmBooking = (paymentMethod: { id: string; name: string }) => {
    if (seatMapData && routeData) {
      const baseFare = routeData.fare_per_seat || 500;
      const totalFare = calculateTotalFare(baseFare, selectedSeats.length, 50); // 50 for taxes/fees
      
      const bookingPayload = {
        route_id: routeId,
        from_station: routeData.origin || 'Station A',
        to_station: routeData.destination || 'Station B',
        journey_date: journeyDate,
        seat_type: 'standard',
        number_of_seats: selectedSeats.length,
        seat_number: selectedSeats,
        total_fare: totalFare,
        payment_method: paymentMethod.id,
        payment_name: paymentMethod.name
      };
      
      // Validate the booking payload
      const validationResult = BookingCreateSchema.safeParse(bookingPayload);
      if (!validationResult.success) {
        console.error('Booking validation failed:', validationResult.error);
        return;
      }
      
      setBookingData(bookingPayload);
      createBooking(bookingPayload);
    }
  };
  
  // Handle going back to dashboard
  const handleGoToDashboard = () => {
    router.push('/dashboard');
  };
  
  // Handle payment method change
  const handlePaymentChange = (paymentMethod: { id: string; name: string }) => {
    setSelectedPaymentMethod(paymentMethod);
  };
  
  if (isSeatMapLoading || isLoadingRoute) {
    return <BookingSkeleton />;
  }
  
  if (isSeatMapError) {
    return (
      <div className="flex flex-col items-center justify-center h-96">
        <h2 className="text-2xl font-bold text-red-500 mb-4">Error Loading Seat Map</h2>
        <p className="text-muted-foreground mb-6">Unable to fetch seat availability. Please try again later.</p>
        <Button onClick={() => router.back()}>
          <ArrowLeft className="mr-2 h-4 w-4" />
          Go Back
        </Button>
      </div>
    );
  }
  
  if (!seatMapData) {
    return (
      <div className="flex flex-col items-center justify-center h-96">
        <h2 className="text-2xl font-bold mb-4">No Data Available</h2>
        <p className="text-muted-foreground mb-6">Seat map data is not available for this route.</p>
        <Button onClick={() => router.back()}>
          <ArrowLeft className="mr-2 h-4 w-4" />
          Go Back
        </Button>
      </div>
    );
  }
  
  // Calculate total fare
  const baseFare = routeData?.fare_per_seat || 500;
  const totalFare = calculateTotalFare(baseFare, selectedSeats.length, 50); // 50 for taxes/fees
  
  return (
    <div className="container py-8">
      <div className="mb-6">
        <Button variant="ghost" onClick={() => router.back()}>
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back to Search
        </Button>
      </div>
      
      <Tabs value={activeStep} className="w-full">
        <TabsList className="grid w-full grid-cols-4 mb-8">
          <TabsTrigger value={BookingStep.SEAT_SELECTION}>1. Select Seats</TabsTrigger>
          <TabsTrigger value={BookingStep.PASSENGER_DETAILS}>2. Passenger Details</TabsTrigger>
          <TabsTrigger value={BookingStep.BOOKING_SUMMARY}>3. Review & Pay</TabsTrigger>
          <TabsTrigger value={BookingStep.CONFIRMATION}>4. Confirmation</TabsTrigger>
        </TabsList>
        
        <TabsContent value={BookingStep.SEAT_SELECTION}>
          <div className="flex flex-col items-center">
            <SeatMap
              seatMap={seatMapData.seat_map}
              totalCapacity={seatMapData.total_capacity}
              availableSeatsCount={seatMapData.available_seats_count}
              vehicleType={seatMapData.vehicle_type}
              onSeatSelect={toggleSeatSelection}
              selectedSeats={selectedSeats}
            />
            <div className="mt-8 flex gap-4">
              <Button
                onClick={clearSelection}
                variant="outline"
              >
                Clear Selection
              </Button>
              <Button
                onClick={handleContinueToPassengerDetails}
                disabled={selectedSeats.length === 0}
              >
                Continue ({selectedSeats.length} seat{selectedSeats.length !== 1 ? 's' : ''} selected)
              </Button>
            </div>
          </div>
        </TabsContent>
        
        <TabsContent value={BookingStep.PASSENGER_DETAILS}>
          <PassengerForm
            numberOfSeats={selectedSeats.length}
            onSubmit={handleContinueToSummary}
            defaultValues={{ passengers }}
          />
        </TabsContent>
        
        <TabsContent value={BookingStep.BOOKING_SUMMARY}>
          <BookingSummary
            route={{
              id: routeId,
              from_station: routeData?.origin || 'Station A',
              to_station: routeData?.destination || 'Station B',
              departure_time: routeData?.departure_time || '08:00 AM',
              arrival_time: routeData?.arrival_time || '12:00 PM',
              fare: baseFare
            }}
            selectedSeats={selectedSeats}
            passengers={passengers}
            totalFare={totalFare}
            onEditSeats={() => setActiveStep(BookingStep.SEAT_SELECTION)}
            onEditPassengers={() => setActiveStep(BookingStep.PASSENGER_DETAILS)}
            onConfirmBooking={handleConfirmBooking}
            onPaymentChange={handlePaymentChange}
            selectedPaymentMethod={selectedPaymentMethod}
          />
        </TabsContent>
        
        <TabsContent value={BookingStep.CONFIRMATION}>
          {errorMessage && (
            <div className="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
              {errorMessage}
            </div>
          )}
          {bookingSuccess && bookingData && (
            <BookingConfirmation
              pnr={bookingSuccess.pnr}
              route={{
                from_station: routeData?.origin || 'Station A',
                to_station: routeData?.destination || 'Station B',
                departure_time: routeData?.departure_time || '08:00 AM',
                arrival_time: routeData?.arrival_time || '12:00 PM'
              }}
              selectedSeats={selectedSeats}
              passengers={passengers}
              totalFare={totalFare}
              onDownloadTicket={() => console.log('Download ticket')}
              onGoToDashboard={handleGoToDashboard}
            />
          )}
        </TabsContent>
      </Tabs>
    </div>
  );
}