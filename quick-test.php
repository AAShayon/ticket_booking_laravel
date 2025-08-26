<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Quick Server Test</h1>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";

// Test database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=anstecs_ticket_booking", "anstecs_ticket_booking", "AmiShayon");
    echo "✅ Database Connected<br>";
    
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . count($tables) . "<br>";
    
    if (in_array('users', $tables)) {
        echo "✅ Users table exists<br>";
        
        // Check admin user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute(['admin@ansteches.shop']);
        $admin = $stmt->fetch();
        
        if ($admin) {
            echo "✅ Admin user found (ID: " . $admin['id'] . ")<br>";
        } else {
            echo "❌ Admin user not found<br>";
        }
    } else {
        echo "❌ Users table not found<br>";
        echo "Available tables: " . implode(', ', $tables) . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
}
?>