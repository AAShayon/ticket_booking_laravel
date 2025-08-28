import axios from 'axios'
import { cookies } from 'next/headers'

export const createServerApiClient = () => {
  const cookieStore = cookies()
  const token = cookieStore.get('auth-token')?.value

  return axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_BASE_URL,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...(token && { Authorization: `Bearer ${token}` }),
    },
  })
}