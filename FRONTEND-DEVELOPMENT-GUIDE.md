# ANSTECHES TICKET BOOKING - FRONTEND DEVELOPMENT GUIDE

## 🎯 **PRODUCTION API OVERVIEW**

**Production API Base URL**: `https://ansteches.shop/api`  
**API Testing Interface**: `https://ansteches.shop`  
**GitHub Repository**: `https://github.com/AAShayon/ticket_booking_laravel.git`  
**Production Branch**: `final_deploy`  

---

## 🔐 **AUTHENTICATION SYSTEM**

### **Authentication Flow**
- **Method**: Laravel Sanctum Token-based Authentication
- **Token Type**: Bearer Token
- **Login Endpoint**: `POST /api/login`
- **Token Header**: `Authorization: Bearer {token}`

### **User Roles & Permissions**
```json
{
  "roles": {
    "admin": "Full system access, can manage operators and users",
    "operator": "Can manage their own vehicles and bookings", 
    "user": "Can book tickets and manage profile"
  }
}
```

### **Test Credentials**
```json
{
  "admin": {
    "email": "superadmin@example.com",
    "password": "password"
  },
  "user": {
    "email": "user1@example.com", 
    "password": "password"
  }
}
```

---

## 🛠 **COMPLETE API ENDPOINTS**

### **🔑 Authentication Endpoints**
```javascript
// Login
POST /api/login
{
  "email": "superadmin@example.com",
  "password": "password"
}

// Register
POST /api/register
{
  "name": "John Doe",
  "email": "john@example.com", 
  "password": "password",
  "password_confirmation": "password",
  "phone_number": "+1234567890"
}

// Logout
POST /api/logout
Headers: { "Authorization": "Bearer {token}" }

// Profile Management
GET /api/profile
PUT /api/profile
```

### **👥 User Management (Admin Only)**
```javascript
// Users CRUD
GET /api/users              // List all users
POST /api/users             // Create user  
GET /api/users/{id}         // Get user details
PUT /api/users/{id}         // Update user
DELETE /api/users/{id}      // Delete user
```

### **🚌 Operator Management (Admin Only)**
```javascript 
// Operators CRUD
GET /api/operators          // List operators
POST /api/operators         // Create operator
GET /api/operators/{id}     // Get operator details  
PUT /api/operators/{id}     // Update operator
DELETE /api/operators/{id}  // Delete operator
```

### **🚐 Vehicle Management**
```javascript
// Vehicles (Operator can manage own, Admin can manage all)
GET /api/vehicles           // List vehicles
POST /api/vehicles          // Create vehicle
GET /api/vehicles/{id}      // Get vehicle details
PUT /api/vehicles/{id}      // Update vehicle  
DELETE /api/vehicles/{id}   // Delete vehicle
```

### **🎫 Booking Management**
```javascript
// Bookings (Users book, Operators manage)
GET /api/bookings           // List user's bookings
POST /api/bookings          // Create booking
GET /api/bookings/{id}      // Get booking details
PUT /api/bookings/{id}      // Update booking
DELETE /api/bookings/{id}   // Cancel booking
```

### **📍 Route Management**
```javascript
// Routes (Operators manage)
GET /api/routes             // List routes
POST /api/routes            // Create route
GET /api/routes/{id}        // Get route details
PUT /api/routes/{id}        // Update route
DELETE /api/routes/{id}     // Delete route
```

### **🕐 Schedule Management** 
```javascript
// Schedules (Operators manage)
GET /api/schedules          // List schedules
POST /api/schedules         // Create schedule
GET /api/schedules/{id}     // Get schedule details
PUT /api/schedules/{id}     // Update schedule
DELETE /api/schedules/{id}  // Delete schedule
```

### **💰 Pricing Management**
```javascript
// Pricing (Operators manage)
GET /api/pricing            // List pricing
POST /api/pricing           // Create pricing
GET /api/pricing/{id}       // Get pricing details  
PUT /api/pricing/{id}       // Update pricing
DELETE /api/pricing/{id}    // Delete pricing
```

