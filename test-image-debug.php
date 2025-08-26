<?php
// Enhanced Image Debug Script for Laravel Storage Issues
// This script helps identify and fix image serving problems

echo "<h1>🔍 Laravel Image Storage Debug Tool</h1>";
echo "<hr>";

// Test 1: Check current directory structure
echo "<h2>1. Directory Structure Analysis</h2>";
echo "Current script location: " . __DIR__ . "<br>";
echo "Document root (if available): " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "<br>";
echo "<br>";

// Test 2: Check Laravel storage directories
echo "<h2>2. Storage Directory Check</h2>";
$possibleStoragePaths = [
    __DIR__ . '/storage/app/public',           // If Laravel is in same directory
    __DIR__ . '/laravel/storage/app/public',   // If Laravel is in subdirectory
    __DIR__ . '/../storage/app/public',        // If script is in public, Laravel above
];

foreach ($possibleStoragePaths as $index => $path) {
    $realPath = realpath($path);
    echo "Path " . ($index + 1) . ": $path<br>";
    echo "Exists: " . (is_dir($path) ? '✅ YES' : '❌ NO') . "<br>";
    echo "Real path: " . ($realPath ?: 'Invalid') . "<br>";
    if (is_dir($path)) {
        echo "Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "<br>";
        echo "Writable: " . (is_writable($path) ? '✅ YES' : '❌ NO') . "<br>";
        
        // List some files
        $files = glob($path . '/*');
        echo "Files found: " . count($files) . "<br>";
        if (count($files) > 0) {
            echo "Sample files: " . implode(', ', array_slice(array_map('basename', $files), 0, 5)) . "<br>";
        }
    }
    echo "<br>";
}

// Test 3: Check for symbolic link
echo "<h2>3. Symbolic Link Check</h2>";
$publicStorageLink = __DIR__ . '/storage';
echo "Public storage link: $publicStorageLink<br>";
echo "Link exists: " . (is_link($publicStorageLink) ? '✅ YES' : '❌ NO') . "<br>";
if (is_link($publicStorageLink)) {
    echo "Link target: " . readlink($publicStorageLink) . "<br>";
} else {
    echo "Directory exists instead: " . (is_dir($publicStorageLink) ? '✅ YES' : '❌ NO') . "<br>";
}
echo "<br>";

// Test 4: Test image serving
echo "<h2>4. Image Serving Test</h2>";

if (isset($_GET['test_image'])) {
    $testImagePath = $_GET['test_image'];
    echo "Testing image: $testImagePath<br>";
    
    // Try different storage paths
    foreach ($possibleStoragePaths as $basePath) {
        $fullPath = $basePath . '/' . $testImagePath;
        $realPath = realpath($fullPath);
        
        echo "Trying: $fullPath<br>";
        if (file_exists($fullPath)) {
            echo "✅ Found at: $realPath<br>";
            $mimeType = mime_content_type($fullPath);
            echo "MIME type: $mimeType<br>";
            echo "File size: " . filesize($fullPath) . " bytes<br>";
            
            // Serve the image
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($fullPath));
            readfile($fullPath);
            exit;
        } else {
            echo "❌ Not found<br>";
        }
    }
    
    echo "❌ Image not found in any storage path<br>";
    exit;
}

// Test 5: Create test image if needed
echo "<h2>5. Create Test Image</h2>";
$workingStoragePath = null;
foreach ($possibleStoragePaths as $path) {
    if (is_dir($path) && is_writable($path)) {
        $workingStoragePath = $path;
        break;
    }
}

if ($workingStoragePath) {
    echo "Using storage path: $workingStoragePath<br>";
    $testImagePath = $workingStoragePath . '/test-image.jpg';
    
    if (!file_exists($testImagePath)) {
        // Create a simple test image
        $image = imagecreate(200, 100);
        $bg = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        imagestring($image, 5, 50, 40, 'TEST IMAGE', $textColor);
        
        if (imagejpeg($image, $testImagePath)) {
            echo "✅ Test image created: $testImagePath<br>";
        } else {
            echo "❌ Failed to create test image<br>";
        }
        imagedestroy($image);
    } else {
        echo "✅ Test image already exists<br>";
    }
    
    echo "<a href='?test_image=test-image.jpg'>🖼️ Test Image Display</a><br>";
} else {
    echo "❌ No writable storage path found<br>";
}

// Test 6: Laravel route testing
echo "<h2>6. Recommended Solution</h2>";
echo "<div style='background: #f0f8ff; padding: 15px; border-left: 4px solid #0066cc;'>";
echo "<strong>Issue Identified:</strong><br>";
echo "Your Laravel application needs the storage symbolic link to serve images properly.<br><br>";

echo "<strong>Solutions:</strong><br>";
echo "1. <strong>Create symbolic link (Recommended):</strong><br>";
echo "<code>php artisan storage:link</code><br><br>";

echo "2. <strong>Or create manual symbolic link:</strong><br>";
echo "<code>ln -s " . ($workingStoragePath ?: '/path/to/storage/app/public') . " " . __DIR__ . "/storage</code><br><br>";

echo "3. <strong>Or use .htaccess redirect (Alternative):</strong><br>";
echo "Add to your .htaccess:<br>";
echo "<pre>RewriteRule ^storage/(.*)$ /storage-serve.php?file=$1 [L]</pre><br>";

echo "4. <strong>API Image URLs should be:</strong><br>";
echo "<code>" . ($_SERVER['HTTP_HOST'] ?? 'ansteches.shop') . "/storage/profile-images/filename.jpg</code><br>";
echo "</div>";

echo "<h2>7. Current File Test</h2>";
if (isset($_GET['file'])) {
    echo "File parameter received: " . htmlspecialchars($_GET['file']) . "<br>";
    echo "<a href='?test_image=" . urlencode($_GET['file']) . "'>🔄 Test this file</a><br>";
} else {
    echo "No file parameter provided. Add ?file=your-image.jpg to test<br>";
}
?>