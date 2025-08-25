# CPANEL DEPLOYMENT INSTRUCTIONS
# FOR HOSTS WHERE DOCUMENT ROOT CANNOT BE CHANGED

## PROBLEM SOLVED:
Your hosting provider does not allow Document Root changes, so we've restructured the package to work with Document Root = /public_html

## NEW DIRECTORY STRUCTURE:
After extraction, your /public_html will look like this:

```
/public_html/ (Document Root - your domain points here)
├── index.php (Laravel entry point - from root-index.php)
├── .htaccess (Laravel routing - from root-htaccess)
├── favicon.ico (from public directory)
├── robots.txt (from public directory)
├── laravel/ (ALL Laravel framework files)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   ├── artisan
│   └── ... (all other Laravel files)
```

## STEP-BY-STEP DEPLOYMENT:

### Step 1: Clean Up Existing Files
1. Login to cPanel File Manager
2. Navigate to /public_html/
3. DELETE everything currently in /public_html/
4. Make sure /public_html/ is completely empty

### Step 2: Extract New Package
1. Upload Laravel-CPANEL-RESTRUCTURED.zip to /public_html/
2. Extract the zip file
3. The files will be placed in correct structure automatically

### Step 3: Set Permissions
Set these permissions in File Manager:
- All directories: 755
- All files: 644
- /laravel/storage/ directory: 775 (recursive)
- /laravel/bootstrap/cache/ directory: 775

### Step 4: Test Your Site
After extraction and permission setup:
- http://ansteches.shop/ → Laravel welcome page
- http://ansteches.shop/api/user → 401 Unauthorized (not 403)
- http://ansteches.shop/api/documentation → Swagger UI

## WHAT THIS PACKAGE CONTAINS:

1. **ROOT FILES** (go in /public_html/):
   - index.php (Laravel entry point, points to /laravel/ directory)
   - .htaccess (Laravel URL routing)
   - favicon.ico, robots.txt

2. **LARAVEL DIRECTORY** (/public_html/laravel/):
   - Complete Laravel framework
   - All dependencies (vendor/)
   - Production .env file
   - Cached configuration

## WHY THIS WORKS:
- Document Root stays as /public_html/ (no change needed)
- Laravel entry point is now directly in /public_html/
- All Laravel files are safely in /laravel/ subdirectory
- No .htaccess redirect conflicts
- Direct access to Laravel application

Your APIs will work immediately after deployment!