### **📊 Dashboard & Analytics**
```javascript
// Dashboard data
GET /api/dashboard          // Get dashboard stats

// Reports
GET /api/reports/bookings   // Booking reports
GET /api/reports/revenue    // Revenue reports
GET /api/reports/users      // User reports
```

---

## 🖼️ **FILE UPLOAD SYSTEM**

### **Profile Image Upload**
```javascript
// Profile Image Upload (Multipart Form Data)
PUT /api/profile
Content-Type: multipart/form-data

FormData:
- name: "User Name"
- email: "user@example.com"  
- phone_number: "+1234567890"
- profile_image: File (max 2MB, jpeg,png,jpg,gif,svg)
```

### **File Validation Rules**
```json
{
  "profile_image": {
    "max_size": "2MB",
    "allowed_types": ["jpeg", "png", "jpg", "gif", "svg"],
    "storage_path": "/storage/app/public/uploads/"
  }
}
```

---

## 📱 **FRONTEND REQUIREMENTS & RECOMMENDATIONS**

### **🎨 UI/UX Guidelines**
```javascript
// Modern Framework Recommendations
const frameworks = {
  "react": "Next.js 14+ with TypeScript",
  "vue": "Nuxt.js 3+ with TypeScript", 
  "angular": "Angular 17+ with Material UI",
  "mobile": "React Native or Flutter"
}

// Design System
const designSystem = {
  "colors": {
    "primary": "#3B82F6",    // Blue
    "success": "#10B981",    // Green  
    "warning": "#F59E0B",    // Orange
    "error": "#EF4444",      // Red
    "dark": "#1F2937"        // Dark Gray
  },
  "typography": "Inter or Poppins",
  "components": "Tailwind CSS or Material-UI"
}
```

### **🔧 Required Frontend Features**

#### **1. Authentication Module**
```javascript
// Login/Register Forms
- Email/Password validation
- Remember me functionality  
- Password reset flow
- Role-based redirects (admin/operator/user)
- Token storage (localStorage/sessionStorage)
- Auto-logout on token expiry
```

#### **2. Dashboard Components**
```javascript
// Role-based Dashboards
- Admin: User management, operator oversight, system stats
- Operator: Vehicle management, bookings, revenue tracking  
- User: Booking history, profile management, ticket viewing
```

#### **3. Booking Flow**
```javascript
// User Booking Journey
- Route search with filters
- Schedule selection with real-time availability
- Seat selection (if applicable)
- Passenger details form
- Payment integration
- Booking confirmation
- E-ticket generation (PDF/Digital)
```

#### **4. Management Interfaces**
```javascript
// CRUD Operations for:
- Users (Admin only)
- Operators (Admin only)  
- Vehicles (Operator/Admin)
- Routes (Operator/Admin)
- Schedules (Operator/Admin)
- Bookings (All roles with permissions)
```

### **🌐 API Integration Setup**

#### **Environment Configuration**
```javascript
// .env.local or .env.production
NEXT_PUBLIC_API_BASE_URL=https://ansteches.shop/api
NEXT_PUBLIC_APP_URL=https://ansteches.shop
NEXT_PUBLIC_STORAGE_URL=https://ansteches.shop/storage/app/public

// Development  
NEXT_PUBLIC_API_BASE_URL=http://127.0.0.1:8000/api
NEXT_PUBLIC_APP_URL=http://127.0.0.1:8000
```

#### **API Client Setup (Axios Example)**
```javascript
// api/client.js
import axios from 'axios';

const apiClient = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
});

// Request interceptor for auth token
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor for error handling
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default apiClient;
```

