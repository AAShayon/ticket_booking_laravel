-- Fix Laravel Password Hashing - Run in phpMyAdmin
-- Database: ansteche_ticket_booking

-- Delete existing users and recreate with proper Laravel password hashing
DELETE FROM users WHERE email IN ('admin@ansteches.shop', 'operator@ansteches.shop', 'user@ansteches.shop');

-- Insert Superadmin User with proper Laravel bcrypt hash for "password"
INSERT INTO `users` (
    `name`, `email`, `phone_number`, `email_verified_at`, `password`, 
    `role`, `last_login_at`, `created_at`, `updated_at`
) VALUES (
    'Super Admin',
    'admin@ansteches.shop',
    '+8801580873412',
    '2025-08-25 21:47:37',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    '2025-08-25 21:47:37',
    '2025-08-25 21:47:37',
    '2025-08-25 21:47:37'
);

-- Insert Test Operator User
INSERT INTO `users` (
    `name`, `email`, `phone_number`, `email_verified_at`, `password`, 
    `role`, `last_login_at`, `created_at`, `updated_at`
) VALUES (
    'Test Operator',
    'operator@ansteches.shop',
    '+8801987654321',
    '2025-08-25 21:47:37',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'operator',
    '2025-08-25 21:47:37',
    '2025-08-25 21:47:37',
    '2025-08-25 21:47:37'
);

-- Insert Regular Test User
INSERT INTO `users` (
    `name`, `email`, `phone_number`, `email_verified_at`, `password`, 
    `role`, `last_login_at`, `created_at`, `updated_at`
) VALUES (
    'Test User',
    'user@ansteches.shop',
    '+8801555666777',
    '2025-08-25 21:47:37',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'user',
    '2025-08-25 21:47:37',
    '2025-08-25 21:47:37',
    '2025-08-25 21:47:37'
);

-- Verify the users were created correctly
SELECT id, name, email, role, created_at FROM users WHERE email LIKE '%ansteches.shop' ORDER BY id;