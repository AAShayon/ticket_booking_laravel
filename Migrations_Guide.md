# Running Laravel Migrations in cPanel

If you're getting a database schema error like `Unknown column 'nid' in 'field list'`, you need to run your migrations to update the database schema. Since you're using cPanel without SSH access, here's how to do it:

## Method 1: Create a Web Migration Runner

1. Create a file called `run_migrations.php` in your project root directory with this content:

```php
<?php
// Migration Runner Script - DELETE AFTER USE FOR SECURITY
// This script runs migrations for the Laravel application

// Set a secure random key to prevent unauthorized access
$secret_key = 'YOUR_SECRET_KEY_HERE';

// Check if the provided key matches
if (isset($_GET['key']) && $_GET['key'] === $secret_key) {
    // Define the application base path
    $basePath = __DIR__;
    
    // Change to the application root directory (if needed)
    chdir($basePath);
    
    // Include the autoloader
    require $basePath . '/vendor/autoload.php';
    
    // Load the Laravel application
    $app = require_once $basePath . '/bootstrap/app.php';
    
    // Get the Artisan kernel
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    // Run migrations
    echo "<h2>Running Migrations</h2>";
    echo "<pre>";
    $exitCode = $kernel->call('migrate', ['--force' => true]);
    echo "</pre>";
    
    echo "<p>Migration exit code: $exitCode</p>";
    
    if ($exitCode === 0) {
        echo "<h3 style='color:green'>Migrations completed successfully!</h3>";
    } else {
        echo "<h3 style='color:red'>Migrations failed with exit code $exitCode.</h3>";
    }
    
    // Show migration status
    echo "<h2>Migration Status</h2>";
    echo "<pre>";
    $kernel->call('migrate:status');
    echo "</pre>";
    
    // Show database schema
    echo "<h2>Database Schema for operators table</h2>";
    echo "<pre>";
    try {
        $columns = DB::select("SHOW COLUMNS FROM operators");
        print_r($columns);
    } catch (Exception $e) {
        echo "Error getting schema: " . $e->getMessage();
    }
    echo "</pre>";
    
    // Terminate the application
    $kernel->terminate(Input::capture(), $exitCode);
} else {
    // If key doesn't match, show access denied
    header('HTTP/1.0 403 Forbidden');
    echo "<h1>Access Denied</h1>";
    echo "<p>Invalid or missing security key.</p>";
}
?>
```

2. Upload this file to your server using cPanel File Manager
3. Visit the URL: `https://ansteches.shop/run_migrations.php?key=YOUR_SECRET_KEY_HERE`
4. This will run all pending migrations and show the current schema
5. **IMPORTANT:** Delete this file immediately after use for security!

## Method 2: Add a Temporary Route

1. Add this code to your `routes/web.php` file:

```php
Route::get('/run-migrations', function () {
    if (request('key') !== 'YOUR_SECRET_KEY_HERE') {
        abort(403, 'Unauthorized');
    }
    
    Artisan::call('migrate', ['--force' => true]);
    
    return response('<h1>Migrations completed</h1><pre>' . Artisan::output() . '</pre>');
})->middleware('web');
```

2. Visit the URL: `https://ansteches.shop/public/run-migrations?key=YOUR_SECRET_KEY_HERE`
3. Remove this route after you've run the migrations

## After Running Migrations

Once your migrations are complete, you should be able to use the full endpoint with all fields, including `nid`, `address`, and `transport_business_license`.

If you still have issues, you may need to check if there are other database schema problems or if the migrations have actually been run successfully.