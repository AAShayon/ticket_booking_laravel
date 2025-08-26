<?php
// Create Test Image for Storage Testing
echo "<h1>Test Image Creator</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;}</style>";

// Create storage directory structure if it doesn't exist
$storage_dirs = [
    'storage',
    'storage/app', 
    'storage/app/public',
    'storage/app/public/test'
];

echo "<h2>1. Creating Storage Directory Structure</h2>";
foreach ($storage_dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "<span class='success'>✓</span> Created directory: {$dir}<br>";
    } else {
        echo "<span class='success'>✓</span> Directory exists: {$dir}<br>";
    }
}

echo "<h2>2. Creating Test Images</h2>";

// Create a simple test image using GD
if (extension_loaded('gd')) {
    // Create a 200x100 test image
    $image = imagecreate(200, 100);
    $bg_color = imagecolorallocate($image, 135, 206, 235); // Sky blue
    $text_color = imagecolorallocate($image, 255, 255, 255); // White
    
    // Add text
    imagestring($image, 5, 30, 30, "TEST IMAGE", $text_color);
    imagestring($image, 3, 50, 60, "Storage Test", $text_color);
    
    // Save test images
    $test_images = [
        'storage/app/public/test-image.jpg' => 'JPEG',
        'storage/app/public/test/profile.jpg' => 'JPEG', 
        'storage/app/public/test/operator-logo.png' => 'PNG'
    ];
    
    foreach ($test_images as $file => $format) {
        if ($format === 'JPEG') {
            $success = imagejpeg($image, $file, 90);
        } else {
            $success = imagepng($image, $file);
        }
        
        if ($success) {
            echo "<span class='success'>✓</span> Created {$file}<br>";
        } else {
            echo "<span class='error'>✗</span> Failed to create {$file}<br>";
        }
    }
    
    imagedestroy($image);
} else {
    echo "<span class='error'>✗</span> GD extension not available. Creating placeholder files...<br>";
    
    // Create placeholder files if GD is not available
    $placeholder_content = "PLACEHOLDER IMAGE DATA";
    $test_files = [
        'storage/app/public/test-image.jpg',
        'storage/app/public/test/profile.jpg',
        'storage/app/public/test/operator-logo.png'
    ];
    
    foreach ($test_files as $file) {
        file_put_contents($file, $placeholder_content);
        echo "<span class='success'>✓</span> Created placeholder: {$file}<br>";
    }
}

echo "<h2>3. Testing Storage Access</h2>";
echo "Test the following URLs after uploading the storage-serve.php script:<br><br>";

$base_url = "https://ansteches.shop";
$test_urls = [
    '/storage/test-image.jpg',
    '/storage/test/profile.jpg', 
    '/storage/test/operator-logo.png'
];

foreach ($test_urls as $url) {
    echo "<a href='{$base_url}{$url}' target='_blank'>{$base_url}{$url}</a><br>";
}

echo "<h2>4. Next Steps</h2>";
echo "1. Upload storage-serve.php to your server root<br>";
echo "2. Replace your .htaccess with .htaccess-STORAGE-FIX<br>";
echo "3. Test the URLs above<br>";
echo "4. Check your API responses for image URLs<br>";

echo "<hr><small>Generated: " . date('Y-m-d H:i:s') . "</small>";
?>