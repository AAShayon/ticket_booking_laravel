import { type ClassValue, clsx } from "clsx"
import { twMerge } from "tailwind-merge"

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export const getAuthToken = () => {
  if (typeof window !== 'undefined') {
    return localStorage.getItem('auth-token')
  }
  return null
}

export const setAuthToken = (token: string) => {
  if (typeof window !== 'undefined') {
    localStorage.setItem('auth-token', token)
    const isProduction = process.env.NODE_ENV === 'production'
    const secure = isProduction ? '; Secure' : ''
    document.cookie = `auth-token=${token}; Max-Age=86400; Path=/; SameSite=Lax${secure}`
  }
}

export const removeAuthToken = () => {
  if (typeof window !== 'undefined') {
    localStorage.removeItem('auth-token')
    document.cookie = 'auth-token=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT'
  }
}

export const setUserRole = (role: string) => {
  if (typeof window !== 'undefined') {
    localStorage.setItem('user-role', role)
    const isProduction = process.env.NODE_ENV === 'production'
    const secure = isProduction ? '; Secure' : ''
    document.cookie = `user-role=${role}; Max-Age=86400; Path=/; SameSite=Lax${secure}`
  }
}

export const removeUserRole = () => {
  if (typeof window !== 'undefined') {
    localStorage.removeItem('user-role')
    document.cookie = 'user-role=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT'
  }
}

export const formatCurrency = (amount: number) => {
  return `৳${amount.toLocaleString()}`
}

export const formatTime = (time: string) => {
  return new Date(`2000-01-01T${time}`).toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })
}

export const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

export const debounce = <T extends (...args: any[]) => any>(
  func: T,
  wait: number
): ((...args: Parameters<T>) => void) => {
  let timeout: NodeJS.Timeout
  return (...args: Parameters<T>) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => func(...args), wait)
  }
}

// Booking utility functions
export const calculateTotalFare = (baseFare: number, numberOfSeats: number, taxesAndFees: number = 0) => {
  return (baseFare * numberOfSeats) + taxesAndFees;
};

export const generateSeatLayout = (totalCapacity: number, rows: number, columns: number, aislePositions: number[]) => {
  const layout = [];
  let seatIndex = 0;
  
  for (let row = 0; row < rows; row++) {
    const rowSeats = [];
    
    for (let col = 0; col < columns; col++) {
      // Check if this position is an aisle
      if (aislePositions.includes(col)) {
        rowSeats.push({ type: 'aisle', position: col });
        continue;
      }
      
      // Add seat if within capacity
      if (seatIndex < totalCapacity) {
        rowSeats.push({ 
          type: 'seat', 
          number: `S${seatIndex + 1}`,
          position: col 
        });
        seatIndex++;
      }
    }
    
    layout.push({
      row: row,
      seats: rowSeats
    });
  }
  
  return layout;
};

export const validateSeatSelection = (selectedSeats: string[], maxSeats: number = 6) => {
  if (selectedSeats.length === 0) {
    return { isValid: false, message: 'Please select at least one seat' };
  }
  
  if (selectedSeats.length > maxSeats) {
    return { isValid: false, message: `You can select a maximum of ${maxSeats} seats` };
  }
  
  return { isValid: true, message: '' };
};

export const formatBookingReference = (pnr: string) => {
  // Format PNR as XXX-XXX-XXX if not already formatted
  if (pnr.includes('-')) return pnr;
  
  const formatted = pnr.match(/.{1,3}/g)?.join('-') || pnr;
  return formatted.toUpperCase();
};

export const generateSeatNumber = (index: number) => {
  return `S${index}`;
};

export const getBookingStatusColor = (status: string) => {
  switch (status.toLowerCase()) {
    case 'confirmed':
      return 'bg-green-100 text-green-800';
    case 'pending':
      return 'bg-yellow-100 text-yellow-800';
    case 'cancelled':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
};