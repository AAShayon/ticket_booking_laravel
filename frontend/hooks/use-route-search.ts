'use client'

import { useQuery } from '@tanstack/react-query'
import { useSearchParams } from 'next/navigation'
import { routesApi } from '@/lib/api/routes'
import type { RouteSearchParams } from '@/lib/types/route'
import { DEFAULT_SEARCH_PARAMS } from '@/lib/constants/routes'

export function useRouteSearch() {
  const searchParams = useSearchParams()

  const queryParams: RouteSearchParams = {
    origin: searchParams.get('origin') || '',
    destination: searchParams.get('destination') || '',
    journey_date: searchParams.get('journey_date') || '',
    minFare: searchParams.get('minFare') ? parseInt(searchParams.get('minFare')!) : undefined,
    maxFare: searchParams.get('maxFare') ? parseInt(searchParams.get('maxFare')!) : undefined,
    type: searchParams.get('type') || undefined,
    time_of_day: searchParams.get('time_of_day') || undefined,
    page: parseInt(searchParams.get('page') || DEFAULT_SEARCH_PARAMS.page.toString()),
    per_page: parseInt(searchParams.get('per_page') || DEFAULT_SEARCH_PARAMS.per_page.toString()),
  }

  return useQuery({
    queryKey: ['routes', 'search', queryParams],
    queryFn: () => routesApi.searchRoutes(queryParams),
    enabled: !!(queryParams.origin && queryParams.destination && queryParams.journey_date),
    staleTime: 5 * 60 * 1000, // 5 minutes
  })
}