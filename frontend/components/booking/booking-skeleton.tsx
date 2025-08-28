'use client';

import { Skeleton } from '@/components/ui/skeleton';

export function BookingSkeleton() {
  return (
    <div className="container py-8">
      <Skeleton className="h-10 w-32 mb-6" />
      
      <div className="flex flex-col items-center">
        <Skeleton className="h-8 w-64 mb-8" />
        
        <div className="mb-6 p-4 bg-gray-100 rounded-lg w-full max-w-md">
          <div className="flex justify-center mb-4">
            <Skeleton className="h-4 w-32 rounded" />
          </div>
          
          {/* Seat grid skeleton */}
          {[...Array(10)].map((_, rowIndex) => (
            <div key={rowIndex} className="flex items-center justify-center gap-2 mb-2">
              {[...Array(4)].map((_, colIndex) => (
                <Skeleton key={colIndex} className="w-12 h-12 rounded-md" />
              ))}
            </div>
          ))}
        </div>
        
        <div className="w-full max-w-md">
          <Skeleton className="h-6 w-32 mb-4" />
          
          <div className="grid grid-cols-2 gap-4">
            {[...Array(3)].map((_, index) => (
              <div key={index} className="flex items-center">
                <Skeleton className="w-4 h-4 rounded-sm mr-2" />
                <Skeleton className="h-4 w-20" />
              </div>
            ))}
          </div>
          
          <div className="mt-4 pt-4 border-t">
            {[...Array(3)].map((_, index) => (
              <div key={index} className="flex justify-between mb-2">
                <Skeleton className="h-4 w-24" />
                <Skeleton className="h-4 w-12" />
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}