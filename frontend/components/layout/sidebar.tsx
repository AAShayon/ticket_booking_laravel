'use client'

import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { 
  LayoutDashboard, 
  Users, 
  Bus, 
  Route, 
  Ticket, 
  Settings,
  UserCheck,
  Calendar
} from 'lucide-react'
import { cn } from '@/lib/utils'
import { useAuth } from '@/contexts/auth-context'

const adminNavItems = [
  { href: '/admin', label: 'Dashboard', icon: LayoutDashboard },
  { href: '/admin/users', label: 'Users', icon: Users },
  { href: '/admin/operators', label: 'Operators', icon: UserCheck },
  { href: '/admin/vehicles', label: 'Vehicles', icon: Bus },
  { href: '/admin/routes', label: 'Routes', icon: Route },
  { href: '/admin/bookings', label: 'Bookings', icon: Ticket },
  { href: '/admin/settings', label: 'Settings', icon: Settings },
]

const operatorNavItems = [
  { href: '/operator', label: 'Dashboard', icon: LayoutDashboard },
  { href: '/operator/vehicles', label: 'My Vehicles', icon: Bus },
  { href: '/operator/routes', label: 'My Routes', icon: Route },
  { href: '/operator/schedules', label: 'Schedules', icon: Calendar },
  { href: '/operator/bookings', label: 'Bookings', icon: Ticket },
]

const userNavItems = [
  { href: '/user', label: 'Dashboard', icon: LayoutDashboard },
  { href: '/user/bookings', label: 'My Bookings', icon: Ticket },
  { href: '/user/profile', label: 'Profile', icon: Settings },
]

export function Sidebar() {
  const pathname = usePathname()
  const { role } = useAuth()

  const getNavItems = () => {
    switch (role) {
      case 'admin':
        return adminNavItems
      case 'operator':
        return operatorNavItems
      case 'user':
        return userNavItems
      default:
        return []
    }
  }

  const navItems = getNavItems()

  return (
    <aside className="w-64 bg-white shadow-sm border-r min-h-screen">
      <nav className="p-4 space-y-2">
        {navItems.map((item) => {
          const Icon = item.icon
          const isActive = pathname === item.href
          
          return (
            <Link
              key={item.href}
              href={item.href}
              className={cn(
                'flex items-center space-x-3 px-3 py-2 rounded-md text-sm font-medium transition-colors',
                isActive
                  ? 'bg-primary text-primary-foreground'
                  : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
              )}
            >
              <Icon className="h-4 w-4" />
              <span>{item.label}</span>
            </Link>
          )
        })}
      </nav>
    </aside>
  )
}