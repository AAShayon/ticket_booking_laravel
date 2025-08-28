import { RegisterForm } from '@/components/auth/register-form'

export default function RegisterPage() {
  return (
    <div className="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-6xl w-full flex">
        <div className="flex-1 flex items-center justify-center">
          <RegisterForm />
        </div>
        <div className="hidden lg:flex flex-1 items-center justify-center bg-primary/5 rounded-r-lg">
          <div className="text-center p-8">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">
              Join Us Today
            </h2>
            <p className="text-gray-600">
              Create your account and start booking tickets with ease
            </p>
          </div>
        </div>
      </div>
    </div>
  )
}