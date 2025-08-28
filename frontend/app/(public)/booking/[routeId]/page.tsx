'use client';

import { useParams, useSearchParams } from 'next/navigation';
import { useAuth } from '@/contexts/auth-context';
import { useRouter } from 'next/navigation';
import { BookingWizard } from '@/components/booking/booking-wizard';

export default function BookingPage() {
  const params = useParams();
  const searchParams = useSearchParams();
  const router = useRouter();
  const { user, loading } = useAuth();
  
  const routeId = params.routeId ? parseInt(params.routeId as string, 10) : null;
  const journeyDate = searchParams.get('date');
  
  // Redirect to login if not authenticated
  if (!loading && !user) {
    router.push(`/login?returnUrl=/booking/${routeId}?date=${journeyDate}`);
    return null;
  }
  
  // Show loading state while checking auth
  if (loading) {
    return (
      <div className="flex items-center justify-center h-96">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
      </div>
    );
  }
  
  // Validate required parameters
  if (!routeId || isNaN(routeId)) {
    return (
      <div className="flex flex-col items-center justify-center h-96">
        <h2 className="text-2xl font-bold text-red-500 mb-4">Invalid Route</h2>
        <p className="text-muted-foreground mb-6">The route ID is missing or invalid.</p>
        <button 
          onClick={() => router.back()} 
          className="text-primary hover:underline"
        >
          Go Back
        </button>
      </div>
    );
  }
  
  if (!journeyDate) {
    return (
      <div className="flex flex-col items-center justify-center h-96">
        <h2 className="text-2xl font-bold text-red-500 mb-4">Missing Journey Date</h2>
        <p className="text-muted-foreground mb-6">Please select a journey date to continue.</p>
        <button 
          onClick={() => router.back()} 
          className="text-primary hover:underline"
        >
          Go Back
        </button>
      </div>
    );
  }
  
  return (
    <div className="min-h-screen bg-background">
      <BookingWizard routeId={routeId} />
    </div>
  );
}