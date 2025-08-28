'use client'

import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { useRouter } from 'next/navigation'
import { ArrowLeftRight, Search, Calendar as CalendarIcon } from 'lucide-react'
import { format } from 'date-fns'

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { RouteSearchSchema, type RouteSearchFormData } from '@/lib/validations/route'
import { cn } from '@/lib/utils'

interface SearchBarProps {
  className?: string
  compact?: boolean
}

export function SearchBar({ className, compact = false }: SearchBarProps) {
  const router = useRouter()
  const [date, setDate] = useState<Date>()
  const [isLoading, setIsLoading] = useState(false)

  const {
    register,
    handleSubmit,
    setValue,
    watch,
    formState: { errors },
  } = useForm<RouteSearchFormData>({
    resolver: zodResolver(RouteSearchSchema),
  })

  const origin = watch('origin')
  const destination = watch('destination')

  const swapLocations = () => {
    setValue('origin', destination)
    setValue('destination', origin)
  }

  const onSubmit = (data: RouteSearchFormData) => {
    setIsLoading(true)
    const params = new URLSearchParams({
      origin: data.origin,
      destination: data.destination,
      journey_date: data.journey_date,
    })
    router.push(`/search?${params.toString()}`)
  }

  return (
    <div className={cn('w-full', className)}>
      <form onSubmit={handleSubmit(onSubmit)} className={cn(
        'flex gap-2 p-4 bg-white rounded-lg shadow-md',
        compact ? 'flex-row items-center' : 'flex-col md:flex-row md:items-end'
      )}>
        <div className="flex-1 space-y-2">
          <label className="text-sm font-medium">From</label>
          <Input
            {...register('origin')}
            placeholder="Enter origin city"
            className={errors.origin ? 'border-red-500' : ''}
          />
          {errors.origin && <p className="text-xs text-red-500">{errors.origin.message}</p>}
        </div>

        <Button
          type="button"
          variant="ghost"
          size="icon"
          onClick={swapLocations}
          className="self-end mb-2"
        >
          <ArrowLeftRight className="h-4 w-4" />
        </Button>

        <div className="flex-1 space-y-2">
          <label className="text-sm font-medium">To</label>
          <Input
            {...register('destination')}
            placeholder="Enter destination city"
            className={errors.destination ? 'border-red-500' : ''}
          />
          {errors.destination && <p className="text-xs text-red-500">{errors.destination.message}</p>}
        </div>

        <div className="flex-1 space-y-2">
          <label className="text-sm font-medium">Journey Date</label>
          <Popover>
            <PopoverTrigger asChild>
              <Button
                variant="outline"
                className={cn(
                  'w-full justify-start text-left font-normal',
                  !date && 'text-muted-foreground',
                  errors.journey_date && 'border-red-500'
                )}
              >
                <CalendarIcon className="mr-2 h-4 w-4" />
                {date ? format(date, 'PPP') : 'Pick a date'}
              </Button>
            </PopoverTrigger>
            <PopoverContent className="w-auto p-0">
              <Calendar
                mode="single"
                selected={date}
                onSelect={(selectedDate) => {
                  setDate(selectedDate)
                  if (selectedDate) {
                    setValue('journey_date', format(selectedDate, 'yyyy-MM-dd'))
                  }
                }}
                disabled={(date) => date < new Date()}
                initialFocus
              />
            </PopoverContent>
          </Popover>
          {errors.journey_date && <p className="text-xs text-red-500">{errors.journey_date.message}</p>}
        </div>

        <Button type="submit" disabled={isLoading} className="self-end">
          <Search className="mr-2 h-4 w-4" />
          {isLoading ? 'Searching...' : 'Search'}
        </Button>
      </form>
    </div>
  )
}