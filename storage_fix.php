<?php
// Storage Fix Script - DELETE AFTER USE FOR SECURITY
// This script fixes storage symlink issues in Laravel on cPanel

// Set a secure random key to prevent unauthorized access
$secret_key = 'storage_fix_key_12345';

// Check if the provided key matches
if (isset($_GET['key']) && $_GET['key'] === $secret_key) {
    // Define the application base path
    $basePath = __DIR__;
    
    echo "<h1>Laravel Storage Diagnostic and Fix Tool</h1>";
    
    // 1. Check if storage directory exists
    echo "<h2>Checking Storage Directory Structure:</h2>";
    
    $publicPath = $basePath . '/public';
    $storagePath = $basePath . '/storage';
    $publicStoragePath = $publicPath . '/storage';
    $storageAppPublicPath = $storagePath . '/app/public';
    $logosPath = $storageAppPublicPath . '/logos';
    
    echo "<pre>";
    echo "Base path: " . $basePath . "\n";
    echo "Public path: " . $publicPath . " - " . (is_dir($publicPath) ? "EXISTS" : "MISSING") . "\n";
    echo "Storage path: " . $storagePath . " - " . (is_dir($storagePath) ? "EXISTS" : "MISSING") . "\n";
    echo "Public storage path: " . $publicStoragePath . " - " . (is_dir($publicStoragePath) ? "EXISTS" : "MISSING") . "\n";
    echo "Storage app public path: " . $storageAppPublicPath . " - " . (is_dir($storageAppPublicPath) ? "EXISTS" : "MISSING") . "\n";
    echo "Logos path: " . $logosPath . " - " . (is_dir($logosPath) ? "EXISTS" : "MISSING") . "\n";
    echo "</pre>";
    
    // 2. Check if the specific logo file exists
    $logoFile = 'E7B4pvYyrOMXAzMZjUct090NzVEplF4jw1Ms2Neh.jpg';
    $logoPath = $logosPath . '/' . $logoFile;
    
    echo "<h2>Checking Logo File:</h2>";
    echo "<pre>";
    echo "Logo path: " . $logoPath . " - " . (file_exists($logoPath) ? "EXISTS" : "MISSING") . "\n";
    if (file_exists($logoPath)) {
        echo "File size: " . filesize($logoPath) . " bytes\n";
        echo "File permissions: " . substr(sprintf('%o', fileperms($logoPath)), -4) . "\n";
    }
    echo "</pre>";
    
    // 3. Fix symlink if needed
    echo "<h2>Symlink Status and Fix:</h2>";
    echo "<pre>";
    
    // Check if symlink exists and points to the correct location
    if (is_link($publicStoragePath)) {
        $target = readlink($publicStoragePath);
        echo "Symlink exists and points to: " . $target . "\n";
        
        if ($target !== '../storage/app/public') {
            echo "Symlink target is incorrect. Removing old symlink...\n";
            unlink($publicStoragePath);
            echo "Creating new symlink...\n";
            symlink('../storage/app/public', $publicStoragePath);
            echo "Symlink recreated.\n";
        } else {
            echo "Symlink target is correct.\n";
        }
    } else {
        if (is_dir($publicStoragePath)) {
            echo "Public storage path exists as a directory, not a symlink. Removing...\n";
            // This is dangerous, so we'll just rename it
            rename($publicStoragePath, $publicStoragePath . '_backup_' . time());
            echo "Directory moved to backup. Creating symlink...\n";
        } else {
            echo "Symlink doesn't exist. Creating...\n";
        }
        
        // Create the symlink
        symlink('../storage/app/public', $publicStoragePath);
        echo "Symlink created.\n";
    }
    
    // 4. Create directories if needed
    if (!is_dir($storageAppPublicPath)) {
        echo "Creating storage/app/public directory...\n";
        mkdir($storageAppPublicPath, 0755, true);
        echo "Directory created.\n";
    }
    
    if (!is_dir($logosPath)) {
        echo "Creating logos directory...\n";
        mkdir($logosPath, 0755, true);
        echo "Directory created.\n";
    }
    
    // 5. Check permissions
    echo "</pre>";
    echo "<h2>Directory Permissions:</h2>";
    echo "<pre>";
    echo "Storage permissions: " . substr(sprintf('%o', fileperms($storagePath)), -4) . "\n";
    echo "Storage/app permissions: " . substr(sprintf('%o', fileperms($storagePath . '/app')), -4) . "\n";
    echo "Storage/app/public permissions: " . substr(sprintf('%o', fileperms($storageAppPublicPath)), -4) . "\n";
    
    // 6. Fix permissions if needed
    echo "</pre>";
    echo "<h2>Setting Correct Permissions:</h2>";
    echo "<pre>";
    echo "Setting storage directory permissions...\n";
    chmod($storagePath, 0755);
    chmod($storagePath . '/app', 0755);
    chmod($storageAppPublicPath, 0755);
    if (is_dir($logosPath)) {
        chmod($logosPath, 0755);
    }
    echo "Permissions set.\n";
    
    // 7. Create .htaccess file in storage/app/public
    echo "</pre>";
    echo "<h2>Creating .htaccess File:</h2>";
    echo "<pre>";
    
    $htaccessContent = "
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    
    # Allow access to all files
    <FilesMatch \".*\">
        Order Allow,Deny
        Allow from all
    </FilesMatch>
</IfModule>

# Enable MIME types
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
</IfModule>

# Set default handling of files
<IfModule mod_headers.c>
    <FilesMatch \"\\.(jpg|jpeg|png|gif)$\">
        Header set Access-Control-Allow-Origin \"*\"
    </FilesMatch>
</IfModule>
";
    
    file_put_contents($storageAppPublicPath . '/.htaccess', $htaccessContent);
    echo "Created .htaccess file in storage/app/public.\n";
    
    // 8. Check artisan storage:link command
    echo "</pre>";
    echo "<h2>Running Artisan Command:</h2>";
    echo "<pre>";
    
    // Include the autoloader
    require $basePath . '/vendor/autoload.php';
    
    // Load the Laravel application
    $app = require_once $basePath . '/bootstrap/app.php';
    
    // Get the Artisan kernel
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    // Run the storage:link command
    echo "Running storage:link command...\n";
    $exitCode = $kernel->call('storage:link');
    echo "Command exit code: " . $exitCode . "\n";
    
    // 9. Final check
    echo "</pre>";
    echo "<h2>Final Status:</h2>";
    echo "<pre>";
    echo "Symlink exists: " . (is_link($publicStoragePath) ? "YES" : "NO") . "\n";
    if (is_link($publicStoragePath)) {
        echo "Symlink target: " . readlink($publicStoragePath) . "\n";
    }
    echo "Storage/app/public exists: " . (is_dir($storageAppPublicPath) ? "YES" : "NO") . "\n";
    echo "Logos directory exists: " . (is_dir($logosPath) ? "YES" : "NO") . "\n";
    
    // 10. Test URLs
    echo "</pre>";
    echo "<h2>Test URLs:</h2>";
    echo "<p>Try accessing these URLs to test if storage is working:</p>";
    echo "<ul>";
    echo "<li><a href='/public/storage/logos/{$logoFile}' target='_blank'>Public storage URL: /public/storage/logos/{$logoFile}</a></li>";
    echo "<li><a href='/storage/logos/{$logoFile}' target='_blank'>Storage URL: /storage/logos/{$logoFile}</a></li>";
    echo "</ul>";
    
    echo "<h2>Important Security Note:</h2>";
    echo "<p style='color: red; font-weight: bold;'>DELETE THIS FILE IMMEDIATELY AFTER USE FOR SECURITY!</p>";
    
} else {
    // If key doesn't match, show access denied
    header('HTTP/1.0 403 Forbidden');
    echo "<h1>Access Denied</h1>";
    echo "<p>Invalid or missing security key.</p>";
}
?>