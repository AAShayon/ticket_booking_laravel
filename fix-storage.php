<?php
echo "<h1>Creating Missing Laravel Directories</h1>";

// Create storage/logs directory
$logsDir = __DIR__ . '/storage/logs';

if (!is_dir($logsDir)) {
    if (mkdir($logsDir, 0775, true)) {
        echo "✅ Created /storage/logs directory<br>";
    } else {
        echo "❌ Failed to create /storage/logs directory<br>";
    }
} else {
    echo "✅ /storage/logs already exists<br>";
}

// Set permissions
if (is_dir($logsDir)) {
    chmod($logsDir, 0775);
    echo "✅ Set permissions on /storage/logs to 775<br>";
}

// Test Laravel again
echo "<h2>Testing Laravel Load:</h2>";
try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ Laravel loads successfully (Version: " . $app->version() . ")<br>";
    echo "<br><strong>🎉 Laravel should now work properly!</strong><br>";
    echo "<br><a href='https://ansteches.shop/'>Test Main Site</a> | ";
    echo "<a href='https://ansteches.shop/api/user'>Test API</a> | ";
    echo "<a href='https://ansteches.shop/api/documentation'>Test Swagger</a><br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>