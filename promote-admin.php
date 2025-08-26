<?php
// Admin User Promotion Script
echo "<h1>Admin User Promotion</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;}</style>";

try {
    // Load Laravel environment
    if (file_exists('.env')) {
        $env_lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env_vars = [];
        foreach ($env_lines as $line) {
            if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
                list($key, $value) = explode('=', $line, 2);
                $env_vars[trim($key)] = trim($value, '"\'');
            }
        }
        
        // Connect to database
        $pdo = new PDO(
            "mysql:host={$env_vars['DB_HOST']};dbname={$env_vars['DB_DATABASE']}", 
            $env_vars['DB_USERNAME'], 
            $env_vars['DB_PASSWORD']
        );
        
        echo "<h2>1. Current Users</h2>";
        $stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id DESC LIMIT 10");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' style='border-collapse:collapse; margin:10px 0;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
        foreach ($users as $user) {
            echo "<tr><td>{$user['id']}</td><td>{$user['name']}</td><td>{$user['email']}</td><td>{$user['role']}</td></tr>";
        }
        echo "</table>";
        
        echo "<h2>2. Promoting Users to Different Roles</h2>";
        
        // Promote first user to admin
        if (!empty($users)) {
            $adminUser = $users[0];
            $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
            $stmt->execute([$adminUser['id']]);
            echo "<span class='success'>✓</span> Promoted {$adminUser['name']} (ID: {$adminUser['id']}) to admin<br>";
            
            // Promote second user to operator if exists
            if (count($users) > 1) {
                $operatorUser = $users[1];
                $stmt = $pdo->prepare("UPDATE users SET role = 'operator' WHERE id = ?");
                $stmt->execute([$operatorUser['id']]);
                echo "<span class='success'>✓</span> Promoted {$operatorUser['name']} (ID: {$operatorUser['id']}) to operator<br>";
            }
        }
        
        echo "<h2>3. Updated User Roles</h2>";
        $stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id DESC LIMIT 10");
        $updated_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' style='border-collapse:collapse; margin:10px 0;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
        foreach ($updated_users as $user) {
            $roleColor = $user['role'] === 'admin' ? 'red' : ($user['role'] === 'operator' ? 'blue' : 'green');
            echo "<tr><td>{$user['id']}</td><td>{$user['name']}</td><td>{$user['email']}</td><td style='color:{$roleColor};font-weight:bold;'>{$user['role']}</td></tr>";
        }
        echo "</table>";
        
        echo "<h2>4. Next Steps</h2>";
        echo "1. Login with the admin user to get admin token<br>";
        echo "2. Test admin endpoints with proper authorization<br>";
        echo "3. Test operator endpoints with operator token<br>";
        echo "4. Test regular user endpoints<br>";
        
    } else {
        echo '<span class="error">✗ .env file not found</span><br>';
    }
    
} catch (Exception $e) {
    echo '<span class="error">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</span><br>';
}

echo "<hr><small>Completed: " . date('Y-m-d H:i:s') . "</small>";
?>