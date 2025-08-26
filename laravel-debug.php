<?php
// Laravel Error Debug Script
// This will show the actual Laravel error by enabling debug mode temporarily

echo "<h1>Laravel Error Debug</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .error{color:red;background:#ffe6e6;padding:10px;border:1px solid red;}</style>";

try {
    // Set debug mode temporarily
    putenv('APP_DEBUG=true');
    $_ENV['APP_DEBUG'] = true;
    
    // Load Laravel
    require_once 'vendor/autoload.php';
    
    // Get the Laravel app
    $app = require_once 'bootstrap/app.php';
    
    // Enable debug mode in config
    $app->make('config')->set('app.debug', true);
    
    echo "<h2>Laravel Bootstrap Test</h2>";
    echo "✓ Laravel loaded successfully<br>";
    echo "✓ Debug mode enabled<br>";
    
    // Try to handle a basic request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    echo "✓ HTTP Kernel created<br>";
    
    // Create a test request
    $request = Illuminate\Http\Request::create('/', 'GET');
    
    echo "✓ Request created<br>";
    
    // Handle the request (this is where the error likely occurs)
    echo "<h2>Handling Request...</h2>";
    $response = $kernel->handle($request);
    
    echo "✓ Request handled successfully<br>";
    echo "Response Status: " . $response->getStatusCode() . "<br>";
    
} catch (Throwable $e) {
    echo "<div class='error'>";
    echo "<h2>Laravel Error Detected:</h2>";
    echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . "<br>";
    echo "<strong>Line:</strong> " . $e->getLine() . "<br>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

echo "<hr><small>Debug completed: " . date('Y-m-d H:i:s') . "</small>";
?>