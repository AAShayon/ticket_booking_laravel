# macOS FTP Connection Guide for Laravel Deployment

## 🚨 **Issue: macOS doesn't include FTP command by default**

## ✅ **Solution Options:**

### **Option 1: Install FTP Command (Terminal)**
```bash
# Install Homebrew (if not installed)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install ftp command
brew install inetutils

# Test FTP connection
ftp ftp.ansteches.shop
```

**When connected, you'll see:**
```
Connected to ftp.ansteches.shop.
220 ansteches.shop FTP server ready.
Name (ftp.ansteches.shop:your-local-username): ansteche
331 Password required for ansteche.
Password: [enter your cPanel password here]
230 Login successful.
Remote system type is UNIX.
Using binary mode to transfer files.
ftp> cd public_html
250 Directory successfully changed.
ftp> ls
227 Entering Passive Mode
150 Here comes the directory listing.
[files will be listed here]
226 Directory send OK.
ftp> quit
221 Goodbye.
```

### **Option 2: Use SFTP (Built-in, Recommended)**
```bash
# Connect via SFTP (more secure)
sftp ansteche@ftp.ansteches.shop

# You'll be prompted for password
# Enter your cPanel password when asked
```

**SFTP Session Example:**
```bash
$ sftp ansteche@ftp.ansteches.shop
ansteche@ftp.ansteches.shop's password: [enter password]
Connected to ftp.ansteches.shop.

sftp> cd public_html
sftp> ls -la
sftp> put localfile.php
sftp> get remotefile.php
sftp> quit
```

### **Option 3: Use cURL for FTP (Built-in)**
```bash
# Test connection and list files
curl -u ansteche ftp://ftp.ansteches.shop/public_html/

# Upload a single file
curl -u ansteche -T myfile.php ftp://ftp.ansteches.shop/public_html/

# Download a file
curl -u ansteche ftp://ftp.ansteches.shop/public_html/index.php -o downloaded_index.php
```

**When using cURL, you'll be prompted:**
```
Enter host password for user 'ansteche':
[enter your cPanel password]
```

### **Option 4: FileZilla (GUI - Easiest)**

**Download & Install:**
```bash
# Download FileZilla
open https://filezilla-project.org/download.php?type=client

# Or install via Homebrew
brew install --cask filezilla
```

**FileZilla Connection Settings:**
```
Host: ftp.ansteches.shop
Username: ansteche
Password: [your cPanel password]
Port: 21
Protocol: FTP
```

### **Option 5: macOS Finder (Built-in GUI)**
```bash
# Press Cmd+K in Finder, then enter:
ftp://ansteche@ftp.ansteches.shop

# Enter password when prompted
# Navigate to public_html folder
```

## 🎯 **Quick Test Commands (Choose One):**

### **Test 1: SFTP Connection**
```bash
sftp ansteche@ftp.ansteches.shop
# Enter password when prompted
# Type: pwd (shows current directory)
# Type: ls (shows files)
# Type: quit (to exit)
```

### **Test 2: cURL Connection**
```bash
curl -u ansteche ftp://ftp.ansteches.shop/
# Enter password when prompted
# Should show directory listing
```

### **Test 3: After Installing FTP**
```bash
brew install inetutils
ftp ftp.ansteches.shop
# Username: ansteche
# Password: [your cPanel password]
# Type: pwd
# Type: ls
# Type: quit
```

## 📋 **Your FTP Credentials Summary:**
```
Server: ftp.ansteches.shop
Username: ansteche
Password: [your cPanel password - you'll enter this when prompted]
Port: 21
Root Directory: /home/ansteche
Web Directory: /home/ansteche/public_html
```

## 🚀 **Laravel Upload Commands (After Connection):**

### **Using SFTP:**
```bash
sftp ansteche@ftp.ansteches.shop
sftp> cd public_html
sftp> put .env
sftp> put index.php
sftp> put .htaccess
sftp> put -r vendor/
sftp> put -r app/
sftp> put -r config/
sftp> quit
```

### **Using cURL (Single Files):**
```bash
# Upload .env file
curl -u ansteche -T .env ftp://ftp.ansteches.shop/public_html/

# Upload index.php
curl -u ansteche -T CORRECTED-index.php ftp://ftp.ansteches.shop/public_html/index.php

# Upload .htaccess
curl -u ansteche -T .htaccess ftp://ftp.ansteches.shop/public_html/
```

## ⚠️ **Important Notes:**

1. **Password Entry:** You'll be prompted to enter your cPanel password - it won't show as you type (security feature)

2. **Directory Navigation:** Always navigate to `public_html` after connecting:
   ```bash
   cd public_html
   ```

3. **File Permissions:** After upload, you may need to set permissions via cPanel

4. **Large Files:** `vendor/` directory is large - may take time to upload

## 🧪 **Test Your Connection Now:**

**Run this command and enter your password when prompted:**
```bash
sftp ansteche@ftp.ansteches.shop
```

**Once connected, test with:**
```bash
pwd
ls -la
cd public_html
ls -la
quit
```

Choose the method that works best for you! SFTP is recommended as it's built-in and secure.