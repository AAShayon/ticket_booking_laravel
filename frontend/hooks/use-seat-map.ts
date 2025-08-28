'use client';

import { useQuery } from '@tanstack/react-query';
import { getSeatMap } from '@/lib/api/bookings';

export const useSeatMap = (routeId: number, journeyDate: string) => {
  return useQuery({
    queryKey: ['seatMap', routeId, journeyDate],
    queryFn: () => getSeatMap(routeId, journeyDate),
    staleTime: 30000, // 30 seconds
    refetchInterval: 10000, // Refetch every 10 seconds for more up-to-date availability
  });
};