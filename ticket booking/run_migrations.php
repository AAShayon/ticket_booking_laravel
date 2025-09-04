<?php
// Migration Runner Script - DELETE AFTER USE FOR SECURITY
// This script runs migrations for the Laravel application

// Set the exact secret key as requested
$secret_key = 'bnkjshd7263bhjsd7268hjsd';

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
    
    // Add manual SQL alteration options if migrations don't work
    echo "<h2>Manual SQL Alterations (Only if migrations fail)</h2>";
    echo "<p>You can run these SQL commands in phpMyAdmin if the migrations don't work:</p>";
    echo "<pre>";
    echo "ALTER TABLE operators ADD COLUMN nid VARCHAR(255) NULL AFTER contact_phone;\n";
    echo "ALTER TABLE operators ADD COLUMN address VARCHAR(255) NULL AFTER nid;\n";
    echo "ALTER TABLE operators ADD COLUMN transport_business_license VARCHAR(255) NULL AFTER address;\n";
    echo "ALTER TABLE operators ADD COLUMN logo VARCHAR(255) NULL AFTER transport_business_license;\n";
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