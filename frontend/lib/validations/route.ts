import { z } from "zod"

export const RouteSearchSchema = z.object({
  origin: z.string().min(1, "Origin is required"),
  destination: z.string().min(1, "Destination is required"),
  journey_date: z.string().min(1, "Journey date is required"),
  minFare: z.number().optional(),
  maxFare: z.number().optional(),
  type: z.enum(['ac', 'non-ac', 'sleeper', 'hyundai', 'scania', 'volvo']).optional(),
  time_of_day: z.enum(['morning', 'afternoon', 'evening', 'night']).optional(),
})

export type RouteSearchFormData = z.infer<typeof RouteSearchSchema>