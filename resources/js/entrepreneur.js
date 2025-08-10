// entrepreneur.js - Manejo de navegación y dashboard del emprendedor

// Función para mostrar secciones - accesible globalmente
window.showSection = function(sectionId) {
    // Ocultar todas las secciones
    const sections = document.querySelectorAll('.section-content');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Mostrar la sección seleccionada
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.style.display = 'block';
    }

    // Actualizar el estado activo del menú
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.classList.remove('active');
    });

    // Encontrar y activar el elemento del menú clickeado
    const clickedItem = event?.target?.closest('.menu-item');
    if (clickedItem) {
        clickedItem.classList.add('active');
    }
};

// Función para cancelar formularios y volver al dashboard
window.cancelForm = function() {
    showSection('dashboard');
};

// Función para manejar otros formularios (servicios, perfil, etc.)
function handleFormSubmission(formId, successMessage, redirectSection = null) {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Aquí puedes agregar lógica de envío real
            alert(successMessage);
            
            // Redirigir a una sección específica si se proporciona
            if (redirectSection && window.showSection) {
                window.showSection(redirectSection);
            }
        });
    }
}

// Inicialización del dashboard cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    console.log('Entrepreneur dashboard script cargado correctamente');
    
    // Configurar navegación inicial - mostrar dashboard por defecto
    const defaultSection = 'dashboard';
    if (document.getElementById(defaultSection)) {
        showSection(defaultSection);
    }
    
    // Agregar event listeners a todos los botones de menú
    const menuButtons = document.querySelectorAll('.menu-item');
    menuButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Obtener el onclick attribute para extraer el sectionId
            const onclickAttr = this.getAttribute('onclick');
            if (onclickAttr) {
                const match = onclickAttr.match(/showSection\('([^']+)'\)/);
                if (match) {
                    const sectionId = match[1];
                    showSection(sectionId);
                }
            }
        });
    });

    // Manejar formularios de servicios, perfil y configuración
    handleFormSubmission('servicio-form', 'Servicio publicado exitosamente!', 'servicios');
    handleFormSubmission('perfil-form', 'Perfil actualizado exitosamente!');
    handleFormSubmission('password-form', 'Contraseña cambiada exitosamente!');
    
    // Manejar formularios de configuración adicionales
    const configForms = document.querySelectorAll('.config-form');
    configForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Configuración guardada exitosamente!');
        });
    });

    // Agregar funcionalidad para estadísticas del dashboard
    updateDashboardStats();
});

// Función para actualizar estadísticas del dashboard
function updateDashboardStats() {
    // Esta función puede ser expandida para obtener datos reales del servidor
    const statsCards = document.querySelectorAll('.stat-card');
    statsCards.forEach(card => {
        const countElement = card.querySelector('.stat-count');
        if (countElement) {
            // Animación simple para los números
            const finalValue = parseInt(countElement.textContent);
            animateNumber(countElement, 0, finalValue, 1000);
        }
    });
}

// Función para animar números en el dashboard
function animateNumber(element, start, end, duration) {
    const startTime = Date.now();
    const timer = setInterval(() => {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const current = Math.round(start + (end - start) * progress);
        element.textContent = current;
        
        if (progress === 1) {
            clearInterval(timer);
        }
    }, 16);
}