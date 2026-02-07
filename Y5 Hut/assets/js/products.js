// Products Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    loadAllProducts();
});

// Load all products from API
function loadAllProducts() {
    const productsGrid = document.getElementById('products-grid');
    
    if (productsGrid) {
        // Show loading
        productsGrid.innerHTML = '<div style="text-align: center; padding: 2rem; color: #666;"><i class="fas fa-spinner fa-spin"></i> Loading products...</div>';
        
        // Fetch products from API
        fetch('api/products.php')
            .then(response => response.json())
            .then(data => {
                if (data.products && data.products.length > 0) {
                    displayProducts(data.products);
                } else {
                    productsGrid.innerHTML = `
                        <div style="text-align: center; padding: 3rem; color: #666; grid-column: 1 / -1;">
                            <i class="fas fa-box-open" style="font-size: 4rem; margin-bottom: 1rem; display: block;"></i>
                            <h3>No Products Available</h3>
                            <p>Products will appear here once added by admin</p>
                            <a href="admin/login.php" style="color: #E53E3E; text-decoration: none; font-weight: 600;">Go to Admin Panel</a>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
                // Show error message
                productsGrid.innerHTML = `
                    <div style="text-align: center; padding: 3rem; color: #666; grid-column: 1 / -1;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 4rem; margin-bottom: 1rem; display: block; color: #E53E3E;"></i>
                        <h3>Unable to Load Products</h3>
                        <p>Please check your database connection</p>
                        <button onclick="loadAllProducts()" style="background: #E53E3E; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; margin-top: 1rem; cursor: pointer;">Try Again</button>
                    </div>
                `;
            });
    }
}

// Display products in grid
function displayProducts(products) {
    const productsGrid = document.getElementById('products-grid');
    
    productsGrid.innerHTML = products.map(product => createProductCard(product)).join('');
}

// Create product card HTML
function createProductCard(product) {
    return `
        <div class="product-card premium-hover" data-category="${product.category}">
            <img src="${product.image}" alt="${product.name}" class="product-image" 
                 onerror="this.src='assets/images/placeholder-phone.jpg'">
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <div class="product-price">${product.price}</div>
                ${product.original_price ? `<div class="original-price" style="text-decoration: line-through; color: #999; font-size: 0.9rem;">${product.original_price}</div>` : ''}
                <div class="product-actions" style="display: flex; gap: 0.5rem; flex-direction: column; margin-top: 1rem;">
                    <a href="product-details.html?id=${product.id}" class="btn btn-primary">View Details</a>
                    <button onclick="contactWhatsApp('Hi, I am interested in ${product.name}. Is it available?')" 
                            class="btn btn-outline" style="background: #25D366; color: white; border-color: #25D366;">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Filter products by category
function filterProducts(category = 'all') {
    const productsGrid = document.getElementById('products-grid');
    
    // Show loading
    productsGrid.innerHTML = '<div style="text-align: center; padding: 2rem; color: #666;"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    
    // Fetch filtered products
    fetch(`api/products.php?category=${category}`)
        .then(response => response.json())
        .then(data => {
            if (data.products && data.products.length > 0) {
                displayProducts(data.products);
            } else {
                productsGrid.innerHTML = `
                    <div style="text-align: center; padding: 3rem; color: #666; grid-column: 1 / -1;">
                        <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                        <h3>No Products Found</h3>
                        <p>No products available in this category</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error filtering products:', error);
            loadAllProducts(); // Fallback to all products
        });

    // Update active filter button
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === category) {
            btn.classList.add('active');
        }
    });
}

// Get product by ID (for product details page)
function getProductById(id) {
    return fetch(`api/products.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.products && data.products.length > 0) {
                return data.products[0];
            }
            return null;
        });
}