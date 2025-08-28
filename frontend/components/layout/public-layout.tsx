import Link from 'next/link'
import { SearchBar } from '@/components/search/search-bar'

interface PublicLayoutProps {
  children: React.ReactNode
}

export function PublicLayout({ children }: PublicLayoutProps) {
  return (
    <div className="min-h-screen bg-gray-50">
      <header className="bg-white shadow-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <Link href="/" className="text-xl font-bold text-primary">
              TicketBooking
            </Link>
            <div className="hidden md:block flex-1 max-w-md mx-8">
              <SearchBar compact />
            </div>
            <nav className="flex space-x-4">
              <Link
                href="/login"
                className="text-gray-600 hover:text-primary transition-colors"
              >
                Sign In
              </Link>
              <Link
                href="/register"
                className="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors"
              >
                Sign Up
              </Link>
            </nav>
          </div>
        </div>
      </header>
      <main>{children}</main>
    </div>
  )
}