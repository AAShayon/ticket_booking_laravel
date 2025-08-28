'use client'

import { useRouter, useSearchParams as useNextSearchParams } from 'next/navigation'
import { useCallback } from 'react'

export function useSearchParams() {
  const router = useRouter()
  const searchParams = useNextSearchParams()

  const updateParam = useCallback((key: string, value: string) => {
    const params = new URLSearchParams(searchParams.toString())
    params.set(key, value)
    router.push(`?${params.toString()}`)
  }, [router, searchParams])

  const clearParam = useCallback((key: string) => {
    const params = new URLSearchParams(searchParams.toString())
    params.delete(key)
    router.push(`?${params.toString()}`)
  }, [router, searchParams])

  const clearAllFilters = useCallback(() => {
    const params = new URLSearchParams(searchParams.toString())
    // Keep essential search params
    const origin = params.get('origin')
    const destination = params.get('destination')
    const journey_date = params.get('journey_date')
    
    // Instead of clear(), manually remove all params except essential ones
    const paramKeys = Array.from(params.keys())
    paramKeys.forEach(key => {
      if (key !== 'origin' && key !== 'destination' && key !== 'journey_date') {
        params.delete(key)
      }
    })
    
    if (origin) params.set('origin', origin)
    if (destination) params.set('destination', destination)
    if (journey_date) params.set('journey_date', journey_date)
    
    router.push(`?${params.toString()}`)
  }, [router, searchParams])

  return {
    searchParams,
    updateParam,
    clearParam,
    clearAllFilters,
  }
}