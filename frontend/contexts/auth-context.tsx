'use client'

import React, { createContext, useContext, useEffect, useState } from 'react'
import { authApi } from '@/lib/api/auth'
import { setAuthToken, removeAuthToken, getAuthToken, setUserRole, removeUserRole } from '@/lib/utils'
import type { AuthContextType, User, RegisterRequest } from '@/lib/types/auth'

export const AuthContext = createContext<AuthContextType | undefined>(undefined)

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null)
  const [token, setToken] = useState<string | null>(null)
  const [role, setRole] = useState<'admin' | 'operator' | 'user' | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const initAuth = async () => {
      const savedToken = getAuthToken()
      if (savedToken) {
        setToken(savedToken)
        try {
          const userData = await authApi.getProfile()
          setUser(userData)
          setRole(userData.role)
          setUserRole(userData.role)
        } catch (error) {
          removeAuthToken()
          removeUserRole()
        }
      }
      setLoading(false)
    }

    initAuth()
  }, [])

  const login = async (email: string, password: string) => {
    const response = await authApi.login({ email, password })
    setAuthToken(response.access_token)
    setUserRole(response.role)
    setToken(response.access_token)
    setUser(response.user)
    setRole(response.role)
  }

  const register = async (data: RegisterRequest) => {
    const response = await authApi.register(data)
    setAuthToken(response.access_token)
    setUserRole(response.role)
    setToken(response.access_token)
    setUser(response.user)
    setRole(response.role)
  }

  const logout = () => {
    authApi.logout().catch(() => {})
    removeAuthToken()
    removeUserRole()
    setToken(null)
    setUser(null)
    setRole(null)
  }

  return (
    <AuthContext.Provider value={{ user, token, role, loading, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  )
}

export const useAuth = () => {
  const context = useContext(AuthContext)
  
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider')
  }
  
  return context
}

