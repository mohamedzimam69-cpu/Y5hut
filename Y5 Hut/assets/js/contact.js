// Contact Form JavaScript
function submitContactForm(event) {
    event.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const originalText = submitBtn.innerHTML;
    
    // Validate form
    if (!validateForm('contact-form')) {
        return false;
    }
    
    // Show loading state
    showLoading(submitBtn);
    
    // Get form data
    const formData = new FormData(event.target);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        phone: formData.get('phone'),
        subject: formData.get('subject'),
        message: formData.get('message')
    };
    
    // Simulate form submission (in real app, this would send to backend)
    setTimeout(() => {
        // Hide loading state
        hideLoading(submitBtn, originalText);
        
        // Show success message
        showSuccessMessage();
        
        // Reset form
        document.getElementById('contact-form').reset();
        
        // Optional: Send WhatsApp message with form data
        const whatsappMessage = `New Contact Form Submission:
Name: ${data.name}
Email: ${data.email}
Phone: ${data.phone}
Subject: ${data.subject || 'General Inquiry'}
Message: ${data.message}`;
        
        // You can uncomment this to auto-send to WhatsApp
        // contactWhatsApp(whatsappMessage);
        
    }, 2000);
    
    return false;
}

function showSuccessMessage() {
    // Create success message element
    const successDiv = document.createElement('div');
    successDiv.className = 'success-message';
    successDiv.innerHTML = `
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <strong>Message Sent Successfully!</strong>
            <p>Thank you for contacting us. We'll get back to you within 24 hours.</p>
        </div>
    `;
    
    // Insert before the form
    const form = document.getElementById('contact-form');
    form.parentNode.insertBefore(successDiv, form);
    
    // Remove success message after 5 seconds
    setTimeout(() => {
        successDiv.remove();
    }, 5000);
    
    // Scroll to success message
    successDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Add CSS for success message
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .success-message {
            margin-bottom: 2rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert i {
            margin-right: 0.5rem;
            color: #28a745;
        }
        
        .alert strong {
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-top: 3rem;
        }
        
        .contact-section {
            padding: 60px 0;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
        }
        
        .contact-icon {
            background: #D32F2F;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .contact-details h3 {
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .contact-details a {
            color: #D32F2F;
            text-decoration: none;
        }
        
        .contact-details a:hover {
            text-decoration: underline;
        }
        
        .map-section {
            padding: 60px 0;
            background: #f8f9fa;
        }
        
        .map-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .contact-item {
                flex-direction: column;
                text-align: center;
            }
            
            .contact-icon {
                margin: 0 auto 1rem auto;
            }
        }
    `;
    document.head.appendChild(style);
});