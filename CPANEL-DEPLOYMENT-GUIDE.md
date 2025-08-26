# Laravel cPanel Deployment Guide
# For hosting without terminal access

## Current Status: 403 Forbidden on ALL requests
This indicates a server-level configuration issue, not a Laravel issue.

## Quick Fix Steps:

### Step 1: Check cPanel Document Root
1. Login to cPanel
2. Go to "File Manager"
3. Check if domain points to `/public_html`
4. Verify files are actually uploaded there

### Step 2: Alternative Deployment Structure
If standard deployment doesn't work, use this structure:

```
public_html/
├── index.php (rename cpanel-index.php to this)
├── laravel-app/ (extract full Laravel project here)
│   ├── app/
│   ├── public/
│   ├── vendor/
│   ├── .env
│   └── ... (all Laravel files)
└── .htaccess (optional)
```

### Step 3: File Permissions via cPanel
Set these permissions in File Manager:
- Directories: 755
- Files: 644  
- storage/: 775 (and all subdirectories)
- bootstrap/cache/: 775

### Step 4: Contact Hosting Support
If still getting 403, contact your hosting provider about:
1. Document root configuration
2. File ownership issues  
3. Server-level access restrictions
4. LiteSpeed configuration

## Test URLs After Fix:
- http://ansteches.shop/ (should show Laravel welcome)
- http://ansteches.shop/api/user (should show authentication error, not 403)
- http://ansteches.shop/api/documentation (Swagger UI)

## Common Hosting Issues:
1. Domain not properly pointed to public_html
2. Files uploaded to wrong directory
3. Incorrect file ownership
4. Server security restrictions