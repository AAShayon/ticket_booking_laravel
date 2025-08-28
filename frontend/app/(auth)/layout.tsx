'use client'

import { useEffect } from 'react'
import { useRouter } from 'next/navigation'
import { useAuth } from '@/contexts/auth-context'
import { PublicLayout } from '@/components/layout/public-layout'

export default function AuthLayout({
  children,
}: {
  children: React.ReactNode
}) {
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

  if (user) {
    return null
  }

  return <PublicLayout>{children}</PublicLayout>
}