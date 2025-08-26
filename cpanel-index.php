<?php
// Simple Laravel entry point for cPanel hosting
// This file should be in the root directory (public_html)

// Check if Laravel is properly installed
if (!file_exists('./laravel-app/public/index.php')) {
    die('Laravel application not found. Please extract files to ./laravel-app/ directory');
}

// Redirect all requests to Laravel public directory
$request_uri = $_SERVER['REQUEST_URI'];

// Remove query string for clean routing
$path = parse_url($request_uri, PHP_URL_PATH);

// Handle root requests
if ($path === '/' || $path === '') {
    $path = '/index.php';
}

// Set up Laravel environment
$_SERVER['SCRIPT_NAME'] = '/laravel-app/public/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/laravel-app/public/index.php';

// Include Laravel application
require_once './laravel-app/public/index.php';
?>