<?php
echo "<h1>Laravel Configuration Fix</h1>";

// 1. Check .env file
echo "<h2>1. Environment File Check:</h2>";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo "✅ .env file exists<br>";
    $envSize = filesize($envFile);
    echo "Size: $envSize bytes<br>";
    
    if (is_readable($envFile)) {
        echo "✅ .env file is readable<br>";
        
        // Check key environment variables
        $envContent = file_get_contents($envFile);
        $hasAppKey = strpos($envContent, 'APP_KEY=') !== false;
        $hasAppEnv = strpos($envContent, 'APP_ENV=') !== false;
        $hasAppDebug = strpos($envContent, 'APP_DEBUG=') !== false;
        
        echo "APP_KEY present: " . ($hasAppKey ? 'YES' : 'NO') . "<br>";
        echo "APP_ENV present: " . ($hasAppEnv ? 'YES' : 'NO') . "<br>";
        echo "APP_DEBUG present: " . ($hasAppDebug ? 'YES' : 'NO') . "<br>";
    } else {
        echo "❌ .env file is not readable<br>";
    }
} else {
    echo "❌ .env file missing<br>";
}

// 2. Clear Laravel caches
echo "<h2>2. Clearing Laravel Caches:</h2>";

// Clear config cache
$configCache = __DIR__ . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    if (unlink($configCache)) {
        echo "✅ Deleted config cache<br>";
    } else {
        echo "❌ Failed to delete config cache<br>";
    }
} else {
    echo "✅ Config cache doesn't exist<br>";
}

// Clear route cache
$routeCache = __DIR__ . '/bootstrap/cache/routes-v7.php';
if (file_exists($routeCache)) {
    if (unlink($routeCache)) {
        echo "✅ Deleted route cache<br>";
    } else {
        echo "❌ Failed to delete route cache<br>";
    }
} else {
    echo "✅ Route cache doesn't exist<br>";
}

// Clear services cache  
$servicesCache = __DIR__ . '/bootstrap/cache/services.php';
if (file_exists($servicesCache)) {
    if (unlink($servicesCache)) {
        echo "✅ Deleted services cache<br>";
    } else {
        echo "❌ Failed to delete services cache<br>";
    }
} else {
    echo "✅ Services cache doesn't exist<br>";
}

// 3. Ensure storage directories exist with correct permissions
echo "<h2>3. Storage Directory Fix:</h2>";
$storageDirs = [
    '/storage',
    '/storage/logs',
    '/storage/framework',
    '/storage/framework/cache',
    '/storage/framework/cache/data',
    '/storage/framework/sessions',
    '/storage/framework/views',
    '/bootstrap/cache'
];

foreach ($storageDirs as $dir) {
    $fullPath = __DIR__ . $dir;
    if (!is_dir($fullPath)) {
        if (mkdir($fullPath, 0775, true)) {
            echo "✅ Created $dir<br>";
        } else {
            echo "❌ Failed to create $dir<br>";
        }
    } else {
        echo "✅ $dir exists<br>";
    }
    
    // Set permissions
    if (is_dir($fullPath)) {
        chmod($fullPath, 0775);
    }
}

// 4. Test Laravel again
echo "<h2>4. Testing Laravel:</h2>";
try {
    require __DIR__ . '/vendor/autoload.php';
    
    // Clear any existing environment
    if (function_exists('putenv')) {
        putenv('APP_ENV=production');
        putenv('APP_DEBUG=false');
    }
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ Laravel loads successfully<br>";
    
    // Test a simple request
    $request = \Illuminate\Http\Request::create('/', 'GET');
    echo "✅ Request created<br>";
    
    echo "<br><h2>🎉 All fixes applied!</h2>";
    echo "<a href='https://ansteches.shop/'>Test Main Site</a> | ";
    echo "<a href='https://ansteches.shop/api/user'>Test API</a><br>";
    
} catch (Exception $e) {
    echo "❌ Still error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
}
?>