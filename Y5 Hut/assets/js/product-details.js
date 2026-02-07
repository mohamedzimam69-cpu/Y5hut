// Product Details Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    loadProductDetails();
    loadRelatedProducts();
});

function loadProductDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    
    if (!productId) {
        window.location.href = 'products.html';
        return;
    }

    const product = getProductById(productId);
    
    if (!product) {
        document.getElementById('product-detail-content').innerHTML = `
            <div class="text-center">
                <h2>Product Not Found</h2>
                <p>The product you're looking for doesn't exist.</p>
                <a href="products.html" class="btn btn-primary">Back to Products</a>
            </div>
        `;
        return;
    }

    // Update page title
    document.title = `${product.name} - Y5 Hut Mobile Shop`;

    // Render product details
    document.getElementById('product-detail-content').innerHTML = `
        <div class="product-gallery">
            <img src="${product.image}" alt="${product.name}" class="main-image" 
                 onerror="this.src='assets/images/placeholder-phone.jpg'">
        </div>
        <div class="product-info-detail">
            <h1>${product.name}</h1>
            <div class="product-price-detail">${product.price}</div>
            
            <div class="product-specs">
                <h3>Specifications</h3>
                ${Object.entries(product.specs).map(([key, value]) => `
                    <div class="spec-item">
                        <span><strong>${formatSpecKey(key)}:</strong></span>
                        <span>${value}</span>
                    </div>
                `).join('')}
            </div>

            <div class="product-actions">
                <button onclick="contactWhatsApp('Hi, I\\'m interested in ${product.name}. Can you provide more details and availability?')" 
                        class="btn btn-primary">
                    <i class="fab fa-whatsapp"></i> Contact to Buy
                </button>
                <a href="tel:+94777123456" class="btn btn-outline">
                    <i class="fas fa-phone"></i> Call Now
                </a>
            </div>

            <div class="product-features">
                <h3>Why Choose This Product?</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Genuine product with warranty</li>
                    <li><i class="fas fa-check"></i> Best price in Akkaraipattu</li>
                    <li><i class="fas fa-check"></i> Expert technical support</li>
                    <li><i class="fas fa-check"></i> Free setup and data transfer</li>
                </ul>
            </div>
        </div>
    `;
}

function loadRelatedProducts() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    const currentProduct = getProductById(productId);
    
    if (!currentProduct) return;

    // Get products from same category, excluding current product
    const relatedProducts = allProducts
        .filter(product => product.category === currentProduct.category && product.id != productId)
        .slice(0, 3);

    const relatedGrid = document.getElementById('related-products-grid');
    if (relatedGrid && relatedProducts.length > 0) {
        relatedGrid.innerHTML = relatedProducts.map(product => createProductCard(product)).join('');
    } else if (relatedGrid) {
        relatedGrid.innerHTML = '<p class="text-center">No related products found.</p>';
    }
}

function formatSpecKey(key) {
    const keyMap = {
        display: 'Display',
        processor: 'Processor',
        ram: 'RAM',
        storage: 'Storage',
        camera: 'Camera',
        type: 'Type',
        power: 'Power',
        compatibility: 'Compatibility',
        cable: 'Cable',
        length: 'Length',
        material: 'Material',
        battery: 'Battery Life',
        connectivity: 'Connectivity',
        features: 'Features'
    };
    
    return keyMap[key] || key.charAt(0).toUpperCase() + key.slice(1);
}