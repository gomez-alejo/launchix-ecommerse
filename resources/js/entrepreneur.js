/**
 * Navigation.js
 * Maneja la navegación entre secciones de la aplicación
 */

// Función para mostrar secciones - ahora accesible globalmente
window.showSection = function(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.section-content');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Show selected section
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    // Update menu active state
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.classList.remove('active');
    });
    
    // Find and activate the clicked menu item
    const clickedItem = event?.target?.closest('.menu-item');
    if (clickedItem) {
        clickedItem.classList.add('active');
    }
    
    // Si estamos mostrando la sección de servicios, cargar los datos
    if (sectionId === 'servicios') {
        setTimeout(() => {
            if (window.ServicesManager && window.ServicesManager.loadServicios) {
                window.ServicesManager.loadServicios();
            }
        }, 100);
    }
};

// Función para mostrar toast notifications
window.showToast = function(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    toast.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' ? 
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                    type === 'error' ?
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' :
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remover después de 4 segundos
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 4000);
};

// Función para mostrar mensajes de éxito
window.showSuccessMessage = function(message) {
    showToast(message, 'success');
};

// Función para mostrar mensajes de error
window.showErrorMessage = function(message) {
    showToast(message, 'error');
};

// Función para mostrar diálogo de confirmación personalizado
window.showConfirmDialog = function({ title, message, confirmText, cancelText, type = 'warning' }) {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        
        const iconColor = type === 'danger' ? 'text-red-600' : 'text-yellow-600';
        const confirmButtonColor = type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-600 hover:bg-yellow-700';
        
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md mx-4">
                <div class="flex items-center mb-4">
                    <svg class="w-8 h-8 ${iconColor} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">${title}</h3>
                </div>
                <p class="text-gray-600 mb-6">${message}</p>
                <div class="flex justify-end space-x-3">
                    <button id="cancel-btn" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        ${cancelText}
                    </button>
                    <button id="confirm-btn" class="px-4 py-2 text-white ${confirmButtonColor} rounded-lg">
                        ${confirmText}
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        const cancelBtn = modal.querySelector('#cancel-btn');
        const confirmBtn = modal.querySelector('#confirm-btn');
        
        cancelBtn.onclick = () => {
            document.body.removeChild(modal);
            resolve(false);
        };
        
        confirmBtn.onclick = () => {
            document.body.removeChild(modal);
            resolve(true);
        };
        
        // Cerrar con ESC
        const handleKeyDown = (e) => {
            if (e.key === 'Escape') {
                document.body.removeChild(modal);
                document.removeEventListener('keydown', handleKeyDown);
                resolve(false);
            }
        };
        document.addEventListener('keydown', handleKeyDown);
    });
};

// Inicialización de navegación
document.addEventListener('DOMContentLoaded', () => {
    console.log('Navigation script loaded successfully');
    
    // Aquí puedes agregar más inicializaciones de navegación si es necesario
    // Por ejemplo, configurar eventos de teclado para navegación, etc.
});