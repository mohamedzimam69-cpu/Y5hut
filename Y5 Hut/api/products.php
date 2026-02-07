<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../backend/db.php';

try {
    if ($db === null) {
        throw new Exception('Database connection failed');
    }

    // Get featured products for homepage
    if (isset($_GET['featured']) && $_GET['featured'] == 'true') {
        $db->query('SELECT p.*, c.name as category_name FROM products p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   WHERE p.status = "active" AND p.featured = 1 
                   ORDER BY p.created_at DESC LIMIT 6');
    } 
    // Get products by category
    else if (isset($_GET['category']) && !empty($_GET['category'])) {
        if ($_GET['category'] == 'all') {
            $db->query('SELECT p.*, c.name as category_name FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.status = "active" 
                       ORDER BY p.created_at DESC');
        } else {
            $db->query('SELECT p.*, c.name as category_name FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.status = "active" AND c.slug = :category 
                       ORDER BY p.created_at DESC');
            $db->bind(':category', $_GET['category']);
        }
    }
    // Get all active products
    else {
        $db->query('SELECT p.*, c.name as category_name FROM products p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   WHERE p.status = "active" 
                   ORDER BY p.created_at DESC');
    }
    
    $products = $db->resultset();
    
    // Format products for frontend
    $formattedProducts = [];
    foreach ($products as $product) {
        $specs = json_decode($product['specifications'], true) ?: [];
        
        $formattedProducts[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => 'Rs. ' . number_format($product['price'], 0),
            'original_price' => $product['original_price'] ? 'Rs. ' . number_format($product['original_price'], 0) : null,
            'image' => $product['image'] ? 'assets/images/' . $product['image'] : 'assets/images/placeholder-phone.jpg',
            'category' => strtolower($product['category_name']),
            'category_name' => $product['category_name'],
            'description' => $product['description'],
            'specifications' => $specs,
            'featured' => (bool)$product['featured']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'products' => $formattedProducts
    ]);
    
} catch (Exception $e) {
    // Return sample data if database fails
    $sampleProducts = [
        [
            'id' => 1,
            'name' => 'Samsung Galaxy A54',
            'price' => 'Rs. 89,900',
            'original_price' => 'Rs. 94,900',
            'image' => 'assets/images/samsung-a54.jpg',
            'category' => 'samsung',
            'category_name' => 'Samsung',
            'description' => 'Mid-range Samsung with excellent camera performance',
            'specifications' => [
                'display' => '6.4" Super AMOLED',
                'processor' => 'Exynos 1380',
                'ram' => '8GB',
                'storage' => '128GB',
                'camera' => '50MP Triple Camera'
            ],
            'featured' => true
        ],
        [
            'id' => 2,
            'name' => 'iPhone 14',
            'price' => 'Rs. 289,900',
            'original_price' => 'Rs. 299,900',
            'image' => 'assets/images/iphone-14.jpg',
            'category' => 'iphone',
            'category_name' => 'iPhone',
            'description' => 'Powerful iPhone with excellent performance and camera',
            'specifications' => [
                'display' => '6.1" Super Retina XDR',
                'processor' => 'A15 Bionic',
                'ram' => '6GB',
                'storage' => '128GB',
                'camera' => '12MP Dual Camera'
            ],
            'featured' => true
        ],
        [
            'id' => 3,
            'name' => 'Redmi Note 12',
            'price' => 'Rs. 54,900',
            'original_price' => 'Rs. 59,900',
            'image' => 'assets/images/redmi-note12.jpg',
            'category' => 'redmi',
            'category_name' => 'Redmi',
            'description' => 'Affordable Redmi with great value for money',
            'specifications' => [
                'display' => '6.67" AMOLED',
                'processor' => 'Snapdragon 685',
                'ram' => '6GB',
                'storage' => '128GB',
                'camera' => '50MP Triple Camera'
            ],
            'featured' => true
        ]
    ];
    
    echo json_encode([
        'success' => false,
        'products' => $sampleProducts,
        'error' => $e->getMessage()
    ]);
}
?>