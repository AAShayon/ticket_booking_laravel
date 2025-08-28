'use client';

import { useState } from 'react';

export const useSeatSelection = () => {
  const [selectedSeats, setSelectedSeats] = useState<string[]>([]);

  const toggleSeatSelection = (seatNumber: string) => {
    setSelectedSeats(prev => {
      if (prev.includes(seatNumber)) {
        return prev.filter(seat => seat !== seatNumber);
      } else {
        return [...prev, seatNumber];
      }
    });
  };

  const clearSelection = () => {
    setSelectedSeats([]);
  };

  const isSelected = (seatNumber: string) => {
    return selectedSeats.includes(seatNumber);
  };
  
  const deselectSeats = (seatNumbers: string[]) => {
    setSelectedSeats(prev => prev.filter(seat => !seatNumbers.includes(seat)));
  };

  return {
    selectedSeats,
    toggleSeatSelection,
    clearSelection,
    isSelected,
    deselectSeats
  };
};