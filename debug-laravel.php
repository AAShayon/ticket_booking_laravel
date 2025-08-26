<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Laravel Debug Information</h1>";

// Check if files exist
echo "<h2>File Checks:</h2>";
echo ".env exists: " . (file_exists(__DIR__ . '/.env') ? 'YES' : 'NO') . "<br>";
echo "vendor/autoload.php exists: " . (file_exists(__DIR__ . '/vendor/autoload.php') ? 'YES' : 'NO') . "<br>";
echo "bootstrap/app.php exists: " . (file_exists(__DIR__ . '/bootstrap/app.php') ? 'YES' : 'NO') . "<br>";
echo "storage/ writable: " . (is_writable(__DIR__ . '/storage') ? 'YES' : 'NO') . "<br>";

// Check storage directories
echo "<h2>Storage Directory Checks:</h2>";
$storageDirs = [
    '/storage/framework',
    '/storage/framework/cache',
    '/storage/framework/sessions',
    '/storage/framework/views',
    '/storage/logs'
];

foreach ($storageDirs as $dir) {
    $fullPath = __DIR__ . $dir;
    echo $dir . ": " . (is_dir($fullPath) && is_writable($fullPath) ? 'EXISTS & WRITABLE' : 'MISSING OR NOT WRITABLE') . "<br>";
}

// Try to load Laravel
echo "<h2>Laravel Load Test:</h2>";
try {
    require __DIR__ . '/vendor/autoload.php';
    echo "Autoloader: OK<br>";
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "Bootstrap: OK<br>";
    
    echo "Laravel Version: " . $app->version() . "<br>";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "<br>";
}
?>