#### **Authentication Service**
```javascript
// services/auth.js
import apiClient from '../api/client';

class AuthService {
  async login(credentials) {
    const response = await apiClient.post('/login', credentials);
    const { token, user } = response.data;
    
    localStorage.setItem('auth_token', token);
    localStorage.setItem('user', JSON.stringify(user));
    
    return { token, user };
  }

  async register(userData) {
    return await apiClient.post('/register', userData);
  }

  async logout() {
    await apiClient.post('/logout');
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
  }

  getCurrentUser() {
    return JSON.parse(localStorage.getItem('user') || 'null');
  }

  isAuthenticated() {
    return !!localStorage.getItem('auth_token');
  }
}

export default new AuthService();
```

---

## 🔒 **SECURITY CONSIDERATIONS**

### **Frontend Security Checklist**
```javascript
// Security Best Practices
const security = {
  "tokenStorage": "Use httpOnly cookies for production",
  "apiValidation": "Validate all API responses", 
  "inputSanitization": "Sanitize user inputs",
  "cors": "Configure CORS properly",
  "https": "Enforce HTTPS in production",
  "xss": "Implement XSS protection",
  "csrf": "Handle CSRF tokens if needed"
}
```

### **Error Handling**
```javascript
// Global Error Handler
const errorHandler = {
  "401": "Redirect to login",
  "403": "Show access denied message",
  "404": "Show not found page", 
  "422": "Show validation errors",
  "500": "Show generic error message"
}
```

---

## 📦 **DEPLOYMENT RECOMMENDATIONS**

### **Frontend Hosting Options**
```javascript
// Production Deployment
const hostingOptions = {
  "vercel": "Recommended for Next.js",
  "netlify": "Good for static sites",
  "aws": "S3 + CloudFront for React SPA",
  "digitalOcean": "App Platform for full-stack",
  "cpanel": "Traditional hosting with build output"
}
```

### **Build Configuration**
```javascript
// For cPanel deployment
const buildConfig = {
  "outputType": "static export or SPA",
  "apiProxy": "Not needed (direct API calls)",
  "assetPath": "Configure for subdomain if needed",
  "envVars": "Use production API URLs"
}
```

---

## 🧪 **API TESTING & VALIDATION**

### **Test the API Before Development**
1. **Visit**: `https://ansteches.shop`
2. **Use the built-in testing interface**
3. **Test all endpoints with different roles**
4. **Verify file upload functionality**
5. **Check response formats and error handling**

### **Sample API Calls for Testing**
```bash
# Test Authentication
curl -X POST https://ansteches.shop/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"superadmin@example.com","password":"password"}'

# Test Protected Endpoint
curl -X GET https://ansteches.shop/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN"

# Test File Upload
curl -X PUT https://ansteches.shop/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "name=Test User" \
  -F "profile_image=@/path/to/image.jpg"
```

---

## 📚 **ADDITIONAL RESOURCES**

### **Documentation Links**
- **GitHub Repository**: https://github.com/AAShayon/ticket_booking_laravel.git
- **Production API**: https://ansteches.shop/api  
- **API Testing Interface**: https://ansteches.shop
- **Postman Collection**: Available in repository root

### **Development Timeline Suggestion**
1. **Week 1**: Setup + Authentication + Basic UI
2. **Week 2**: User Dashboard + Booking Flow  
3. **Week 3**: Admin/Operator Interfaces
4. **Week 4**: Testing + Deployment + Polish

---

## 🚀 **QUICK START COMMAND**

```bash
# Clone and setup your frontend project
npx create-next-app@latest ansteches-frontend --typescript --tailwind --app
cd ansteches-frontend

# Install additional dependencies  
npm install axios react-query @tanstack/react-query lucide-react

# Create .env.local
echo "NEXT_PUBLIC_API_BASE_URL=https://ansteches.shop/api" > .env.local
echo "NEXT_PUBLIC_STORAGE_URL=https://ansteches.shop/storage/app/public" >> .env.local

# Start development
npm run dev
```

**Your Laravel API is production-ready and fully functional! Start building your frontend with confidence.** 🎉