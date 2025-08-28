'use client'

import { useEffect } from 'react'
import { useRouter } from 'next/navigation'
import { useAuth } from '@/contexts/auth-context'

interface AuthGuardProps {
  children: React.ReactNode
  requiredRole?: 'admin' | 'operator' | 'user'
}

export function AuthGuard({ children, requiredRole }: AuthGuardProps) {
  const { user, role, loading } = useAuth()
  const router = useRouter()

  useEffect(() => {
    if (!loading) {
      if (!user) {
        router.push('/login')
        return
      }

      if (requiredRole && role !== requiredRole) {
        // Redirect to appropriate dashboard based on user role
        if (role === 'admin') {
          router.push('/admin')
        } else if (role === 'operator') {
          router.push('/operator')
        } else {
          router.push('/user')
        }
        return
      }
    }
  }, [user, role, loading, requiredRole, router])

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-primary"></div>
      </div>
    )
  }

  if (!user) {
    return null
  }

  if (requiredRole && role !== requiredRole) {
    return null
  }

  return <>{children}</>
}