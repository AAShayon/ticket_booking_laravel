<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔍 Database Connection Diagnostic</h1>";
echo "<p>Testing different connection scenarios...</p>";

// Database credentials to test
$credentials = [
    'host' => '127.0.0.1',
    'database' => 'anstecs_ticket_booking',
    'username' => 'anstecs_ticket_booking', 
    'password' => 'AmiShayon'
];

echo "<h2>1. Testing Database Connection Scenarios</h2>";

// Test 1: Try connecting to MySQL server without database
echo "<h3>Test 1: MySQL Server Connection (without database)</h3>";
try {
    $pdo = new PDO("mysql:host={$credentials['host']}", $credentials['username'], $credentials['password']);
    echo "✅ MySQL server connection successful<br>";
    
    // List all databases
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Available databases: " . implode(', ', $databases) . "<br>";
    
    // Check if our database exists
    if (in_array($credentials['database'], $databases)) {
        echo "✅ Database '{$credentials['database']}' exists<br>";
    } else {
        echo "❌ Database '{$credentials['database']}' does not exist<br>";
        echo "<strong>SOLUTION: Create database in cPanel MySQL Databases</strong><br>";
    }
    
} catch (Exception $e) {
    echo "❌ MySQL server connection failed: " . $e->getMessage() . "<br>";
    echo "<strong>Possible issues:</strong><br>";
    echo "- Wrong username/password<br>";
    echo "- MySQL user doesn't exist<br>";
    echo "- User doesn't have proper permissions<br>";
}

// Test 2: Try connecting with database
echo "<h3>Test 2: Database Connection (with database)</h3>";
try {
    $pdo = new PDO("mysql:host={$credentials['host']};dbname={$credentials['database']}", $credentials['username'], $credentials['password']);
    echo "✅ Database connection successful<br>";
    
    // Test query
    $stmt = $pdo->query("SELECT 1");
    echo "✅ Query execution successful<br>";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}

// Test 3: Try common hosting variations
echo "<h3>Test 3: Alternative Connection Methods</h3>";

$alternativeHosts = ['localhost', '127.0.0.1', 'mysql'];
foreach ($alternativeHosts as $host) {
    echo "Testing host: $host... ";
    try {
        $pdo = new PDO("mysql:host=$host;dbname={$credentials['database']}", $credentials['username'], $credentials['password']);
        echo "✅ SUCCESS<br>";
        break;
    } catch (Exception $e) {
        echo "❌ Failed<br>";
    }
}

echo "<h2>2. cPanel Setup Instructions</h2>";
echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0;'>";
echo "<strong>If database doesn't exist, follow these steps:</strong><br>";
echo "1. Login to cPanel<br>";
echo "2. Go to 'MySQL Databases'<br>";
echo "3. Create database: 'anstecs_ticket_booking'<br>";
echo "4. Create user: 'anstecs_ticket_booking' with password: 'AmiShayon'<br>";
echo "5. Add user to database with ALL PRIVILEGES<br>";
echo "</div>";

echo "<h2>3. Manual Database Creation</h2>";
echo "<div style='background: #e8f5e8; padding: 10px; margin: 10px 0;'>";
echo "<strong>If you have phpMyAdmin access, run this SQL:</strong><br>";
echo "<pre>";
echo "CREATE DATABASE IF NOT EXISTS anstecs_ticket_booking;
CREATE USER IF NOT EXISTS 'anstecs_ticket_booking'@'localhost' IDENTIFIED BY 'AmiShayon';
GRANT ALL PRIVILEGES ON anstecs_ticket_booking.* TO 'anstecs_ticket_booking'@'localhost';
FLUSH PRIVILEGES;";
echo "</pre>";
echo "</div>";

echo "<h2>4. Test After Setup</h2>";
echo "After creating database/user, test again:<br>";
echo "<a href='https://ansteches.shop/setup-database.php'>Run Database Setup</a>";
?>