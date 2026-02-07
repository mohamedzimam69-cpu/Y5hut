<?php
session_start();
require_once '../backend/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Get statistics
try {
    // Total products
    $db->query('SELECT COUNT(*) as total FROM products WHERE status = "active"');
    $totalProducts = $db->single()['total'];
    
    // Total categories
    $db->query('SELECT COUNT(*) as total FROM categories WHERE status = "active"');
    $totalCategories = $db->single()['total'];
    
    // Total messages
    $db->query('SELECT COUNT(*) as total FROM messages WHERE status = "new"');
    $newMessages = $db->single()['total'];
    
    // Featured products
    $db->query('SELECT COUNT(*) as total FROM products WHERE featured = 1 AND status = "active"');
    $featuredProducts = $db->single()['total'];
    
    // Recent products
    $db->query('SELECT * FROM products ORDER BY created_at DESC LIMIT 5');
    $recentProducts = $db->resultset();
    
    // Recent messages
    $db->query('SELECT * FROM messages ORDER BY created_at DESC LIMIT 5');
    $recentMessages = $db->resultset();
    
} catch (Exception $e) {
    $error = 'Error loading dashboard data';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Y5 Hut Mobile Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-mobile-alt"></i> Y5 Hut Admin</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                    <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages <span class="badge"><?php echo $newMessages; ?></span></a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="../index.html" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon products">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalProducts; ?></h3>
                        <p>Total Products</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon categories">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalCategories; ?></h3>
                        <p>Categories</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon messages">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $newMessages; ?></h3>
                        <p>New Messages</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon featured">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $featuredProducts; ?></h3>
                        <p>Featured Products</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="dashboard-grid">
                <!-- Recent Products -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-box"></i> Recent Products</h3>
                        <a href="products.php" class="view-all">View All</a>
                    </div>
                    <div class="card-content">
                        <?php if (!empty($recentProducts)): ?>
                            <div class="recent-list">
                                <?php foreach ($recentProducts as $product): ?>
                                    <div class="recent-item">
                                        <div class="item-info">
                                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                            <p>Rs. <?php echo number_format($product['price'], 2); ?></p>
                                        </div>
                                        <span class="item-status <?php echo $product['status']; ?>">
                                            <?php echo ucfirst($product['status']); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data">No products found</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-envelope"></i> Recent Messages</h3>
                        <a href="messages.php" class="view-all">View All</a>
                    </div>
                    <div class="card-content">
                        <?php if (!empty($recentMessages)): ?>
                            <div class="recent-list">
                                <?php foreach ($recentMessages as $message): ?>
                                    <div class="recent-item">
                                        <div class="item-info">
                                            <h4><?php echo htmlspecialchars($message['name']); ?></h4>
                                            <p><?php echo htmlspecialchars(substr($message['message'], 0, 50)) . '...'; ?></p>
                                        </div>
                                        <span class="item-status <?php echo $message['status']; ?>">
                                            <?php echo ucfirst($message['status']); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data">No messages found</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <a href="add-product.php" class="action-btn primary">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                    <a href="categories.php" class="action-btn secondary">
                        <i class="fas fa-tags"></i> Manage Categories
                    </a>
                    <a href="messages.php" class="action-btn info">
                        <i class="fas fa-envelope"></i> View Messages
                    </a>
                    <a href="../index.html" target="_blank" class="action-btn success">
                        <i class="fas fa-external-link-alt"></i> View Website
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>