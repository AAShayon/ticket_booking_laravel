import { NextResponse } from 'next/server'
import type { NextRequest } from 'next/server'

export function middleware(request: NextRequest) {
  const token = request.cookies.get('auth-token')?.value
  const userRole = request.cookies.get('user-role')?.value
  const { pathname } = request.nextUrl

  // Protect dashboard routes
  if (pathname.startsWith('/dashboard') || pathname.startsWith('/admin') || pathname.startsWith('/operator') || pathname.startsWith('/user')) {
    if (!token) {
      return NextResponse.redirect(new URL('/login', request.url))
    }
  }

  // Role-based access control
  if (pathname.startsWith('/admin') && userRole !== 'admin') {
    if (userRole === 'operator') {
      return NextResponse.redirect(new URL('/operator', request.url))
    } else if (userRole === 'user') {
      return NextResponse.redirect(new URL('/user', request.url))
    } else {
      return NextResponse.redirect(new URL('/login', request.url))
    }
  }

  if (pathname.startsWith('/operator') && userRole !== 'operator') {
    if (userRole === 'admin') {
      return NextResponse.redirect(new URL('/admin', request.url))
    } else if (userRole === 'user') {
      return NextResponse.redirect(new URL('/user', request.url))
    } else {
      return NextResponse.redirect(new URL('/login', request.url))
    }
  }

  if (pathname.startsWith('/user') && userRole !== 'user') {
    if (userRole === 'admin') {
      return NextResponse.redirect(new URL('/admin', request.url))
    } else if (userRole === 'operator') {
      return NextResponse.redirect(new URL('/operator', request.url))
    } else {
      return NextResponse.redirect(new URL('/login', request.url))
    }
  }

  // Role-based redirects
  if (pathname === '/dashboard') {
    if (userRole === 'admin') {
      return NextResponse.redirect(new URL('/admin', request.url))
    } else if (userRole === 'operator') {
      return NextResponse.redirect(new URL('/operator', request.url))
    } else if (userRole === 'user') {
      return NextResponse.redirect(new URL('/user', request.url))
    }
  }

  // Redirect authenticated users away from auth pages
  if ((pathname === '/login' || pathname === '/register') && token) {
    if (userRole === 'admin') {
      return NextResponse.redirect(new URL('/admin', request.url))
    } else if (userRole === 'operator') {
      return NextResponse.redirect(new URL('/operator', request.url))
    } else {
      return NextResponse.redirect(new URL('/user', request.url))
    }
  }

  return NextResponse.next()
}

export const config = {
  matcher: [
    '/((?!api|_next/static|_next/image|favicon.ico).*)',
  ],
}