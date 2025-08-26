<?php
// Simple test file to check server functionality
echo "<h1>Server Test</h1>";
echo "<p>If you can see this, PHP is working!</p>";
echo "<p>Server Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Test file access
echo "<h2>File System Test</h2>";
echo "<p>Current directory: " . getcwd() . "</p>";
echo "<p>Files in current directory:</p><ul>";
foreach (scandir('.') as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "<li>$file</li>";
    }
}
echo "</ul>";

phpinfo();
?>