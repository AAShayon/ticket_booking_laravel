# FTP Deployment Guide for Laravel Ticket Booking System

## 🔑 FTP Connection Details
- **Server:** ftp.ansteches.shop
- **Username:** ansteche  
- **Password:** [your cPanel password]
- **Port:** 21
- **Root Path:** /home/ansteche
- **Web Root:** /home/ansteche/public_html

## 📦 Quick FTP Deployment Steps

### 1. Connect via FTP
```bash
# Terminal method
ftp ftp.ansteches.shop
# Enter username: ansteche
# Enter password: [your password]
```

### 2. Navigate to Web Directory
```bash
cd public_html
```

### 3. Upload Laravel Files
**Upload these essential files from your local project:**

#### Core Laravel Files:
- `index.php` (from CORRECTED-index.php)
- `.htaccess` (cPanel-compatible version)
- `.env` (production version with correct database settings)

#### Laravel Framework:
- `vendor/` (all dependencies)
- `app/` (application code)
- `config/` (configuration files)
- `routes/` (route definitions)
- `database/` (migrations, seeders)
- `storage/` (with proper permissions)
- `bootstrap/` (framework bootstrap)

#### Static Assets:
- `public/` contents (CSS, JS, images)

### 4. Set Permissions via FTP
Most FTP clients allow permission changes:
- **Directories:** 755
- **Files:** 644  
- **Storage directory:** 775
- **Bootstrap/cache:** 775

### 5. Upload Database Setup
Upload and run via browser:
- `complete-database-setup.sql` (run in phpMyAdmin)
- `insert-users.sql` (create admin users)

## 📋 FTP Client Recommendations

### FileZilla (Free, Cross-platform)
```
Host: ftp.ansteches.shop
Username: ansteche
Password: [cPanel password]
Port: 21
Protocol: FTP
```

### macOS Built-in
```
ftp://ansteche@ftp.ansteches.shop
```

### Windows Built-in
```
Computer → Add Network Location
ftp://ftp.ansteches.shop
```

## 🔄 File Upload Priority Order

1. **Core files first:**
   - index.php, .htaccess, .env

2. **Framework dependencies:**
   - vendor/ directory (may take time)

3. **Application code:**
   - app/, config/, routes/, database/

4. **Storage and cache:**
   - storage/ (ensure 775 permissions)
   - bootstrap/cache/

5. **Static assets:**
   - public/ contents

## ⚠️ Important Notes

### File Permissions
After upload, ensure correct permissions:
```
/home/ansteche/public_html/          (755)
/home/ansteche/public_html/storage/  (775)
/home/ansteche/public_html/.env      (644)
```

### Database Connection
Update .env with correct database name:
```
DB_DATABASE=ansteche_ticket_booking
DB_USERNAME=ansteche_ticket_booking  
DB_PASSWORD=AmiShayon
```

### Large File Transfers
- vendor/ directory is large (~50MB+)
- Consider uploading in chunks
- Use resume functionality if available

## 🧪 Post-Upload Testing
After FTP upload, test:
1. **Main site:** https://ansteches.shop/
2. **API login:** https://ansteches.shop/api/login
3. **Documentation:** https://ansteches.shop/api/documentation

## 📁 Recommended FTP Structure
```
/home/ansteche/public_html/
├── index.php              (Laravel entry point)
├── .htaccess              (URL rewriting)
├── .env                   (environment config)
├── app/                   (Laravel application)
├── vendor/                (Composer dependencies)
├── config/                (Laravel configuration)
├── routes/                (API and web routes)
├── database/              (migrations, seeders)
├── storage/               (logs, cache, uploads)
├── bootstrap/             (framework bootstrap)
└── public/                (static assets)
```

## 🔧 Troubleshooting FTP Issues

### Connection Refused
- Check server address: ftp.ansteches.shop
- Verify port 21 is not blocked
- Try passive mode if behind firewall

### Permission Denied
- Ensure you're in /home/ansteche/public_html
- Check if files already exist (may need to overwrite)

### Upload Failures
- Large files may timeout
- Use binary mode for PHP files
- Check available disk space (5120 MB limit)

## 💾 Backup Before Upload
Always backup existing files:
```bash
# Create backup directory
mkdir backup_$(date +%Y%m%d)
# Move existing files to backup
mv * backup_$(date +%Y%m%d)/
```

---
**Ready to deploy your Laravel Ticket Booking System via FTP! 🚀**