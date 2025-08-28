'use client';

import { cn } from '@/lib/utils';
import { SeatStatus } from '@/lib/types/seat';
import { Button } from '@/components/ui/button';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';

interface SeatComponentProps {
  seatNumber: string;
  status: SeatStatus;
  isSelected: boolean;
  onClick: (seatNumber: string) => void;
}

export function SeatComponent({ seatNumber, status, isSelected, onClick }: SeatComponentProps) {
  const isBooked = status === 'booked';
  const isAvailable = status === 'available';
  const bgColor = isSelected ? 'bg-blue-500' : 
                  status === 'booked' ? 'bg-red-500' : 
                  status === 'available' ? 'bg-green-500' : 'bg-gray-300';

  return (
    <TooltipProvider>
      <Tooltip>
        <TooltipTrigger asChild>
          <Button
            variant="ghost"
            className={cn(
              'w-12 h-12 p-0 rounded-md text-xs font-medium',
              bgColor,
              isBooked ? 'cursor-not-allowed opacity-50' : 'hover:opacity-80',
              isSelected && 'ring-2 ring-blue-300'
            )}
            onClick={() => !isBooked && onClick(seatNumber)}
            disabled={isBooked}
            aria-label={`Seat ${seatNumber} - ${status}`}
          >
            {seatNumber.replace('S', '')}
          </Button>
        </TooltipTrigger>
        <TooltipContent>
          <p>Seat {seatNumber} - {status.charAt(0).toUpperCase() + status.slice(1)}</p>
        </TooltipContent>
      </Tooltip>
    </TooltipProvider>
  );
}