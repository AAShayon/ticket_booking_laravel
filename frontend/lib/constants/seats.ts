export const SEAT_TYPES = [
  { id: 'standard', name: 'Standard' },
  { id: 'premium', name: 'Premium' },
  { id: 'luxury', name: 'Luxury' },
];

export const VEHICLE_LAYOUTS: Record<string, { rows: number; columns: number; aislePositions: number[] }> = {
  'AC Bus': { rows: 10, columns: 4, aislePositions: [2] },
  'Non-AC Bus': { rows: 10, columns: 4, aislePositions: [2] },
  'Sleeper Bus': { rows: 5, columns: 4, aislePositions: [2] },
  'default': { rows: 10, columns: 4, aislePositions: [2] },
};

export const SEAT_COLORS: Record<string, string> = {
  available: 'bg-green-500',
  booked: 'bg-red-500',
  selected: 'bg-blue-500',
};

export const DEFAULT_SEAT_LAYOUT = {
  rows: 10,
  columns: 4,
  aislePositions: [2],
};

export const PAYMENT_METHODS = [
  { id: 'cash', name: 'Cash' },
  { id: 'online', name: 'Online Payment' },
];