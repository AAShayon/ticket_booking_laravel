export const VEHICLE_TYPES = [
  { value: 'ac', label: 'AC' },
  { value: 'non-ac', label: 'Non-AC' },
  { value: 'sleeper', label: 'Sleeper' },
  { value: 'hyundai', label: 'Hyundai' },
  { value: 'scania', label: 'Scania' },
  { value: 'volvo', label: 'Volvo' },
]

export const TIME_OF_DAY = [
  { value: 'morning', label: 'Morning (6AM - 12PM)' },
  { value: 'afternoon', label: 'Afternoon (12PM - 6PM)' },
  { value: 'evening', label: 'Evening (6PM - 10PM)' },
  { value: 'night', label: 'Night (10PM - 6AM)' },
]

export const POPULAR_ROUTES = [
  { origin: 'Dhaka', destination: 'Chittagong' },
  { origin: 'Dhaka', destination: 'Sylhet' },
  { origin: 'Dhaka', destination: 'Rajshahi' },
  { origin: 'Chittagong', destination: 'Cox\'s Bazar' },
]

export const DEFAULT_SEARCH_PARAMS = {
  page: 1,
  per_page: 10,
}

export const FARE_RANGE_LIMITS = {
  min: 0,
  max: 5000,
  step: 50,
}