// Main JavaScript file for Workshop AWS BCRP
// This file validates that JavaScript is loading and executing correctly

document.addEventListener('DOMContentLoaded', function() {
    console.log('Workshop AWS BCRP - JavaScript loaded successfully');
    console.log('Site hosted on Amazon S3');
    
    // Add current year to footer if needed
    updateFooterYear();
    
    // Initialize contact form if present
    initContactForm();
    
    // Add smooth scrolling to all links
    initSmoothScrolling();
    
    // Log page load time
    logPageLoadTime();
});

// Update footer year dynamically
function updateFooterYear() {
    const currentYear = new Date().getFullYear();
    const footerElements = document.querySelectorAll('footer p');
    
    footerElements.forEach(function(element) {
        if (element.textContent.includes('2024')) {
            element.textContent = element.textContent.replace('2024', currentYear);
        }
    });
}

// Initialize contact form validation and submission
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const laboratorio = document.getElementById('laboratorio').value;
            const mensaje = document.getElementById('mensaje').value;
            
            // Validate form
            if (!nombre || !email || !mensaje) {
                showFormMessage('Por favor complete todos los campos obligatorios.', 'error');
                return;
            }
            
            // Validate email format
            if (!isValidEmail(email)) {
                showFormMessage('Por favor ingrese un correo electrónico válido.', 'error');
                return;
            }
            
            // Simulate form submission
            console.log('Form submitted:', {
                nombre: nombre,
                email: email,
                laboratorio: laboratorio,
                mensaje: mensaje
            });
            
            // Show success message
            showFormMessage('Gracias por su consulta. Este es un formulario de demostración para el workshop.', 'success');
            
            // Reset form
            contactForm.reset();
        });
    }
}

// Show form message
function showFormMessage(message, type) {
    const messageDiv = document.getElementById('formMessage');
    
    if (messageDiv) {
        messageDiv.textContent = message;
        messageDiv.className = 'form-message ' + type;
        messageDiv.style.display = 'block';
        
        // Hide message after 5 seconds
        setTimeout(function() {
            messageDiv.style.display = 'none';
        }, 5000);
    }
}

// Validate email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Add smooth scrolling to all anchor links
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}

// Log page load time for performance monitoring
function logPageLoadTime() {
    if (window.performance && window.performance.timing) {
        window.addEventListener('load', function() {
            const loadTime = window.performance.timing.domContentLoadedEventEnd - 
                           window.performance.timing.navigationStart;
            console.log('Page load time: ' + loadTime + 'ms');
        });
    }
}

// Add active class to current navigation item
function setActiveNavigation() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(function(link) {
        const linkPage = link.getAttribute('href');
        
        if (linkPage === currentPage) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Call setActiveNavigation on page load
setActiveNavigation();

// Add console message for verification
console.log('='.repeat(50));
console.log('Workshop AWS BCRP - Día 2');
console.log('Laboratorio 2.1: Almacenamiento EBS y S3');
console.log('JavaScript ejecutándose correctamente');
console.log('='.repeat(50));
