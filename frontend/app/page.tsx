'use client'

import { useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import { useAuth } from '@/contexts/auth-context'
import { Button } from '@/components/ui/button'
import { SearchBar } from '@/components/search/search-bar'
import { POPULAR_ROUTES } from '@/lib/constants/routes'

export default function HomePage() {
  const { user, role, loading } = useAuth()
  const router = useRouter()

  useEffect(() => {
    if (!loading && user) {
      // Redirect authenticated users to their dashboard
      if (role === 'admin') {
        router.push('/admin')
      } else if (role === 'operator') {
        router.push('/operator')
      } else {
        router.push('/user')
      }
    }
  }, [user, role, loading, router])

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-primary"></div>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
      <div className="container mx-auto px-4 py-16">
        <div className="text-center mb-12">
          <h1 className="text-4xl font-bold text-gray-900 mb-6">
            Welcome to TicketBooking
          </h1>
          <p className="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Your comprehensive bus ticket booking system. Book tickets, manage routes, and travel with ease.
          </p>
        </div>

        <div className="max-w-4xl mx-auto mb-12">
          <SearchBar />
        </div>

        <div className="max-w-4xl mx-auto mb-12">
          <h2 className="text-2xl font-semibold text-gray-900 mb-6 text-center">Popular Routes</h2>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {POPULAR_ROUTES.map((route, index) => (
              <div key={index} className="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                <div className="flex items-center justify-between">
                  <span className="font-medium">{route.origin}</span>
                  <span className="text-gray-400">→</span>
                  <span className="font-medium">{route.destination}</span>
                </div>
              </div>
            ))}
          </div>
        </div>
          
        <div className="flex justify-center space-x-4">
          <Button asChild size="lg">
            <Link href="/login">Sign In</Link>
          </Button>
          <Button asChild variant="outline" size="lg">
            <Link href="/register">Create Account</Link>
          </Button>
        </div>
      </div>
    </div>
  )
}