# Y5 Hut Mobile Shop Website

A premium, responsive website for Y5 Hut Mobile Shop in Akkaraipattu, Sri Lanka.

## 🎨 Design Features

- **Premium Color Scheme**: Inspired by the Y5 Hut logo with vibrant reds and sophisticated grays
- **Responsive Design**: Mobile-first approach with perfect display on all devices
- **Modern UI**: Glass morphism effects, smooth animations, and premium shadows
- **Fast Performance**: Optimized CSS and JavaScript for quick loading

## 🚀 Quick Setup

### Local Development (XAMPP/WAMP)

1. **Install XAMPP or WAMP**
   - Download from [XAMPP](https://www.apachefriends.org/) or [WAMP](https://www.wampserver.com/)

2. **Setup Project**
   ```bash
   # Copy project to web server directory
   cp -r y5hut/ C:/xampp/htdocs/  # Windows XAMPP
   # or
   cp -r y5hut/ /Applications/XAMPP/htdocs/  # Mac XAMPP
   ```

3. **Database Setup**
   - Start Apache and MySQL in XAMPP/WAMP
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Import `database/y5hut.sql`

4. **Access Website**
   - Frontend: `http://localhost/y5hut/`
   - Admin Panel: `http://localhost/y5hut/admin/login.php`
   - Default Admin: username `admin`, password `admin123`

## 📁 Project Structure

```
y5hut/
├── index.html              # Homepage
├── products.html           # Product catalog
├── product-details.html    # Individual product pages
├── about.html             # About page
├── contact.html           # Contact page
├── assets/
│   ├── css/
│   │   └── style.css      # Main stylesheet
│   ├── js/
│   │   ├── script.js      # Core functionality
│   │   ├── products.js    # Product management
│   │   ├── product-details.js
│   │   └── contact.js     # Contact form
│   └── images/            # Product images
├── admin/
│   ├── login.php          # Admin login
│   └── dashboard.php      # Admin dashboard
├── backend/
│   └── db.php            # Database connection
└── database/
    └── y5hut.sql         # Database schema
```

## 🎯 Features

### Frontend
- **Homepage**: Hero section, featured products, quick contact
- **Products**: Filterable catalog by brand (Samsung, iPhone, Redmi, Accessories)
- **Product Details**: Specifications, pricing, WhatsApp integration
- **About**: Company story, features, team information
- **Contact**: Contact form, map, multiple contact methods

### Backend (PHP/MySQL)
- **Admin Panel**: Secure login system
- **Product Management**: CRUD operations for products
- **Category Management**: Organize products by brand
- **Contact Messages**: View customer inquiries
- **User Management**: Admin user accounts

### Technical Features
- **WhatsApp Integration**: Direct messaging for inquiries
- **SEO Optimized**: Meta tags, semantic HTML
- **Form Validation**: Client-side and server-side validation
- **Security**: Password hashing, prepared statements
- **Responsive**: Mobile-first design approach

## 🛠 Customization

### Update Contact Information
Edit these files with your actual details:
- Phone numbers in all HTML files
- Email addresses in contact.html
- Address in footer sections
- WhatsApp number in JavaScript files

### Add Product Images
1. Add images to `assets/images/` folder
2. Update image paths in `assets/js/products.js`
3. Recommended image size: 400x400px for products

### Modify Colors
Update CSS variables in `assets/css/style.css`:
```css
:root {
    --primary-red: #E53E3E;
    --logo-red: #DC2626;
    --deep-red: #B91C1C;
    /* Add your custom colors */
}
```

## 📱 Browser Support

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## 🔧 Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+, MySQL 5.7+
- **Frameworks**: Bootstrap (optional), Font Awesome
- **Fonts**: Google Fonts (Poppins)

## 📞 Support

For technical support or customization requests, contact the development team.

---

**Y5 Hut Mobile Shop** - Your trusted mobile phone retailer in Akkaraipattu, Sri Lanka.