-- Update User Roles for Testing
-- Run this in phpMyAdmin SQL tab

-- Promote the test admin user to admin role
UPDATE users SET role = 'admin' WHERE email = 'admin@ansteches.shop';

-- Promote the operator user to operator role
UPDATE users SET role = 'operator' WHERE email = 'operator1@ansteches.shop';

-- Verify the updates
SELECT id, name, email, role FROM users ORDER BY id DESC;