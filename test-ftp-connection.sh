#!/bin/bash

echo "🔧 FTP Connection Test Script"
echo "============================="
echo ""
echo "Server: ftp.ansteches.shop"
echo "Username: ansteche"
echo ""
echo "This script will test different connection methods."
echo "Enter your cPanel password when prompted."
echo ""

echo "📋 Method 1: Testing SFTP connection..."
echo "----------------------------------------"
read -p "Press Enter to test SFTP connection, then enter your password when prompted: "
sftp ansteche@ftp.ansteches.shop << EOF
pwd
ls
quit
EOF

echo ""
echo "📋 Method 2: Testing FTP connection..."
echo "--------------------------------------"
read -p "Press Enter to test FTP connection, then enter your password when prompted: "
ftp -n ftp.ansteches.shop << EOF
user ansteche
pwd
ls
quit
EOF

echo ""
echo "📋 Method 3: Testing with cURL..."
echo "----------------------------------"
read -p "Press Enter to test cURL connection, then enter your password when prompted: "
curl -u ansteche ftp://ftp.ansteches.shop/

echo ""
echo "✅ Test completed!"
echo ""
echo "If all methods failed, possible issues:"
echo "1. Wrong password (check your cPanel password)"
echo "2. Wrong username (check if it's exactly 'ansteche')"
echo "3. FTP access might be disabled by your hosting provider"
echo "4. Your IP might need to be whitelisted"
echo ""
echo "💡 Alternative: Continue using cPanel File Manager for uploads"