'use client';

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { SEAT_COLORS, SEAT_TYPES } from '@/lib/constants/seats';

interface SeatLegendProps {
  totalCapacity: number;
  availableSeats: number;
  selectedSeats: number;
}

export function SeatLegend({ totalCapacity, availableSeats, selectedSeats }: SeatLegendProps) {
  return (
    <Card className="w-full max-w-md">
      <CardHeader>
        <CardTitle className="text-lg">Seat Legend</CardTitle>
      </CardHeader>
      <CardContent>
        <div className="grid grid-cols-2 gap-4">
          <div className="flex items-center">
            <div className={`w-4 h-4 rounded-sm mr-2 ${SEAT_COLORS.available}`}></div>
            <span className="text-sm">Available</span>
          </div>
          <div className="flex items-center">
            <div className={`w-4 h-4 rounded-sm mr-2 ${SEAT_COLORS.booked}`}></div>
            <span className="text-sm">Booked</span>
          </div>
          <div className="flex items-center">
            <div className={`w-4 h-4 rounded-sm mr-2 ${SEAT_COLORS.selected}`}></div>
            <span className="text-sm">Selected</span>
          </div>
        </div>
        
        <div className="mt-4 pt-4 border-t">
          <div className="flex justify-between text-sm">
            <span>Total Seats:</span>
            <span className="font-medium">{totalCapacity}</span>
          </div>
          <div className="flex justify-between text-sm">
            <span>Available:</span>
            <span className="font-medium">{availableSeats}</span>
          </div>
          <div className="flex justify-between text-sm">
            <span>Selected:</span>
            <span className="font-medium">{selectedSeats}</span>
          </div>
        </div>
        
        <div className="mt-4 pt-4 border-t">
          <h4 className="text-sm font-medium mb-2">Seat Types</h4>
          <div className="grid grid-cols-2 gap-2">
            {SEAT_TYPES.map((type) => (
              <div key={type.id} className="flex items-center">
                <div className="w-3 h-3 rounded-sm mr-2 bg-gray-300"></div>
                <span className="text-xs">{type.name}</span>
              </div>
            ))}
          </div>
        </div>
      </CardContent>
    </Card>
  );
}