// profileSection.js - Manejo completo del perfil de usuario

/**
 * ========================================
 * UTILITY FUNCTIONS
 * ========================================
 */

function getCsrfToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    const inputToken = document.querySelector('input[name="_token"]');
    return metaToken?.content || inputToken?.value || '';
}

function showProfileNotification(message, type = 'success') {
    const notification = document.getElementById('profileNotification');
    const notificationText = document.getElementById('profileNotificationText');
    const notificationIcon = document.getElementById('notificationIcon');
    
    if (!notification || !notificationText || !notificationIcon) return;
    
    const icons = {
        success: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>',
        error: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>',
        warning: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>',
        info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>'
    };
    
    notificationIcon.innerHTML = icons[type] || icons.success;
    notificationText.textContent = message;
    notification.className = `mb-4 rounded-lg p-4 flex items-start gap-3 ${type}`;
    notification.classList.remove('hidden');
    
    setTimeout(() => notification.classList.add('hidden'), 5000);
}

function getInitials(name) {
    if (!name) return 'U';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-CO', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

function clearPasswordFields() {
    ['profileCurrentPassword', 'profileNewPassword', 'profileConfirmPassword'].forEach(id => {
        const field = document.getElementById(id);
        if (field) field.value = '';
    });
}

/**
 * ========================================
 * TAB MANAGEMENT
 * ========================================
 */

function showViewSection() {
    const viewSection = document.getElementById('viewProfileSection');
    const editSection = document.getElementById('editProfileSection');
    const tabView = document.getElementById('tabViewProfile');
    const tabEdit = document.getElementById('tabEditProfile');
    
    if (viewSection && editSection && tabView && tabEdit) {
        viewSection.classList.remove('hidden');
        editSection.classList.add('hidden');
        tabView.classList.add('active');
        tabEdit.classList.remove('active');
    }
}

function showEditSection() {
    const viewSection = document.getElementById('viewProfileSection');
    const editSection = document.getElementById('editProfileSection');
    const tabView = document.getElementById('tabViewProfile');
    const tabEdit = document.getElementById('tabEditProfile');
    
    if (viewSection && editSection && tabView && tabEdit) {
        viewSection.classList.add('hidden');
        editSection.classList.remove('hidden');
        tabView.classList.remove('active');
        tabEdit.classList.add('active');
    }
}

/**
 * ========================================
 * DATA LOADING
 * ========================================
 */

async function loadProfileData() {
    try {
        const response = await fetch('/profile/data', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }

        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.message || 'Error al cargar datos');
        }

        const data = result.data;
        updateViewSection(data);
        updateEditForm(data);

        console.log('Datos del perfil cargados correctamente');

    } catch (error) {
        console.error('Error al cargar perfil:', error);
        showProfileNotification('Error al cargar los datos del perfil: ' + error.message, 'error');
    }
}

function updateViewSection(data) {
    // Header info
    const userInitials = document.getElementById('viewUserInitials');
    const userName = document.getElementById('viewUserName');
    const userEmail = document.getElementById('viewUserEmail');
    
    if (userInitials) userInitials.textContent = getInitials(data.full_name);
    if (userName) userName.textContent = data.full_name || 'Usuario';
    if (userEmail) userEmail.textContent = data.email || '-';
    
    // Información personal y dirección
    const viewFields = {
        'viewName': data.full_name,
        'viewUsername': data.username,
        'viewEmail': data.email,
        'viewPhone': data.phone || '-',
        'viewBirthdate': formatDate(data.birthdate),
        'viewAddress': data.address || '-',
        'viewCity': data.city || '-',
        'viewPostalCode': data.postal_code || '-',
        'viewDepartment': data.department || '-',
        'viewReference': data.reference || '-',
        'viewLastUpdate': formatDate(data.last_updated)
    };

    Object.keys(viewFields).forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = viewFields[id] || '-';
        }
    });
}

function updateEditForm(data) {
    const editFields = {
        'profileName': data.name,
        'profileLastName': data.last_name,
        'profileUsername': data.username,
        'profileEmail': data.email,
        'profilePhone': data.phone,
        'profileBirthdate': data.birthdate,
        'profileAddress': data.address,
        'profileCity': data.city,
        'profilePostalCode': data.postal_code,
        'profileDepartment': data.department,
        'profileReference': data.reference
    };

    Object.keys(editFields).forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.value = editFields[id] || '';
        }
    });
    
    clearPasswordFields();
}

