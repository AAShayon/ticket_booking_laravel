'use client'

import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { RouteCard } from './route-card'
import { SearchSkeleton } from './search-skeleton'
import { useRouteSearch } from '@/hooks/use-route-search'
import { useSearchParams } from '@/hooks/use-search-params'

export function ResultsList() {
  const { searchParams, updateParam } = useSearchParams()
  const { data, isLoading, error } = useRouteSearch()
  
  // Extract journey date from search params
  const journeyDate = searchParams.get('date')

  if (isLoading) {
    return <SearchSkeleton />
  }

  if (error) {
    return (
      <div className="text-center py-8">
        <p className="text-muted-foreground">Failed to load results. Please try again.</p>
      </div>
    )
  }

  if (!data?.data.length) {
    return (
      <div className="text-center py-8">
        <p className="text-muted-foreground">No routes found for your search criteria.</p>
      </div>
    )
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <div className="text-sm text-muted-foreground">
          Showing {data.from}-{data.to} of {data.total} results
        </div>
        <Select
          value={searchParams.get('sort') || 'departure_time'}
          onValueChange={(value) => updateParam('sort', value)}
        >
          <SelectTrigger className="w-48">
            <SelectValue placeholder="Sort by" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="departure_time">Departure Time</SelectItem>
            <SelectItem value="fare_per_seat">Price</SelectItem>
            <SelectItem value="duration">Duration</SelectItem>
          </SelectContent>
        </Select>
      </div>

      <div className="space-y-4">
        {data.data.map((route) => (
          <RouteCard key={route.id} route={route} journeyDate={journeyDate || undefined} />
        ))}
      </div>
    </div>
  )
}