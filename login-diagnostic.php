<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔐 Login Diagnostic Test</h1>";

try {
    // Connect to database
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=ansteche_ticket_booking", "ansteche_ticket_booking", "AmiShayon");
    echo "✅ Database connected<br>";
    
    // Test user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['admin@ansteches.shop']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ User found - ID: " . $user['id'] . ", Role: " . $user['role'] . "<br>";
        echo "Password hash: " . substr($user['password'], 0, 20) . "...<br>";
        
        // Test password verification
        $testPassword = 'password';
        echo "<h3>Password Verification Tests:</h3>";
        
        // Test 1: Direct PHP password_verify
        if (password_verify($testPassword, $user['password'])) {
            echo "✅ password_verify() works correctly<br>";
        } else {
            echo "❌ password_verify() failed<br>";
            
            // Try with different common Laravel hashes
            $commonHashes = [
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // Laravel default
                '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'  // Stronger
            ];
            
            foreach ($commonHashes as $hash) {
                if (password_verify($testPassword, $hash)) {
                    echo "✅ Test hash works: " . substr($hash, 0, 20) . "...<br>";
                    
                    // Update user with working hash
                    $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                    $updateStmt->execute([$hash, 'admin@ansteches.shop']);
                    echo "✅ Password updated in database<br>";
                    break;
                }
            }
        }
        
        // Test 2: Create a fresh hash
        echo "<h3>Creating Fresh Password Hash:</h3>";
        $freshHash = password_hash($testPassword, PASSWORD_DEFAULT);
        echo "Fresh hash: " . substr($freshHash, 0, 30) . "...<br>";
        
        if (password_verify($testPassword, $freshHash)) {
            echo "✅ Fresh hash works<br>";
            
            // Update with fresh hash
            $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $updateStmt->execute([$freshHash, 'admin@ansteches.shop']);
            echo "✅ Database updated with fresh hash<br>";
        }
        
    } else {
        echo "❌ User not found<br>";
    }
    
    echo "<h3>🧪 Test Login API Now:</h3>";
    echo "<p>After running this script, test login with:</p>";
    echo "<code>curl -X POST https://ansteches.shop/api/login -H 'Content-Type: application/json' -H 'Accept: application/json' -d '{\"email\":\"admin@ansteches.shop\",\"password\":\"password\"}'</code>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>