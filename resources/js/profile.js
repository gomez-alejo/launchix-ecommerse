// Notification system
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const notificationText = document.getElementById('notification-text');
    
    if (!notification || !notificationText) return;
    
    notification.classList.remove('hidden', 'error-notification');
    
    if (type === 'error') {
        notification.classList.add('error-notification');
    }
    
    notificationText.textContent = message;
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 300);
    }, 3000);
}

// Form input validation
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and submission
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            // Password validation
            if (newPassword && newPassword !== confirmPassword) {
                showNotification('Las contraseñas no coinciden.', 'error');
                return;
            }
            
            if (newPassword && newPassword.length < 8) {
                showNotification('La contraseña debe tener al menos 8 caracteres.', 'error');
                return;
            }
            
            // Simulate form submission
            showNotification('Perfil actualizado exitosamente.', 'success');
            
            // Clear password fields
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        });
    }

    // Email validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
    }

    // Phone validation
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            // Remove non-numeric characters except + and spaces
            this.value = this.value.replace(/[^+\d\s]/g, '');
        });
    }

    // Username validation
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            // Remove spaces and special characters
            this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
        });
    }
});