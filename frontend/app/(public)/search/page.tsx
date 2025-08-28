import { Suspense } from 'react'
import { FiltersPanel } from '@/components/search/filters-panel'
import { ResultsList } from '@/components/search/results-list'
import { Pagination } from '@/components/search/pagination'
import { SearchSkeleton } from '@/components/search/search-skeleton'

export default function SearchPage() {
  return (
    <div className="container mx-auto px-4 py-6">
      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div className="lg:col-span-1">
          <Suspense fallback={<div className="h-96 bg-gray-100 rounded animate-pulse" />}>
            <FiltersPanel />
          </Suspense>
        </div>
        
        <div className="lg:col-span-3 space-y-6">
          <Suspense fallback={<SearchSkeleton />}>
            <ResultsList />
          </Suspense>
          
          <Suspense fallback={<div className="h-12 bg-gray-100 rounded animate-pulse" />}>
            <Pagination />
          </Suspense>
        </div>
      </div>
    </div>
  )
}