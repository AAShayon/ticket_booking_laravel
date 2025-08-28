# Ticket Booking Frontend

A modern Next.js 14+ frontend application for the bus ticket booking system with role-based authentication and responsive design.

## Features

- **Authentication System**: Complete login/register with JWT token management
- **Role-Based Access Control**: Admin, Operator, and User dashboards
- **Modern UI**: Built with shadcn/ui components and Tailwind CSS
- **Type Safety**: Full TypeScript implementation
- **State Management**: React Context for authentication state
- **API Integration**: Axios client with interceptors for Laravel backend
- **Form Validation**: React Hook Form with Zod schemas
- **Responsive Design**: Mobile-first approach with Tailwind CSS

## Tech Stack

- **Framework**: Next.js 14+ with App Router
- **Language**: TypeScript
- **Styling**: Tailwind CSS
- **UI Components**: shadcn/ui
- **Forms**: React Hook Form + Zod validation
- **HTTP Client**: Axios with interceptors
- **State Management**: React Context
- **Data Fetching**: TanStack Query
- **Icons**: Lucide React

## Environment Variables

Create a `.env.local` file in the root directory:

```env
NEXT_PUBLIC_API_BASE_URL=https://ansteches.shop/api
NEXT_PUBLIC_STORAGE_URL=https://ansteches.shop/storage/app/public
```

## Installation

1. Install dependencies:
```bash
npm install
```

2. Run the development server:
```bash
npm run dev
```

3. Open [http://localhost:3000](http://localhost:3000) in your browser.

## Project Structure

```
frontend/
├── app/                    # Next.js App Router
│   ├── (auth)/            # Authentication pages
│   │   ├── login/
│   │   └── register/
│   ├── (dashboard)/       # Protected dashboard pages
│   │   ├── admin/
│   │   ├── operator/
│   │   └── user/
│   ├── globals.css        # Global styles
│   └── layout.tsx         # Root layout
├── components/            # Reusable components
│   ├── auth/             # Authentication components
│   ├── layout/           # Layout components
│   └── ui/               # shadcn/ui components
├── contexts/             # React contexts
├── hooks/                # Custom hooks
├── lib/                  # Utilities and configurations
│   ├── api/             # API client and services
│   ├── types/           # TypeScript type definitions
│   └── validations/     # Zod schemas
└── middleware.ts         # Next.js middleware for route protection
```

## Authentication Flow

1. **Login/Register**: Users authenticate via forms with validation
2. **Token Storage**: JWT tokens stored in localStorage and cookies
3. **Route Protection**: Middleware checks authentication and redirects
4. **Role-Based Routing**: Users redirected to appropriate dashboards
5. **Auto Logout**: Invalid tokens trigger automatic logout

## Role-Based Access

- **Admin**: Full system access, user management, system settings
- **Operator**: Vehicle and route management, booking oversight
- **User**: Booking management, profile settings

## API Integration

The frontend integrates with the Laravel backend at `https://ansteches.shop/api`:

- **Authentication**: `/login`, `/register`, `/logout`, `/user`
- **Automatic Headers**: Bearer token attached to all requests
- **Error Handling**: 401 responses trigger logout and redirect
- **Request/Response Logging**: Development mode logging

## Development

### Adding New Components

1. Use shadcn/ui CLI to add components:
```bash
npx shadcn-ui@latest add button
```

2. Create custom components in appropriate directories
3. Follow the established patterns for TypeScript and styling

### Form Validation

All forms use React Hook Form with Zod schemas:

```typescript
const schema = z.object({
  email: z.string().email(),
  password: z.string().min(8),
})

const form = useForm({
  resolver: zodResolver(schema)
})
```

### API Calls

Use the centralized API client:

```typescript
import { authApi } from '@/lib/api/auth'

const response = await authApi.login({ email, password })
```

## Build and Deploy

1. Build the application:
```bash
npm run build
```

2. Start production server:
```bash
npm start
```

## Next Steps

This foundation supports the following planned features:
- Bus search and booking system
- Payment integration
- Real-time seat selection
- Booking management
- Admin panel features
- Operator vehicle management
- User profile and history

## Contributing

1. Follow the established code patterns
2. Use TypeScript for all new code
3. Implement proper error handling
4. Add appropriate loading states
5. Ensure responsive design
6. Test authentication flows