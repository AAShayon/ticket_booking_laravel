# Laravel cPanel Restructuring Guide

## Current Problem:
- Laravel files are in /public_html/ (root)
- Document Root points to /public_html/
- .htaccess tries to redirect to /public/ but creates conflicts

## Solution 1: Change Document Root (RECOMMENDED)
1. Go to cPanel » Domains » List Domains
2. Click "Manage" next to ansteches.shop
3. Change Document Root from "/public_html" to "/public_html/public"
4. Save changes
5. Test: http://ansteches.shop/

## Solution 2: File Restructuring (if Document Root cannot be changed)

### Step 1: Create new structure via cPanel File Manager
1. Create directory: /public_html/laravel-app/
2. Move ALL current files EXCEPT 'public' folder to /laravel-app/
3. Copy contents of /public_html/public/ to /public_html/ (root)
4. Delete the now-empty /public_html/public/ directory

### Step 2: Update index.php in root
Edit /public_html/index.php and change:
```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```
TO:
```php
require __DIR__.'/laravel-app/vendor/autoload.php';
$app = require_once __DIR__.'/laravel-app/bootstrap/app.php';
```

### Step 3: Remove problematic .htaccess
Delete /public_html/.htaccess (the one with rewrite rules)
Keep only the .htaccess from Laravel's public directory

### Final Structure:
```
/public_html/ (Document Root)
├── index.php (Laravel entry point)
├── .htaccess (Laravel public .htaccess)
├── favicon.ico
├── robots.txt
├── laravel-app/
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

## Testing:
After implementing either solution:
1. http://ansteches.shop/ → Laravel welcome page
2. http://ansteches.shop/api/user → Authentication error (not 403)
3. http://ansteches.shop/api/documentation → Swagger UI