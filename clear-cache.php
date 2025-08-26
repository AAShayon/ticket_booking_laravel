<?php
// Laravel Cache Clear Script
// This script clears Laravel cached configuration files that might contain incorrect paths

echo "<h1>Laravel Cache Clear Utility</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;}</style>";

$cache_files = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes-v7.php', 
    'bootstrap/cache/events.php',
    'bootstrap/cache/views.php',
    'bootstrap/cache/services.php',
    'bootstrap/cache/packages.php'
];

echo "<h2>Clearing Laravel Cache Files</h2>";

foreach ($cache_files as $file) {
    if (file_exists($file)) {
        $deleted = unlink($file);
        $status = $deleted ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
        echo "{$status} Deleted {$file}<br>";
    } else {
        echo "<span style='color:orange;'>-</span> {$file} (not found)<br>";
    }
}

// Clear storage cache if writable
echo "<h2>Clearing Storage Cache</h2>";
$storage_cache_dirs = [
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views'
];

foreach ($storage_cache_dirs as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        $files = glob($dir . '/*');
        $deleted_count = 0;
        foreach ($files as $file) {
            if (is_file($file) && unlink($file)) {
                $deleted_count++;
            }
        }
        echo "<span class='success'>✓</span> Cleared {$deleted_count} files from {$dir}<br>";
    } else {
        echo "<span style='color:orange;'>-</span> {$dir} (not accessible)<br>";
    }
}

echo "<h2>Cache Clear Complete</h2>";
echo "1. Laravel configuration cache has been cleared<br>";
echo "2. Try accessing your Laravel application again<br>";
echo "3. If still getting 500 errors, run the server-debug.php script<br>";

echo "<hr><small>Completed: " . date('Y-m-d H:i:s') . "</small>";
?>