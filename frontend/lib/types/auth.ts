export interface User {
  id: number
  name: string
  email: string
  role: 'admin' | 'operator' | 'user'
  email_verified_at?: string
  created_at: string
  updated_at: string
}

export interface AuthResponse {
  access_token: string
  token_type: string
  role: 'admin' | 'operator' | 'user'
  user: User
}

export interface LoginRequest {
  email: string
  password: string
}

export interface RegisterRequest {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export interface AuthContextType {
  user: User | null
  token: string | null
  role: 'admin' | 'operator' | 'user' | null
  loading: boolean
  login: (email: string, password: string) => Promise<void>
  register: (data: RegisterRequest) => Promise<void>
  logout: () => void
}