# 🎯 FRONTEND DEVELOPMENT PROMPT

## **PROJECT**: Ansteches Ticket Booking System Frontend

**Build a modern, responsive frontend for a Laravel-powered ticket booking system with role-based access control.**

---

## 🔗 **PRODUCTION API DETAILS**

- **Base URL**: `https://ansteches.shop/api`
- **Authentication**: Laravel Sanctum (Bearer Token)
- **Testing Interface**: `https://ansteches.shop` (Built-in Postman-like interface)
- **Repository**: `https://github.com/AAShayon/ticket_booking_laravel.git` (branch: `final_deploy`)

## 👥 **USER ROLES & ACCESS**

```json
{
  "admin": {
    "access": "Full system control, manage users/operators",
    "test_login": "superadmin@example.com / password"
  },
  "operator": {
    "access": "Manage vehicles, routes, bookings",
    "test_login": "operator1@example.com / password"
  },
  "user": {
    "access": "Book tickets, manage profile",
    "test_login": "user1@example.com / password"
  }
}
```

## 🛠 **CORE FEATURES TO BUILD**

### **1. Authentication System**
- Login/Register with email validation
- Role-based dashboard routing
- Profile management with image upload
- Token-based session management

### **2. User Dashboard**
- Booking history and active tickets  
- Profile management (name, phone, image)
- E-ticket viewing and download

### **3. Operator Dashboard**
- Vehicle management (CRUD)
- Route and schedule management
- Booking oversight and revenue tracking
- Customer management

### **4. Admin Dashboard**  
- User and operator management
- System-wide analytics and reports
- Platform configuration and oversight

### **5. Booking Flow**
- Route search with date/time filters
- Available schedule selection
- Passenger details and confirmation
- Payment integration (prepare for future)
- E-ticket generation

## 📱 **TECHNICAL REQUIREMENTS**

### **Framework Recommendations**
- **Next.js 14+** with TypeScript (Recommended)
- **React 18+** with Vite + TypeScript
- **Vue 3** with Nuxt.js + TypeScript

### **UI/UX Guidelines**
- **Design**: Modern, clean, mobile-first responsive
- **Colors**: Primary #3B82F6, Success #10B981, Error #EF4444
- **Components**: Tailwind CSS or Material-UI/Ant Design
- **Typography**: Inter or Poppins font family

### **Required Libraries**
```bash
# Core dependencies
npm install axios @tanstack/react-query
npm install lucide-react react-hook-form zod
npm install date-fns react-datepicker
npm install react-router-dom # or Next.js built-in routing
```

## 🔒 **API INTEGRATION SETUP**

### **Environment Variables**
```env
NEXT_PUBLIC_API_BASE_URL=https://ansteches.shop/api
NEXT_PUBLIC_STORAGE_URL=https://ansteches.shop/storage/app/public
```

### **Authentication Headers**
```javascript
// All protected requests need:
headers: {
  "Authorization": "Bearer {token}",
  "Content-Type": "application/json",
  "Accept": "application/json"
}
```

## 📊 **KEY API ENDPOINTS TO IMPLEMENT**

```javascript
// Authentication
POST /api/login
POST /api/register  
POST /api/logout

// Profile Management
GET /api/profile
PUT /api/profile (with FormData for image upload)

// Resource Management (with role-based access)
GET|POST|PUT|DELETE /api/users        // Admin only
GET|POST|PUT|DELETE /api/operators    // Admin only  
GET|POST|PUT|DELETE /api/vehicles     // Operator/Admin
GET|POST|PUT|DELETE /api/routes       // Operator/Admin
GET|POST|PUT|DELETE /api/schedules    // Operator/Admin
GET|POST|PUT|DELETE /api/bookings     // All roles with permissions

// Dashboard & Analytics
GET /api/dashboard
GET /api/reports/bookings
GET /api/reports/revenue
```

## 🎨 **UI COMPONENTS TO BUILD**

### **Shared Components**
- Navigation bar with role-based menu
- Sidebar with dashboard navigation  
- Data tables with pagination/filtering
- Form components with validation
- Modal dialogs for CRUD operations
- Loading states and error boundaries

### **Role-Specific Pages**
```
/login, /register
/dashboard (role-based redirect)
/profile
/bookings (list, create, view)
/admin/users, /admin/operators
/operator/vehicles, /operator/routes
/search-routes, /book-ticket
```

## 🔧 **FILE UPLOAD HANDLING**

```javascript
// Profile image upload (max 2MB, jpeg/png/jpg/gif/svg)
const formData = new FormData();
formData.append('name', userName);
formData.append('email', userEmail);
formData.append('profile_image', imageFile);

fetch('/api/profile', {
  method: 'PUT',
  headers: { 'Authorization': `Bearer ${token}` },
  body: formData
});
```

## 🚀 **DEPLOYMENT STRATEGY**

### **Development**
- Use the built-in API testing interface at `https://ansteches.shop`
- Test all endpoints before frontend implementation
- Validate authentication flow and role permissions

### **Production Options**
- **Vercel/Netlify**: For Next.js/React static deployment
- **cPanel**: Build static files and upload to hosting
- **AWS S3**: For SPA deployment with CloudFront CDN

## ✅ **SUCCESS CRITERIA**

1. **Authentication**: Seamless login/logout with role detection
2. **Responsive Design**: Works on desktop, tablet, and mobile
3. **Real-time Data**: Proper API integration with error handling  
4. **Role Permissions**: Correct access control for all features
5. **File Upload**: Working image upload with preview
6. **Performance**: Fast loading with proper state management
7. **UX**: Intuitive booking flow and dashboard navigation

## 🎯 **QUICK START**

1. **Test the API**: Visit `https://ansteches.shop` and explore all endpoints
2. **Choose Framework**: Set up Next.js/React project with TypeScript
3. **Setup Authentication**: Implement login flow with token storage
4. **Build Dashboards**: Create role-based dashboard layouts
5. **Implement Booking**: Build the core ticket booking functionality
6. **Polish UI**: Add responsive design and smooth animations

**The Laravel backend is production-ready and fully functional. Focus on creating an exceptional user experience!** 🎉