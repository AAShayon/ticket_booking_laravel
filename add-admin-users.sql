-- Add Admin Users to Existing Database
-- Run this in phpMyAdmin SQL tab for database: ansteche_ticket_booking

-- Clear existing test users (if any)
DELETE FROM users WHERE email IN ('admin@ansteches.shop', 'operator@ansteches.shop', 'user@ansteches.shop');

-- Insert Superadmin User
INSERT INTO `users` (
    `name`, `email`, `phone_number`, `email_verified_at`, `password`, 
    `role`, `last_login_at`, `created_at`, `updated_at`
) VALUES (
    'Super Admin',
    'admin@ansteches.shop',
    '+8801712345678',
    NOW(),
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    NOW(),
    NOW(),
    NOW()
);

-- Insert Test Operator User
INSERT INTO `users` (
    `name`, `email`, `phone_number`, `email_verified_at`, `password`, 
    `role`, `last_login_at`, `created_at`, `updated_at`
) VALUES (
    'Test Operator',
    'operator@ansteches.shop',
    '+8801987654321',
    NOW(),
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'operator',
    NOW(),
    NOW(),
    NOW()
);

-- Insert Regular Test User
INSERT INTO `users` (
    `name`, `email`, `phone_number`, `email_verified_at`, `password`, 
    `role`, `last_login_at`, `created_at`, `updated_at`
) VALUES (
    'Test User',
    'user@ansteches.shop',
    '+8801555666777',
    NOW(),
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'user',
    NOW(),
    NOW(),
    NOW()
);

-- Verify the users were created
SELECT id, name, email, role, created_at FROM users WHERE email LIKE '%ansteches.shop' ORDER BY id DESC;