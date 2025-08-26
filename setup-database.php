<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔧 Database Setup Script</h1>";

try {
    // Connect to database
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=anstecs_ticket_booking", "anstecs_ticket_booking", "AmiShayon");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connected successfully<br>";
    
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Found " . count($tables) . " tables<br>";
    
    if (in_array('users', $tables)) {
        echo "✅ Users table exists<br>";
        
        // Check if admin user exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute(['admin@ansteches.shop']);
        $adminExists = $stmt->fetchColumn() > 0;
        
        if (!$adminExists) {
            echo "👤 Creating admin user...<br>";
            
            // Hash password using PHP's password_hash (Laravel compatible)
            $hashedPassword = '$2y$12$' . base64_encode(hash('sha256', 'password' . 'laravel_salt', true));
            
            // Insert admin user
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, email_verified_at, password, role, created_at, updated_at) 
                VALUES (?, ?, NOW(), ?, ?, NOW(), NOW())
            ");
            
            $stmt->execute([
                'Super Admin',
                'admin@ansteches.shop', 
                date('Y-m-d H:i:s'),
                password_hash('password', PASSWORD_DEFAULT),
                'admin'
            ]);
            
            echo "✅ Admin user created successfully<br>";
        } else {
            echo "✅ Admin user already exists<br>";
        }
        
        // Test login credentials
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute(['admin@ansteches.shop']);
        $admin = $stmt->fetch();
        
        if ($admin) {
            echo "✅ Admin found - ID: " . $admin['id'] . ", Role: " . $admin['role'] . "<br>";
            
            // Test password
            if (password_verify('password', $admin['password'])) {
                echo "✅ Password verification successful<br>";
            } else {
                echo "❌ Password verification failed<br>";
            }
        }
        
    } else {
        echo "❌ Users table not found. Available tables: " . implode(', ', $tables) . "<br>";
        echo "<br><strong>Need to create database tables first!</strong><br>";
    }
    
    echo "<br><h2>🧪 Test API Now:</h2>";
    echo "<code>curl -X POST https://ansteches.shop/api/login -H 'Content-Type: application/json' -d '{\"email\":\"admin@ansteches.shop\",\"password\":\"password\"}'</code><br>";
    
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
}
?>