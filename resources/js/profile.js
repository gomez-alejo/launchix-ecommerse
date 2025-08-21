        // Navigation functionality
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
                section.classList.remove('active');
            });

            // Show selected section
            const targetSection = document.getElementById(sectionName + '-section');
            if (targetSection) {
                targetSection.classList.remove('hidden');
                targetSection.classList.add('active');
            }

            // Update menu active state
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active-menu');
            });
            event.target.classList.add('active-menu');
        }

        // Form validation and submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
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

        // Notification system
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const notificationText = document.getElementById('notification-text');
            
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
            // Email validation
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.value)) {
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500');
                }
            });

            // Phone validation
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function() {
                // Remove non-numeric characters except + and spaces
                this.value = this.value.replace(/[^+\d\s]/g, '');
            });

            // Username validation
            const usernameInput = document.getElementById('username');
            usernameInput.addEventListener('input', function() {
                // Remove spaces and special characters
                this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
            });
        });

        // Add smooth scrolling to sections
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sectionName = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                    showSection(sectionName);
                });
            });
        });