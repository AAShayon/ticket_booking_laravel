'use client';

import { useState } from 'react';
import { Clock, Users, MapPin } from 'lucide-react'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import type { Route } from '@/lib/types/route'
import { formatCurrency, formatTime } from '@/lib/utils'
import { useRouter } from 'next/navigation'

interface RouteCardProps {
  route: Route
  journeyDate?: string
}

export function RouteCard({ route, journeyDate }: RouteCardProps) {
  const router = useRouter();
  const [isBooking, setIsBooking] = useState(false);

  const handleBookNow = () => {
    if (!journeyDate) {
      console.error('Journey date is required for booking');
      return;
    }
    
    setIsBooking(true);
    router.push(`/booking/${route.id}?date=${journeyDate}`);
  };

  return (
    <Card className="hover:shadow-md transition-shadow">
      <CardContent className="p-6">
        <div className="flex items-center justify-between">
          <div className="flex-1 space-y-4">
            <div className="flex items-center justify-between">
              <div className="flex items-center space-x-4">
                <div className="text-center">
                  <div className="text-lg font-semibold">{formatTime(route.departure_time)}</div>
                  <div className="text-sm text-muted-foreground">{route.origin}</div>
                </div>
                <div className="flex-1 flex items-center space-x-2">
                  <div className="h-px bg-border flex-1"></div>
                  <div className="flex items-center space-x-1 text-sm text-muted-foreground">
                    <Clock className="h-3 w-3" />
                    <span>{route.duration}</span>
                  </div>
                  <div className="h-px bg-border flex-1"></div>
                </div>
                <div className="text-center">
                  <div className="text-lg font-semibold">{formatTime(route.arrival_time)}</div>
                  <div className="text-sm text-muted-foreground">{route.destination}</div>
                </div>
              </div>
            </div>

            <div className="flex items-center justify-between">
              <div className="space-y-1">
                <div className="font-medium">{route.operator.name}</div>
                <div className="text-sm text-muted-foreground">{route.vehicle.name}</div>
                <div className="flex items-center space-x-2">
                  <Badge variant="secondary">{route.vehicle.type}</Badge>
                  <Badge variant="outline">{route.time_of_day}</Badge>
                </div>
              </div>

              <div className="text-right space-y-2">
                <div className="text-2xl font-bold text-primary">
                  {formatCurrency(route.fare_per_seat)}
                </div>
                <div className="flex items-center text-sm text-muted-foreground">
                  <Users className="h-3 w-3 mr-1" />
                  <span>{route.available_seats} seats left</span>
                </div>
                <Button 
                  className="w-full" 
                  onClick={handleBookNow}
                  disabled={isBooking}
                >
                  {isBooking ? 'Redirecting...' : 'Book Now'}
                </Button>
              </div>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}