/**
 * ========================================
 * DATA UPDATE
 * ========================================
 */

async function updateProfile(formData) {
    try {
        const data = {};
        for (let [key, value] of formData.entries()) {
            if (key !== '_token') {
                data[key] = value;
            }
        }

        const response = await fetch('/profile/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (!response.ok) {
            if (result.errors) {
                const errorMessages = Object.values(result.errors).flat().join(', ');
                throw new Error(errorMessages);
            }
            throw new Error(result.message || 'Error al actualizar el perfil');
        }

        showProfileNotification(result.message || 'Perfil actualizado exitosamente', 'success');
        
        setTimeout(() => loadProfileData(), 500);
        
        return true;

    } catch (error) {
        console.error('Error al actualizar perfil:', error);
        showProfileNotification('Error: ' + error.message, 'error');
        return false;
    }
}

async function changePassword() {
    const currentPassword = document.getElementById('profileCurrentPassword')?.value;
    const newPassword = document.getElementById('profileNewPassword')?.value;
    const confirmPassword = document.getElementById('profileConfirmPassword')?.value;

    if (!currentPassword && !newPassword && !confirmPassword) {
        return true;
    }

    if (!currentPassword || !newPassword || !confirmPassword) {
        showProfileNotification('Debes llenar todos los campos de contraseña', 'error');
        return false;
    }

    if (newPassword !== confirmPassword) {
        showProfileNotification('Las contraseñas nuevas no coinciden', 'error');
        return false;
    }

    if (newPassword.length < 8) {
        showProfileNotification('La nueva contraseña debe tener al menos 8 caracteres', 'error');
        return false;
    }

    try {
        const response = await fetch('/profile/change-password', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            })
        });

        const result = await response.json();

        if (!response.ok) {
            if (result.errors) {
                const errorMessages = Object.values(result.errors).flat().join(', ');
                throw new Error(errorMessages);
            }
            throw new Error(result.message || 'Error al cambiar la contraseña');
        }

        showProfileNotification(result.message || 'Contraseña actualizada exitosamente', 'success');
        clearPasswordFields();
        
        return true;

    } catch (error) {
        console.error('Error al cambiar contraseña:', error);
        showProfileNotification('Error: ' + error.message, 'error');
        return false;
    }
}

/**
 * ========================================
 * EVENT HANDLERS
 * ========================================
 */

async function handleProfileFormSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = document.getElementById('profileSaveBtn');
    const originalHTML = submitBtn?.innerHTML || '';
    
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="inline-block w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Guardando...
        `;
    }

    try {
        const formData = new FormData(form);
        const profileUpdated = await updateProfile(formData);
        
        if (profileUpdated) {
            await changePassword();
        }
        
        if (profileUpdated) {
            setTimeout(() => showViewSection(), 1500);
        }

    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        }
    }
}

function handleProfileCancel() {
    loadProfileData();
    clearPasswordFields();
    showViewSection();
    showProfileNotification('Cambios cancelados', 'info');
}

/**
 * ========================================
 * INITIALIZATION
 * ========================================
 */

function initProfileForm() {
    const form = document.getElementById('profileForm');
    const cancelBtn = document.getElementById('profileCancelBtn');
    const tabView = document.getElementById('tabViewProfile');
    const tabEdit = document.getElementById('tabEditProfile');
    
    if (form) {
        form.removeEventListener('submit', handleProfileFormSubmit);
        form.addEventListener('submit', handleProfileFormSubmit);
    }
    
    if (cancelBtn) {
        cancelBtn.removeEventListener('click', handleProfileCancel);
        cancelBtn.addEventListener('click', handleProfileCancel);
    }
    
    if (tabView) {
        tabView.removeEventListener('click', showViewSection);
        tabView.addEventListener('click', showViewSection);
    }
    
    if (tabEdit) {
        tabEdit.removeEventListener('click', showEditSection);
        tabEdit.addEventListener('click', showEditSection);
    }
    
    loadProfileData();
    console.log('Módulo de perfil inicializado correctamente');
}

/**
 * ========================================
 * GLOBAL EXPORTS
 * ========================================
 */

window.loadProfileData = loadProfileData;
window.initProfileForm = initProfileForm;
window.showViewSection = showViewSection;
window.showEditSection = showEditSection;

/**
 * ========================================
 * AUTO-INITIALIZATION
 * ========================================
 */

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProfileForm);
} else {
    initProfileForm();
}