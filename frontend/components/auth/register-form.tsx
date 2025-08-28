'use client'

import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { useMutation } from '@tanstack/react-query'
import { useRouter } from 'next/navigation'
import Link from 'next/link'

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { RegisterSchema, type RegisterFormData } from '@/lib/validations/auth'
import { useAuth } from '@/contexts/auth-context'

export function RegisterForm() {
  const router = useRouter()
  const { register: registerUser } = useAuth()
  const [error, setError] = useState('')

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<RegisterFormData>({
    resolver: zodResolver(RegisterSchema),
  })

  const registerMutation = useMutation({
    mutationFn: async (data: RegisterFormData) => {
      await registerUser(data)
    },
    onSuccess: () => {
      router.push('/dashboard')
    },
    onError: (error: any) => {
      setError(error.response?.data?.message || 'Registration failed')
    },
  })

  const onSubmit = (data: RegisterFormData) => {
    setError('')
    registerMutation.mutate(data)
  }

  return (
    <Card className="w-full max-w-md">
      <CardHeader>
        <CardTitle>Create Account</CardTitle>
        <CardDescription>
          Enter your information to create a new account
        </CardDescription>
      </CardHeader>
      <CardContent>
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div className="space-y-2">
            <Input
              {...register('name')}
              type="text"
              placeholder="Full Name"
              disabled={registerMutation.isPending}
            />
            {errors.name && (
              <p className="text-sm text-destructive">{errors.name.message}</p>
            )}
          </div>

          <div className="space-y-2">
            <Input
              {...register('email')}
              type="email"
              placeholder="Email"
              disabled={registerMutation.isPending}
            />
            {errors.email && (
              <p className="text-sm text-destructive">{errors.email.message}</p>
            )}
          </div>
          
          <div className="space-y-2">
            <Input
              {...register('password')}
              type="password"
              placeholder="Password"
              disabled={registerMutation.isPending}
            />
            {errors.password && (
              <p className="text-sm text-destructive">{errors.password.message}</p>
            )}
          </div>

          <div className="space-y-2">
            <Input
              {...register('password_confirmation')}
              type="password"
              placeholder="Confirm Password"
              disabled={registerMutation.isPending}
            />
            {errors.password_confirmation && (
              <p className="text-sm text-destructive">{errors.password_confirmation.message}</p>
            )}
          </div>

          {error && (
            <p className="text-sm text-destructive">{error}</p>
          )}

          <Button
            type="submit"
            className="w-full"
            disabled={registerMutation.isPending}
          >
            {registerMutation.isPending ? 'Creating account...' : 'Create Account'}
          </Button>
        </form>

        <div className="mt-4 text-center text-sm">
          Already have an account?{' '}
          <Link href="/login" className="text-primary hover:underline">
            Sign in
          </Link>
        </div>
      </CardContent>
    </Card>
  )
}