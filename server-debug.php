<?php
// Laravel Server Debug Script
// This will help identify the exact cause of 500 errors

echo "<h1>Laravel Server Diagnostic</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// 1. Check PHP version and extensions
echo "<h2>1. PHP Environment</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";

$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json'];
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
    echo "{$status} {$ext}<br>";
}

// 2. Check Laravel files
echo "<h2>2. Laravel File Structure</h2>";
$laravel_files = [
    'vendor/autoload.php',
    'bootstrap/app.php', 
    '.env',
    'public/index.php'
];

foreach ($laravel_files as $file) {
    $exists = file_exists($file);
    $status = $exists ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
    echo "{$status} {$file}";
    if ($exists) {
        echo " (Size: " . filesize($file) . " bytes)";
    }
    echo "<br>";
}

// 3. Check .env file content (safely)
echo "<h2>3. Environment Configuration</h2>";
if (file_exists('.env')) {
    $env_content = file_get_contents('.env');
    
    // Check critical settings (without exposing sensitive data)
    $checks = [
        'APP_ENV' => 'APP_ENV',
        'APP_DEBUG' => 'APP_DEBUG', 
        'APP_KEY' => 'APP_KEY',
        'DB_CONNECTION' => 'DB_CONNECTION',
        'DB_HOST' => 'DB_HOST',
        'DB_DATABASE' => 'DB_DATABASE',
        'DB_USERNAME' => 'DB_USERNAME'
    ];
    
    foreach ($checks as $key => $pattern) {
        $found = strpos($env_content, $pattern) !== false;
        $status = $found ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
        echo "{$status} {$key} configured<br>";
    }
} else {
    echo '<span class="error">✗ .env file not found</span><br>';
}

// 4. Test database connection
echo "<h2>4. Database Connection Test</h2>";
try {
    if (file_exists('.env')) {
        // Load .env manually for testing
        $env_lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env_vars = [];
        foreach ($env_lines as $line) {
            if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
                list($key, $value) = explode('=', $line, 2);
                $env_vars[trim($key)] = trim($value, '"\'');
            }
        }
        
        if (isset($env_vars['DB_HOST'], $env_vars['DB_DATABASE'], $env_vars['DB_USERNAME'], $env_vars['DB_PASSWORD'])) {
            $pdo = new PDO(
                "mysql:host={$env_vars['DB_HOST']};dbname={$env_vars['DB_DATABASE']}", 
                $env_vars['DB_USERNAME'], 
                $env_vars['DB_PASSWORD']
            );
            echo '<span class="success">✓ Database connection successful</span><br>';
            
            // Test basic query
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
            $result = $stmt->fetch();
            echo "Users table has {$result['count']} records<br>";
            
        } else {
            echo '<span class="error">✗ Database configuration incomplete</span><br>';
        }
    }
} catch (Exception $e) {
    echo '<span class="error">✗ Database connection failed: ' . htmlspecialchars($e->getMessage()) . '</span><br>';
}

// 5. Check storage permissions
echo "<h2>5. Storage Permissions</h2>";
$storage_paths = [
    'storage/app',
    'storage/app/public', 
    'storage/framework',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($storage_paths as $path) {
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $writable = is_writable($path);
        $status = $writable ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
        echo "{$status} {$path} (Permissions: {$perms})" . ($writable ? '' : ' - Not writable') . "<br>";
    } else {
        echo '<span class="error">✗</span> ' . $path . ' - Directory not found<br>';
    }
}

// 6. Test Laravel bootstrap
echo "<h2>6. Laravel Bootstrap Test</h2>";
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo '<span class="success">✓ Composer autoload successful</span><br>';
        
        if (file_exists('bootstrap/app.php')) {
            echo '<span class="success">✓ Laravel bootstrap file exists</span><br>';
            // Note: We don't actually bootstrap Laravel here to avoid conflicts
        }
    } else {
        echo '<span class="error">✗ Composer autoload not found</span><br>';
    }
} catch (Exception $e) {
    echo '<span class="error">✗ Bootstrap error: ' . htmlspecialchars($e->getMessage()) . '</span><br>';
}

echo "<h2>7. Recommendations</h2>";
echo "1. Upload this script to your server and access it via browser<br>";
echo "2. Check the results above for any red ✗ errors<br>";  
echo "3. Fix database connection if needed<br>";
echo "4. Ensure proper file permissions<br>";
echo "5. Clear Laravel cache if needed<br>";

echo "<hr><small>Generated: " . date('Y-m-d H:i:s') . "</small>";
?>