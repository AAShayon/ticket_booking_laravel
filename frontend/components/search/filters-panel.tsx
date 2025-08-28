'use client'

import { X } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Slider } from '@/components/ui/slider'
import { Badge } from '@/components/ui/badge'
import { VEHICLE_TYPES, TIME_OF_DAY, FARE_RANGE_LIMITS } from '@/lib/constants/routes'
import { useSearchParams } from '@/hooks/use-search-params'

export function FiltersPanel() {
  const { searchParams, updateParam, clearParam, clearAllFilters } = useSearchParams()
  
  const fareRange = [
    parseInt(searchParams.get('minFare') || FARE_RANGE_LIMITS.min.toString()),
    parseInt(searchParams.get('maxFare') || FARE_RANGE_LIMITS.max.toString())
  ]

  const activeFiltersCount = [
    searchParams.get('type'),
    searchParams.get('time_of_day'),
    searchParams.get('minFare'),
    searchParams.get('maxFare')
  ].filter(Boolean).length

  return (
    <Card className="w-full">
      <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle className="text-lg">Filters</CardTitle>
        {activeFiltersCount > 0 && (
          <div className="flex items-center gap-2">
            <Badge variant="secondary">{activeFiltersCount} active</Badge>
            <Button variant="ghost" size="sm" onClick={clearAllFilters}>
              <X className="h-4 w-4" />
              Clear all
            </Button>
          </div>
        )}
      </CardHeader>
      <CardContent className="space-y-6">
        <div className="space-y-3">
          <h4 className="font-medium">Departure Time</h4>
          <Select
            value={searchParams.get('time_of_day') || ''}
            onValueChange={(value) => value ? updateParam('time_of_day', value) : clearParam('time_of_day')}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select time" />
            </SelectTrigger>
            <SelectContent>
              {TIME_OF_DAY.map((time) => (
                <SelectItem key={time.value} value={time.value}>
                  {time.label}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-3">
          <h4 className="font-medium">Vehicle Type</h4>
          <Select
            value={searchParams.get('type') || ''}
            onValueChange={(value) => value ? updateParam('type', value) : clearParam('type')}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select vehicle type" />
            </SelectTrigger>
            <SelectContent>
              {VEHICLE_TYPES.map((type) => (
                <SelectItem key={type.value} value={type.value}>
                  {type.label}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-3">
          <h4 className="font-medium">Fare Range</h4>
          <div className="px-2">
            <Slider
              value={fareRange}
              onValueChange={([min, max]) => {
                updateParam('minFare', min.toString())
                updateParam('maxFare', max.toString())
              }}
              max={FARE_RANGE_LIMITS.max}
              min={FARE_RANGE_LIMITS.min}
              step={FARE_RANGE_LIMITS.step}
              className="w-full"
            />
            <div className="flex justify-between text-sm text-muted-foreground mt-2">
              <span>৳{fareRange[0]}</span>
              <span>৳{fareRange[1]}</span>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}