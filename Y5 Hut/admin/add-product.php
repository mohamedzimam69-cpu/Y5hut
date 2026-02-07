<?php
session_start();
require_once '../backend/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Get categories
try {
    $db->query('SELECT * FROM categories WHERE status = "active" ORDER BY name');
    $categories = $db->resultset();
} catch (Exception $e) {
    $categories = [];
}

$error = '';
$success = '';

if ($_POST) {
    $name = trim($_POST['name']);
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $original_price = $_POST['original_price'] ?: null;
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    // Specifications
    $specs = [];
    if (!empty($_POST['spec_keys']) && !empty($_POST['spec_values'])) {
        foreach ($_POST['spec_keys'] as $index => $key) {
            if (!empty($key) && !empty($_POST['spec_values'][$index])) {
                $specs[trim($key)] = trim($_POST['spec_values'][$index]);
            }
        }
    }
    
    // Validation
    if (empty($name) || empty($category_id) || empty($price)) {
        $error = 'Please fill in all required fields';
    } else {
        // Handle image upload
        $image_name = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($filetype), $allowed)) {
                $image_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
                $upload_path = '../assets/images/' . $image_name;
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $error = 'Failed to upload image';
                }
            } else {
                $error = 'Invalid image format. Please use JPG, PNG, or GIF';
            }
        }
        
        if (empty($error)) {
            try {
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
                
                $db->query('INSERT INTO products (name, slug, category_id, price, original_price, description, specifications, image, status, featured) 
                           VALUES (:name, :slug, :category_id, :price, :original_price, :description, :specifications, :image, :status, :featured)');
                
                $db->bind(':name', $name);
                $db->bind(':slug', $slug);
                $db->bind(':category_id', $category_id);
                $db->bind(':price', $price);
                $db->bind(':original_price', $original_price);
                $db->bind(':description', $description);
                $db->bind(':specifications', json_encode($specs));
                $db->bind(':image', $image_name);
                $db->bind(':status', $status);
                $db->bind(':featured', $featured);
                
                if ($db->execute()) {
                    $success = 'Product added successfully!';
                    // Clear form data
                    $_POST = [];
                } else {
                    $error = 'Failed to add product';
                }
            } catch (Exception $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Y5 Hut Admin</title>
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
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="products.php" class="active"><i class="fas fa-box"></i> Products</a></li>
                    <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
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
                <h1>Add New Product</h1>
                <div class="admin-user">
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <!-- Messages -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Add Product Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><i class="fas fa-plus"></i> Product Information</h3>
                </div>
                <div class="card-content">
                    <form method="POST" enctype="multipart/form-data" class="product-form">
                        <div class="form-grid">
                            <!-- Basic Information -->
                            <div class="form-section">
                                <h4>Basic Information</h4>
                                
                                <div class="form-group">
                                    <label for="name">Product Name *</label>
                                    <input type="text" id="name" name="name" class="form-control" 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="category_id">Category *</label>
                                    <select id="category_id" name="category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" 
                                                    <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="price">Price (Rs.) *</label>
                                        <input type="number" id="price" name="price" class="form-control" step="0.01" 
                                               value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="original_price">Original Price (Rs.)</label>
                                        <input type="number" id="original_price" name="original_price" class="form-control" step="0.01"
                                               value="<?php echo isset($_POST['original_price']) ? $_POST['original_price'] : ''; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="form-section">
                                <h4>Product Image</h4>
                                
                                <div class="form-group">
                                    <label for="image">Upload Image *</label>
                                    <div class="image-upload-area">
                                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                                        <div class="upload-hint">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p>Choose an image file (JPG, PNG, GIF)</p>
                                            <p>Recommended size: 400x400px</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="image-preview" id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview">
                                </div>
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div class="form-section">
                            <h4>Specifications</h4>
                            <div id="specifications">
                                <div class="spec-row">
                                    <input type="text" name="spec_keys[]" placeholder="Specification name (e.g., Display)" class="form-control">
                                    <input type="text" name="spec_values[]" placeholder="Value (e.g., 6.1 inch)" class="form-control">
                                    <button type="button" class="btn btn-danger btn-sm remove-spec">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="addSpec" class="btn btn-secondary btn-sm">
                                <i class="fas fa-plus"></i> Add Specification
                            </button>
                        </div>

                        <!-- Settings -->
                        <div class="form-section">
                            <h4>Settings</h4>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="active" <?php echo (isset($_POST['status']) && $_POST['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo (isset($_POST['status']) && $_POST['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="featured" value="1" 
                                               <?php echo (isset($_POST['featured'])) ? 'checked' : ''; ?>>
                                        <span class="checkmark"></span>
                                        Featured Product
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Product
                            </button>
                            <a href="products.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Add specification
        document.getElementById('addSpec').addEventListener('click', function() {
            const specsContainer = document.getElementById('specifications');
            const newSpec = document.createElement('div');
            newSpec.className = 'spec-row';
            newSpec.innerHTML = `
                <input type="text" name="spec_keys[]" placeholder="Specification name" class="form-control">
                <input type="text" name="spec_values[]" placeholder="Value" class="form-control">
                <button type="button" class="btn btn-danger btn-sm remove-spec">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            specsContainer.appendChild(newSpec);
        });

        // Remove specification
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-spec')) {
                e.target.closest('.spec-row').remove();
            }
        });
    </script>
</body>
</html>