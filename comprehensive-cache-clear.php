<?php
// Comprehensive Laravel Cache Clear Script
echo "<h1>Laravel Cache Clear - Complete</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;}</style>";

// 1. Clear all Laravel cache files
$cache_files = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes-v7.php',
    'bootstrap/cache/routes.php', 
    'bootstrap/cache/events.php',
    'bootstrap/cache/views.php',
    'bootstrap/cache/services.php',
    'bootstrap/cache/packages.php'
];

echo "<h2>1. Clearing Bootstrap Cache Files</h2>";
foreach ($cache_files as $file) {
    if (file_exists($file)) {
        $deleted = unlink($file);
        $status = $deleted ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
        echo "{$status} Deleted {$file}<br>";
    } else {
        echo "<span style='color:orange;'>-</span> {$file} (not found)<br>";
    }
}

// 2. Clear storage cache directories
echo "<h2>2. Clearing Storage Cache</h2>";
$cache_dirs = [
    'storage/framework/cache/data',
    'storage/framework/sessions', 
    'storage/framework/views'
];

foreach ($cache_dirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $deleted = 0;
        foreach ($files as $file) {
            if (is_file($file) && unlink($file)) {
                $deleted++;
            }
        }
        echo "<span class='success'>✓</span> Cleared {$deleted} files from {$dir}<br>";
    } else {
        echo "<span style='color:orange;'>-</span> {$dir} (not found)<br>";
    }
}

// 3. Clear application cache if accessible
echo "<h2>3. Clearing Application Cache</h2>";
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        
        // Clear various caches
        if (method_exists($app, 'make')) {
            try {
                $cache = $app->make('cache');
                $cache->flush();
                echo "<span class='success'>✓</span> Application cache cleared<br>";
            } catch (Exception $e) {
                echo "<span style='color:orange;'>-</span> Application cache: " . $e->getMessage() . "<br>";
            }
        }
    }
} catch (Exception $e) {
    echo "<span style='color:orange;'>-</span> Could not clear application cache: " . $e->getMessage() . "<br>";
}

// 4. Force refresh by touching route file
echo "<h2>4. Force Route Refresh</h2>";
if (file_exists('routes/web.php')) {
    touch('routes/web.php');
    echo "<span class='success'>✓</span> Touched routes/web.php to force refresh<br>";
}

echo "<h2>Cache Clear Complete!</h2>";
echo "All Laravel caches have been cleared.<br>";
echo "Routes, config, views, and application cache cleared.<br>";
echo "<strong>Try accessing your site again now.</strong><br>";

echo "<hr><small>Completed: " . date('Y-m-d H:i:s') . "</small>";
?>