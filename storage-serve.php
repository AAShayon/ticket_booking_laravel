<?php
// Laravel Storage File Serving Script
// This script serves files from Laravel's storage/app/public directory
// when the symbolic link is not available or not working

// Security: Only allow image files
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
$allowedMimeTypes = [
    'image/jpeg', 'image/png', 'image/gif', 
    'image/webp', 'image/svg+xml'
];

if (!isset($_GET['file'])) {
    http_response_code(400);
    echo "Error: No file specified";
    exit;
}

$requestedFile = $_GET['file'];

// Sanitize the file path to prevent directory traversal
$requestedFile = str_replace(['..', '\\', '//', '\\\\'], '', $requestedFile);
$requestedFile = ltrim($requestedFile, '/');

// Check file extension
$extension = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));
if (!in_array($extension, $allowedExtensions)) {
    http_response_code(403);
    echo "Error: File type not allowed";
    exit;
}

// Define possible storage paths
$storagePaths = [
    __DIR__ . '/storage/app/public/' . $requestedFile,     // Laravel in same dir
    __DIR__ . '/laravel/storage/app/public/' . $requestedFile, // Laravel in subdir
    __DIR__ . '/../storage/app/public/' . $requestedFile,  // Script in public dir
];

$filePath = null;
foreach ($storagePaths as $path) {
    if (file_exists($path)) {
        $filePath = $path;
        break;
    }
}

if (!$filePath) {
    http_response_code(404);
    echo "Error: File not found - " . htmlspecialchars($requestedFile);
    exit;
}

// Additional security check
$realStorageBase = realpath(dirname($filePath));
$realFilePath = realpath($filePath);

if (!$realFilePath || strpos($realFilePath, $realStorageBase) !== 0) {
    http_response_code(403);
    echo "Error: Access denied";
    exit;
}

// Get and verify MIME type
$mimeType = mime_content_type($filePath);
if (!in_array($mimeType, $allowedMimeTypes)) {
    http_response_code(403);
    echo "Error: Invalid file type";
    exit;
}

// Set appropriate headers
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: public, max-age=31536000'); // 1 year cache
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Serve the file
readfile($filePath);
exit;
?>