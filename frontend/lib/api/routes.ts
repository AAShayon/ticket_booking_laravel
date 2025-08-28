import apiClient from './client'
import type { RouteSearchParams, RouteSearchResponse, Route } from '@/lib/types/route'

export const routesApi = {
  searchRoutes: async (params: RouteSearchParams): Promise<RouteSearchResponse> => {
    const response = await apiClient.get('/routes/search', { params })
    return response.data
  },

  getPublicRoutes: async (): Promise<Route[]> => {
    const response = await apiClient.get('/routes/public')
    return response.data
  },
}