// MINIMAL JAVASCRIPT - NO ANIMATIONS OR EFFECTS
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Navigation Toggle - Simple
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });

        // Close menu when clicking on a link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
            });
        });
    }

    // Load featured products on home page
    if (document.getElementById('deals-grid')) {
        loadFeaturedProducts();
    }
});

// Load Featured Products from API
function loadFeaturedProducts() {
    const dealsGrid = document.getElementById('deals-grid');
    
    if (dealsGrid) {
        // Show loading
        dealsGrid.innerHTML = '<div style="text-align: center; padding: 2rem; color: #666;"><i class="fas fa-spinner fa-spin"></i> Loading products...</div>';
        
        // Fetch products from API
        fetch('api/products.php?featured=true')
            .then(response => response.json())
            .then(data => {
                if (data.products && data.products.length > 0) {
                    dealsGrid.innerHTML = data.products.map(product => `
                        <div class="product-card">
                            <img src="${product.image}" alt="${product.name}" class="product-image" 
                                 onerror="this.src='assets/images/placeholder-phone.jpg'">
                            <div class="product-info">
                                <h3 class="product-name">${product.name}</h3>
                                <div class="product-price">${product.price}</div>
                                ${product.original_price ? `<div class="original-price">${product.original_price}</div>` : ''}
                                <a href="product-details.html?id=${product.id}" class="btn btn-primary product-btn">View Details</a>
                            </div>
                        </div>
                    `).join('');
                } else {
                    dealsGrid.innerHTML = `
                        <div style="text-align: center; padding: 2rem; color: #666;">
                            <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            <h3>No Products Available</h3>
                            <p>Products will appear here once added by admin</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
                // Fallback to sample data
                loadSampleProducts(dealsGrid);
            });
    }
}

// Fallback sample products
function loadSampleProducts(dealsGrid) {
    const sampleProducts = [
        {
            id: 1,
            name: 'Samsung Galaxy A54',
            price: 'Rs. 89,900',
            image: 'assets/images/placeholder-phone.jpg'
        },
        {
            id: 2,
            name: 'iPhone 14',
            price: 'Rs. 289,900',
            image: 'assets/images/placeholder-phone.jpg'
        },
        {
            id: 3,
            name: 'Redmi Note 12',
            price: 'Rs. 54,900',
            image: 'assets/images/placeholder-phone.jpg'
        }
    ];

    dealsGrid.innerHTML = sampleProducts.map(product => `
        <div class="product-card">
            <img src="${product.image}" alt="${product.name}" class="product-image">
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <div class="product-price">${product.price}</div>
                <a href="product-details.html?id=${product.id}" class="btn btn-primary product-btn">View Details</a>
            </div>
        </div>
    `).join('');
}

// WhatsApp Integration - Simple
function contactWhatsApp(message = '') {
    const phoneNumber = '94777123456';
    const defaultMessage = message || 'Hi, I am interested in your mobile phones. Can you help me?';
    const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(defaultMessage)}`;
    window.open(whatsappURL, '_blank');
}

// Product Filter - Simple
function filterProducts(category = 'all') {
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        if (category === 'all' || product.dataset.category === category) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });

    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === category) {
            btn.classList.add('active');
        }
    });
}

// Scroll to Top - Simple
function scrollToTop() {
    window.scrollTo(0, 0);
}

// Form Validation - Simple
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const inputs = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });

    return isValid;
}