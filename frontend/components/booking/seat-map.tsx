'use client';

import { useState } from 'react';
import { SeatComponent } from './seat-component';
import { SeatLegend } from './seat-legend';
import { SEAT_COLORS, VEHICLE_LAYOUTS, DEFAULT_SEAT_LAYOUT } from '@/lib/constants/seats';
import { SeatStatus } from '@/lib/types/seat';
import { generateSeatLayout } from '@/lib/utils';

interface SeatMapProps {
  seatMap: Record<string, SeatStatus>;
  totalCapacity: number;
  availableSeatsCount: number;
  vehicleType: string;
  onSeatSelect: (seatNumber: string) => void;
  selectedSeats: string[];
}

export function SeatMap({ seatMap, totalCapacity, availableSeatsCount, vehicleType, onSeatSelect, selectedSeats }: SeatMapProps) {
  const layout = VEHICLE_LAYOUTS[vehicleType] || DEFAULT_SEAT_LAYOUT;
  const { rows, columns, aislePositions } = layout;

  // Generate seat grid using the utility function
  const seatLayout = generateSeatLayout(totalCapacity, rows, columns, aislePositions);

  const renderSeats = () => {
    return seatLayout.map((rowLayout) => {
      const rowSeats = rowLayout.seats.map((seat) => {
        if (seat.type === 'aisle') {
          return <div key={`aisle-${rowLayout.row}-${seat.position}`} className="w-8" />;
        }
        
        const seatNumber = seat.number;
        if (!seatNumber) {
          return null;
        }
        
        const status: SeatStatus = seatMap.hasOwnProperty(seatNumber) ? seatMap[seatNumber] : 'available';
        const isSelected = selectedSeats.includes(seatNumber);
        
        return (
          <SeatComponent
            key={seatNumber}
            seatNumber={seatNumber}
            status={status}
            isSelected={isSelected}
            onClick={onSeatSelect}
          />
        );
      });
      
      return (
        <div key={rowLayout.row} className="flex items-center justify-center gap-2 mb-2">
          {rowSeats}
        </div>
      );
    });
  };

  return (
    <div className="w-full">
      <div className="mb-6">
        <h3 className="text-lg font-semibold mb-2">Select Seats</h3>
        <p className="text-sm text-muted-foreground">Click on available seats to select them</p>
      </div>
      
      <div className="flex flex-col items-center">
        <div className="mb-6 p-4 bg-gray-100 rounded-lg w-full max-w-md">
          <div className="flex justify-center mb-4">
            <div className="bg-gray-300 h-2 w-32 rounded"></div>
          </div>
          <div className="overflow-x-auto">
            {renderSeats()}
          </div>
        </div>
        
        <SeatLegend 
          totalCapacity={totalCapacity}
          availableSeats={availableSeatsCount}
          selectedSeats={selectedSeats.length}
        />
      </div>
    </div>
  );
}