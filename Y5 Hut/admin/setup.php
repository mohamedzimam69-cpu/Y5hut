<?php
// Database setup and admin user creation
require_once '../backend/db.php';

$message = '';
$error = '';

// Check if database connection exists
if ($db === null) {
    $error = 'Database connection failed. Please check your database configuration.';
} else {
    try {
        // Check if users table exists
        $db->query('SHOW TABLES LIKE "users"');
        $tableExists = $db->single();
        
        if (!$tableExists) {
            $error = 'Database tables not found. Please import the y5hut.sql file first.';
        } else {
            // Check if admin user exists
            $db->query('SELECT * FROM users WHERE username = "admin"');
            $adminExists = $db->single();
            
            if (!$adminExists) {
                // Create admin user
                $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
                
                $db->query('INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)');
                $db->bind(':username', 'admin');
                $db->bind(':password', $hashedPassword);
                $db->bind(':email', 'admin@y5hut.lk');
                $db->bind(':role', 'admin');
                
                if ($db->execute()) {
                    $message = 'Admin user created successfully! Username: admin, Password: admin123';
                } else {
                    $error = 'Failed to create admin user';
                }
            } else {
                // Update existing admin password
                $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
                
                $db->query('UPDATE users SET password = :password WHERE username = "admin"');
                $db->bind(':password', $hashedPassword);
                
                if ($db->execute()) {
                    $message = 'Admin password reset successfully! Username: admin, Password: admin123';
                } else {
                    $error = 'Failed to reset admin password';
                }
            }
        }
    } catch (Exception $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Y5 Hut Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #E53E3E 0%, #B91C1C 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .setup-container {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        
        .setup-header {
            margin-bottom: 2rem;
        }
        
        .setup-header h1 {
            color: #E53E3E;
            margin-bottom: 0.5rem;
        }
        
        .setup-header p {
            color: #666;
        }
        
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #E53E3E;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 0.5rem;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #B91C1C;
        }
        
        .btn-secondary {
            background: #718096;
        }
        
        .btn-secondary:hover {
            background: #4A5568;
        }
        
        .setup-steps {
            text-align: left;
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
        }
        
        .setup-steps h3 {
            color: #E53E3E;
            margin-bottom: 1rem;
        }
        
        .setup-steps ol {
            margin-left: 1.5rem;
        }
        
        .setup-steps li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <h1><i class="fas fa-mobile-alt"></i> Y5 Hut Setup</h1>
            <p>Database and Admin Setup</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="setup-steps">
                <h3>Setup Instructions:</h3>
                <ol>
                    <li>Make sure XAMPP/WAMP is running</li>
                    <li>Open phpMyAdmin: <code>http://localhost/phpmyadmin</code></li>
                    <li>Create database named: <strong>y5hut_db</strong></li>
                    <li>Import the file: <strong>database/y5hut.sql</strong></li>
                    <li>Refresh this page</li>
                </ol>
            </div>
        <?php endif; ?>
        
        <div class="setup-actions">
            <?php if ($message): ?>
                <a href="login.php" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Go to Login
                </a>
            <?php endif; ?>
            
            <a href="setup.php" class="btn btn-secondary">
                <i class="fas fa-refresh"></i> Refresh
            </a>
            
            <a href="../index.html" class="btn btn-secondary">
                <i class="fas fa-home"></i> Back to Website
            </a>
        </div>
    </div>
</body>
</html>