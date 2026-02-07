// Mobile Navigation Toggle - Simplified
document.addEventListener('DOMContentLoaded', function() {
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

// Load Featured Products (Demo Data) - Simplified
function loadFeaturedProducts() {
    const dealsGrid = document.getElementById('deals-grid');
    
    // Demo products data
    const featuredProducts = [
        {
            id: 1,
            name: 'Samsung Galaxy A54',
            price: 'Rs. 89,900',
            image: 'assets/images/samsung-a54.jpg',
            category: 'Samsung'
        },
        {
            id: 2,
            name: 'iPhone 14',
            price: 'Rs. 289,900',
            image: 'assets/images/iphone-14.jpg',
            category: 'iPhone'
        },
        {
            id: 3,
            name: 'Redmi Note 12',
            price: 'Rs. 54,900',
            image: 'assets/images/redmi-note12.jpg',
            category: 'Redmi'
        }
    ];

    if (dealsGrid) {
        dealsGrid.innerHTML = featuredProducts.map(product => `
            <div class="product-card">
                <img src="${product.image}" alt="${product.name}" class="product-image" 
                     onerror="this.src='assets/images/placeholder-phone.jpg'">
                <div class="product-info">
                    <h3 class="product-name">${product.name}</h3>
                    <div class="product-price">${product.price}</div>
                    <a href="product-details.html?id=${product.id}" class="btn btn-primary product-btn">View Details</a>
                </div>
            </div>
        `).join('');
    }
}

// WhatsApp Integration - Simplified
function contactWhatsApp(message = '') {
    const phoneNumber = '94777123456'; // Replace with actual number
    const defaultMessage = message || 'Hi, I\'m interested in your mobile phones. Can you help me?';
    const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(defaultMessage)}`;
    window.open(whatsappURL, '_blank');
}

// Product Filter Function (for products page) - Simplified
function filterProducts(category = 'all') {
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        if (category === 'all' || product.dataset.category === category) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
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

// Scroll to Top Function - Simplified
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Form Validation - Simplified
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