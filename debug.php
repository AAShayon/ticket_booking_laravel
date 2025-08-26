<?php
// Debug file for Laravel deployment issues
echo "<h1>Laravel Deployment Debug</h1>";

echo "<h2>Server Information</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Current Directory: " . getcwd() . "<br>";

echo "<h2>File System Check</h2>";
echo "Public directory exists: " . (is_dir('./public') ? 'YES' : 'NO') . "<br>";
echo "Public/index.php exists: " . (file_exists('./public/index.php') ? 'YES' : 'NO') . "<br>";
echo "Vendor directory exists: " . (is_dir('./vendor') ? 'YES' : 'NO') . "<br>";
echo "Bootstrap directory exists: " . (is_dir('./bootstrap') ? 'YES' : 'NO') . "<br>";

echo "<h2>Permissions Check</h2>";
echo "Public directory permissions: " . substr(sprintf('%o', fileperms('./public')), -4) . "<br>";
if (file_exists('./public/index.php')) {
    echo "Public/index.php permissions: " . substr(sprintf('%o', fileperms('./public/index.php')), -4) . "<br>";
}
echo "Storage directory writable: " . (is_writable('./storage') ? 'YES' : 'NO') . "<br>";
echo "Bootstrap/cache writable: " . (is_writable('./bootstrap/cache') ? 'YES' : 'NO') . "<br>";

echo "<h2>Rewrite Module</h2>";
echo "mod_rewrite loaded: " . (in_array('mod_rewrite', apache_get_modules()) ? 'YES' : 'Unknown') . "<br>";

echo "<h2>Environment</h2>";
echo "ENV file exists: " . (file_exists('./.env') ? 'YES' : 'NO') . "<br>";

if (file_exists('./.env')) {
    $env = file_get_contents('./.env');
    if (strpos($env, 'APP_ENV=production') !== false) {
        echo "APP_ENV: production<br>";
    }
    if (strpos($env, 'APP_DEBUG=false') !== false) {
        echo "APP_DEBUG: false<br>";
    }
}

echo "<h2>Test Laravel Bootstrap</h2>";
try {
    if (file_exists('./vendor/autoload.php')) {
        require './vendor/autoload.php';
        echo "Composer autoload: SUCCESS<br>";
        
        if (file_exists('./bootstrap/app.php')) {
            $app = require './bootstrap/app.php';
            echo "Laravel bootstrap: SUCCESS<br>";
        } else {
            echo "Laravel bootstrap: FAILED - bootstrap/app.php not found<br>";
        }
    } else {
        echo "Composer autoload: FAILED - vendor/autoload.php not found<br>";
    }
} catch (Exception $e) {
    echo "Laravel bootstrap ERROR: " . $e->getMessage() . "<br>";
}

echo "<h2>Recommendations</h2>";
echo "1. Ensure your domain points to this directory (not /public)<br>";
echo "2. Check that .htaccess files are being processed<br>";
echo "3. Verify all file permissions are correct<br>";
echo "4. Check error logs for more details<br>";
?>