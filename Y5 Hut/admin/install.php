<?php
// Simple database installation script
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'y5hut_db';

$message = '';
$error = '';

try {
    // Connect to MySQL (without database)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    // Create tables
    $sql = "
    -- Users table
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        role ENUM('admin', 'manager') DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- Categories table
    CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- Products table
    CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(200) NOT NULL,
        slug VARCHAR(200) NOT NULL UNIQUE,
        category_id INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        original_price DECIMAL(10,2),
        description TEXT,
        specifications JSON,
        image VARCHAR(255),
        gallery JSON,
        stock_quantity INT DEFAULT 0,
        status ENUM('active', 'inactive', 'out_of_stock') DEFAULT 'active',
        featured BOOLEAN DEFAULT FALSE,
        meta_title VARCHAR(200),
        meta_description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
        INDEX idx_category (category_id),
        INDEX idx_status (status),
        INDEX idx_featured (featured)
    );

    -- Messages table
    CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(200),
        message TEXT NOT NULL,
        status ENUM('new', 'read', 'replied') DEFAULT 'new',
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_created (created_at)
    );
    ";
    
    // Execute table creation
    $pdo->exec($sql);
    
    // Insert default admin user
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['admin', $hashedPassword, 'admin@y5hut.lk', 'admin']);
    
    // Insert default categories
    $categories = [
        ['Samsung', 'samsung', 'Samsung mobile phones and devices'],
        ['iPhone', 'iphone', 'Apple iPhone series'],
        ['Redmi', 'redmi', 'Xiaomi Redmi series phones'],
        ['Accessories', 'accessories', 'Mobile phone accessories and gadgets']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name, slug, description) VALUES (?, ?, ?)");
    foreach ($categories as $category) {
        $stmt->execute($category);
    }
    
    $message = 'Database and tables created successfully! Admin user: admin / admin123';
    
} catch (PDOException $e) {
    $error = 'Database error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install - Y5 Hut Admin</title>
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
        
        .install-container {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        
        .install-header {
            margin-bottom: 2rem;
        }
        
        .install-header h1 {
            color: #E53E3E;
            margin-bottom: 0.5rem;
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
        
        .install-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
            text-align: left;
        }
        
        .install-info h3 {
            color: #E53E3E;
            margin-bottom: 1rem;
        }
        
        .credentials {
            background: #e2e8f0;
            padding: 1rem;
            border-radius: 6px;
            font-family: monospace;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-header">
            <h1><i class="fas fa-mobile-alt"></i> Y5 Hut Installation</h1>
            <p>Automatic Database Setup</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
            
            <div class="install-info">
                <h3>Installation Complete!</h3>
                <p>Your Y5 Hut admin panel is ready to use.</p>
                
                <div class="credentials">
                    <strong>Admin Login Credentials:</strong><br>
                    Username: <strong>admin</strong><br>
                    Password: <strong>admin123</strong>
                </div>
                
                <p><strong>Next Steps:</strong></p>
                <ol style="margin-left: 2rem; margin-top: 1rem;">
                    <li>Login to the admin panel</li>
                    <li>Add your mobile phone products</li>
                    <li>Upload product images</li>
                    <li>Customize categories as needed</li>
                </ol>
            </div>
            
            <a href="login.php" class="btn">
                <i class="fas fa-sign-in-alt"></i> Go to Admin Login
            </a>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
            
            <div class="install-info">
                <h3>Installation Failed</h3>
                <p>Please check the following:</p>
                <ul style="margin-left: 2rem; margin-top: 1rem;">
                    <li>XAMPP/WAMP is running</li>
                    <li>MySQL service is started</li>
                    <li>Database credentials are correct</li>
                    <li>PHP has MySQL extension enabled</li>
                </ul>
            </div>
            
            <a href="install.php" class="btn btn-secondary">
                <i class="fas fa-refresh"></i> Try Again
            </a>
        <?php endif; ?>
        
        <a href="../index.html" class="btn btn-secondary">
            <i class="fas fa-home"></i> Back to Website
        </a>
    </div>
</body>
</html>