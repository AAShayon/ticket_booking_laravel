<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Laravel Web Request Debug</h1>";

// Set up error handler to capture Laravel errors
set_error_handler(function($severity, $message, $file, $line) {
    echo "<h2>PHP Error Captured:</h2>";
    echo "<strong>Message:</strong> $message<br>";
    echo "<strong>File:</strong> $file<br>";
    echo "<strong>Line:</strong> $line<br>";
    echo "<strong>Severity:</strong> $severity<br><br>";
});

set_exception_handler(function($exception) {
    echo "<h2>Exception Captured:</h2>";
    echo "<strong>Message:</strong> " . $exception->getMessage() . "<br>";
    echo "<strong>File:</strong> " . $exception->getFile() . "<br>";
    echo "<strong>Line:</strong> " . $exception->getLine() . "<br>";
    echo "<strong>Trace:</strong><pre>" . $exception->getTraceAsString() . "</pre>";
});

try {
    echo "1. Loading vendor/autoload.php...<br>";
    require __DIR__ . '/vendor/autoload.php';
    echo "✅ Autoloader loaded<br>";
    
    echo "2. Loading bootstrap/app.php...<br>";
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ Laravel app loaded<br>";
    
    echo "3. Creating HTTP request...<br>";
    $request = \Illuminate\Http\Request::capture();
    echo "✅ Request created: " . $request->getMethod() . " " . $request->getRequestUri() . "<br>";
    
    echo "4. Attempting to handle request...<br>";
    
    // This is where the actual error likely occurs
    ob_start();
    $response = $app->handleRequest($request);
    $output = ob_get_clean();
    
    echo "✅ Request handled successfully!<br>";
    echo "<h2>Response Details:</h2>";
    echo "Status: " . $response->getStatusCode() . "<br>";
    echo "Content Length: " . strlen($response->getContent()) . " bytes<br>";
    
    if (strlen($response->getContent()) < 1000) {
        echo "Content Preview:<br><pre>" . htmlspecialchars(substr($response->getContent(), 0, 500)) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Laravel Error Caught:</h2>";
    echo "<strong>Error:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>File:</strong> " . $e->getFile() . "<br>";
    echo "<strong>Line:</strong> " . $e->getLine() . "<br>";
    echo "<strong>Class:</strong> " . get_class($e) . "<br>";
    
    echo "<h3>Full Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    
    // Check if it's a specific Laravel error
    if ($e instanceof \Illuminate\Contracts\Container\BindingResolutionException) {
        echo "<br><h3>💡 This is a Laravel Service Container error - usually configuration or missing service.</h3>";
    } elseif ($e instanceof \Illuminate\Database\QueryException) {
        echo "<br><h3>💡 This is a Database error - check .env database settings.</h3>";
    }
}

echo "<br><h2>Environment Check:</h2>";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'not set') . "<br>";
echo "APP_DEBUG: " . (getenv('APP_DEBUG') ?: 'not set') . "<br>";
echo "APP_KEY: " . (getenv('APP_KEY') ? 'set (' . strlen(getenv('APP_KEY')) . ' chars)' : 'not set') . "<br>";
echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'not set') . "<br>";
?>