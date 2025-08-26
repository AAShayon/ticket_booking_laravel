#!/bin/bash

# Ansteches Booking Platform - Build & Deploy Script
# This script builds the Next.js application and creates a deployment-ready zip file

echo "🚀 Starting Ansteches Booking Platform Build Process..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    print_error "Node.js is not installed. Please install Node.js 18+ first."
    exit 1
fi

# Check Node.js version
NODE_VERSION=$(node -v | cut -d'v' -f2 | cut -d'.' -f1)
if [ "$NODE_VERSION" -lt 18 ]; then
    print_error "Node.js version 18+ is required. Current version: $(node -v)"
    exit 1
fi

print_success "Node.js version: $(node -v)"

# Navigate to frontend directory
if [ ! -d "frontend" ]; then
    print_error "Frontend directory not found. Please run this script from the project root."
    exit 1
fi

cd frontend

# Check if package.json exists
if [ ! -f "package.json" ]; then
    print_error "package.json not found. Invalid frontend directory."
    exit 1
fi

print_status "Installing dependencies..."
if npm install; then
    print_success "Dependencies installed successfully"
else
    print_error "Failed to install dependencies"
    exit 1
fi

# Create environment file if it doesn't exist
if [ ! -f ".env.local" ]; then
    print_warning "Creating .env.local file..."
    cat > .env.local << EOF
NEXT_PUBLIC_API_URL=https://ansteches.shop/api
NEXT_PUBLIC_APP_URL=https://ansteches.shop
EOF
    print_success "Environment file created"
fi

# Clean previous builds
print_status "Cleaning previous builds..."
rm -rf .next out dist ansteches-frontend.zip

# Build the application
print_status "Building Next.js application..."
if npm run build; then
    print_success "Build completed successfully"
else
    print_error "Build failed"
    exit 1
fi

# Export static files
print_status "Exporting static files..."
if npm run export; then
    print_success "Export completed successfully"
else
    print_error "Export failed"
    exit 1
fi

# Create deployment directory structure
print_status "Preparing deployment files..."
mkdir -p deployment

# Copy static files
cp -r out/* deployment/

# Create deployment configuration files
cat > deployment/.htaccess << 'EOF'
# Next.js Static Export Configuration
DirectoryIndex index.html

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>

# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Redirect HTTP to HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Handle Next.js routing
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.html [L]
EOF

# Create deployment instructions
cat > deployment/DEPLOYMENT-INSTRUCTIONS.txt << 'EOF'
ANSTECHES BOOKING PLATFORM - DEPLOYMENT INSTRUCTIONS
====================================================

1. UPLOAD FILES:
   - Upload all files from this directory to your web server
   - Recommended path: /public_html/booking/ or /public_html/
   - Ensure .htaccess file is uploaded and not hidden

2. CONFIGURE WEB SERVER:
   - Point your domain/subdomain to the upload directory
   - Ensure PHP and mod_rewrite are enabled
   - Verify HTTPS is working

3. VERIFY DEPLOYMENT:
   - Visit your domain to see the homepage
   - Test search functionality
   - Check mobile responsiveness
   - Verify API integration with your Laravel backend

4. INTEGRATION WITH LARAVEL:
   - Ensure your Laravel API is accessible at https://ansteches.shop/api
   - Verify CORS is configured to allow frontend domain
   - Test authentication flow

5. TROUBLESHOOTING:
   - If images don't load: Check storage-serve.php in Laravel backend
   - If API calls fail: Verify CORS and API URL configuration
   - If routing doesn't work: Check .htaccess file and mod_rewrite

For support, refer to the README.md file or contact your development team.
EOF

# Create zip file for deployment
print_status "Creating deployment zip file..."
cd deployment
if zip -r ../ansteches-frontend.zip . -x "*.DS_Store" "*.git*"; then
    cd ..
    print_success "Deployment zip created: ansteches-frontend.zip"
else
    cd ..
    print_error "Failed to create zip file"
    exit 1
fi

# Get file size
ZIP_SIZE=$(du -h ansteches-frontend.zip | cut -f1)
FILE_COUNT=$(unzip -l ansteches-frontend.zip | tail -1 | awk '{print $2}')

# Print summary
echo ""
echo "🎉 BUILD COMPLETED SUCCESSFULLY!"
echo "================================="
echo "📦 Deployment file: ansteches-frontend.zip"
echo "📊 File size: $ZIP_SIZE"
echo "📁 Total files: $FILE_COUNT"
echo ""
echo "📋 NEXT STEPS:"
echo "1. Upload ansteches-frontend.zip to your cPanel File Manager"
echo "2. Extract the zip file in your domain directory (e.g., /public_html/booking/)"
echo "3. Ensure your Laravel backend is running at https://ansteches.shop/api"
echo "4. Test the frontend by visiting your domain"
echo ""
echo "🔗 Integration checklist:"
echo "✓ Laravel API running"
echo "✓ CORS configured"
echo "✓ storage-serve.php uploaded"
echo "✓ .htaccess configured"
echo ""
print_success "Your Ansteches Booking Platform frontend is ready for deployment!"

# Cleanup temporary deployment directory
rm -rf deployment

exit 0