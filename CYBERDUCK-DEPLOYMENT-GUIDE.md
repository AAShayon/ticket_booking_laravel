# Cyberduck FTP/SFTP Deployment Guide for Laravel

## 🎯 Setup Instructions

### 1. Install Cyberduck
```bash
brew install --cask cyberduck
```

### 2. Import Configuration Files
1. Open Cyberduck
2. File → Import (or drag config files)
3. Import both FTP and SFTP configuration files
4. Try SFTP connection first (more secure)

### 3. Connect to Server
1. Double-click the imported connection
2. Enter your cPanel password when prompted
3. Navigate to `/public_html/` directory

## 📁 Laravel File Upload Order

### Priority 1: Core Files
Upload these essential files first:
```
.env                    (production environment)
index.php              (from CORRECTED-index.php)
.htaccess              (cPanel-compatible version)
```

### Priority 2: Framework Files
```
vendor/                 (Composer dependencies - large folder)
app/                   (Laravel application code)
config/                (Configuration files)
routes/                (API and web routes)
bootstrap/             (Framework bootstrap)
```

### Priority 3: Database & Storage
```
database/              (migrations, seeders)
storage/               (ensure 775 permissions)
```

### Priority 4: Static Assets
```
public/                (CSS, JS, images - if any)
```

## 🔧 Cyberduck Tips for Laravel

### File Permissions
After upload, right-click folders and set permissions:
- **Directories:** 755
- **Files:** 644
- **storage/ directory:** 775
- **bootstrap/cache/:** 775

### Upload Settings
1. **Transfer Mode:** Binary
2. **Preserve timestamps:** Yes
3. **Skip existing files:** No (overwrite)

### Large File Handling
- `vendor/` directory is ~50MB
- Enable "Resume transfers" in Preferences
- Use "Continue" if upload is interrupted

## 📊 Upload Checklist

### Before Upload:
- [ ] Clean .env file created (no local paths)
- [ ] CORRECTED-index.php ready
- [ ] .htaccess file prepared
- [ ] Database setup SQL ready

### During Upload:
- [ ] Connect via SFTP (preferred) or FTP
- [ ] Navigate to public_html/
- [ ] Upload core files first
- [ ] Upload framework files
- [ ] Set correct permissions

### After Upload:
- [ ] Test: https://ansteches.shop/
- [ ] Test API: https://ansteches.shop/api/user
- [ ] Test docs: https://ansteches.shop/api/documentation
- [ ] Run database setup SQL in phpMyAdmin

## 🚨 Troubleshooting

### Connection Issues:
- Try SFTP configuration first
- Fall back to FTP if SFTP fails
- Check if password is correct
- Verify server address in config

### Upload Issues:
- Check available disk space (5120 MB limit)
- Ensure binary transfer mode
- Try uploading in smaller batches

### Permission Issues:
- Use Cyberduck's "Get Info" to set permissions
- Ensure storage/ has 775 permissions
- Check that .env file is readable (644)

## 💡 Pro Tips

1. **Backup First:** Download existing files before overwriting
2. **Selective Upload:** Only upload changed files after initial deployment
3. **Sync Feature:** Use Cyberduck's sync feature for updates
4. **Bookmarks:** Save working connection as bookmark

## 🎯 Expected Results

After successful upload:
- Laravel welcome page at https://ansteches.shop/
- API endpoints working with authentication
- Swagger documentation accessible
- Database connected and users can login

---
**Ready to deploy your Laravel Ticket Booking System with Cyberduck! 🚀**