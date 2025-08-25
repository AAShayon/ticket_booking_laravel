-- Laravel Ticket Booking System - Create Superadmin and Test Users
-- Execute these queries in phpMyAdmin SQL tab

-- 1. Create Superadmin User
INSERT INTO `users` (
    `name`, 
    `email`, 
    `phone_number`, 
    `email_verified_at`, 
    `password`, 
    `profile_image`, 
    `role`, 
    `last_login_at`, 
    `remember_token`, 
    `created_at`, 
    `updated_at`
) VALUES (
    'Super Admin',
    'admin@ansteches.shop',
    '+8801712345678',
    NOW(),
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Password: password
    NULL,
    'admin',
    NOW(),
    NULL,
    NOW(),
    NOW()
);

-- 2. Create Test Operator User
INSERT INTO `users` (
    `name`, 
    `email`, 
    `phone_number`, 
    `email_verified_at`, 
    `password`, 
    `profile_image`, 
    `role`, 
    `last_login_at`, 
    `remember_token`, 
    `created_at`, 
    `updated_at`
) VALUES (
    'Test Operator',
    'operator@ansteches.shop',
    '+8801987654321',
    NOW(),
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Password: password
    NULL,
    'operator',
    NOW(),
    NULL,
    NOW(),
    NOW()
);

-- 3. Create Regular Test User
INSERT INTO `users` (
    `name`, 
    `email`, 
    `phone_number`, 
    `email_verified_at`, 
    `password`, 
    `profile_image`, 
    `role`, 
    `last_login_at`, 
    `remember_token`, 
    `created_at`, 
    `updated_at`
) VALUES (
    'Test User',
    'user@ansteches.shop',
    '+8801555666777',
    NOW(),
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Password: password
    NULL,
    'user',
    NOW(),
    NULL,
    NOW(),
    NOW()
);

-- 4. Verify the users were created
SELECT id, name, email, role, created_at FROM users ORDER BY id DESC;