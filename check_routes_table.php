<?php
// Simple database connection script
// You'll need to update these values with your actual database credentials
$host = 'localhost'; // Usually localhost on cPanel
$dbname = 'ansteche_ticket_booking'; // Your database name
$username = 'ansteche_ticket_booking'; // Your database username
$password = 'ansteche_secure_password_2025'; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the columns in the routes table
    $stmt = $pdo->query("DESCRIBE routes");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Routes table structure:\n";
    echo "========================\n";
    foreach ($columns as $column) {
        echo $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}