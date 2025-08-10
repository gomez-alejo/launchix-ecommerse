// ProductPublishing.js - Manejo completo de productos (CRUD)

// Función para cargar los datos del producto en el formulario de edición
window.loadProductData = function(productId) {
    fetch(`/products/${productId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Cambiar el título del formulario a modo edición
        document.getElementById('form-title').textContent = 'Editar Producto';
        
        // Llenar los campos del formulario con los datos del producto
        document.getElementById('product-id').value = data.id;
        document.getElementById('product-name').value = data.name;
        document.getElementById('product-price').value = data.price;
        document.getElementById('product-stock').value = data.stock;
        document.getElementById('product-description').value = data.description;
        document.getElementById('product-category').value = data.category;
        
        // Cambiar el texto del botón submit
        document.getElementById('submit-button').textContent = 'Actualizar Producto';
        
        // Configurar el formulario para modo edición
        const form = document.getElementById('producto-form');
        form.action = `/products/${data.id}`;
        
        // Agregar o actualizar el input del método PUT
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
        } else {
            methodInput.value = 'PUT';
        }
        
        // Navegar a la sección de publicar producto
        if (window.showSection) {
            window.showSection('publicar-producto');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al cargar los datos del producto.');
    });
};

// Función para resetear el formulario a modo creación
window.resetProductForm = function() {
    document.getElementById('form-title').textContent = 'Publicar Producto';
    document.getElementById('submit-button').textContent = 'Publicar Producto';
    
    const form = document.getElementById('producto-form');
    form.reset();
    
    // Limpiar campos específicos
    document.getElementById('product-id').value = '';
    document.getElementById('product-name').value = '';
    document.getElementById('product-price').value = '';
    document.getElementById('product-stock').value = '';
    document.getElementById('product-description').value = '';
    document.getElementById('product-category').value = '';
    
    // Remover el input del método PUT si existe
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.remove();
    }
    
    // Resetear la acción del formulario
    form.action = '/products';
    
    // Navegar a la sección de publicar producto
    if (window.showSection) {
        window.showSection('publicar-producto');
    }
};

// Función para cancelar y volver a productos
window.cancelProductForm = function() {
    if (window.showSection) {
        window.showSection('productos');
    }
};

// Función para manejar la eliminación de productos
window.deleteProduct = function(productId, productName) {
    if (confirm(`¿Estás seguro de que deseas eliminar el producto "${productName}"?`)) {
        fetch(`/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { 
                    throw new Error(err.message || 'Error al eliminar el producto'); 
                });
            }
            return response.json();
        })
        .then(data => {
            alert(data.message || 'Producto eliminado exitosamente');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al eliminar el producto: ' + error.message);
        });
    }
};

// Función para validar formulario
function validateProductForm(formData) {
    const requiredFields = [
        { name: 'name', label: 'Nombre del producto' },
        { name: 'price', label: 'Precio' },
        { name: 'category', label: 'Categoría' },
        { name: 'stock', label: 'Stock' },
        { name: 'description', label: 'Descripción' }
    ];

    for (let field of requiredFields) {
        const value = formData.get(field.name);
        if (!value || !value.toString().trim()) {
            alert(`El campo "${field.label}" es obligatorio y no puede estar vacío.`);
            return false;
        }
    }

    // Validaciones específicas
    const price = parseFloat(formData.get('price'));
    if (isNaN(price) || price < 0) {
        alert('El precio debe ser un número válido mayor o igual a 0.');
        return false;
    }

    const stock = parseInt(formData.get('stock'));
    if (isNaN(stock) || stock < 0) {
        alert('El stock debe ser un número entero mayor o igual a 0.');
        return false;
    }

    return true;
}

// Función para mostrar mensajes de éxito/error
function showMessage(message, type = 'info') {
    // Crear elemento de mensaje
    const messageDiv = document.createElement('div');
    messageDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    messageDiv.textContent = message;
    
    document.body.appendChild(messageDiv);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Inicialización del manejo de productos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    console.log('ProductPublishing script cargado correctamente');

    // Manejo del formulario de productos
    const productForm = document.getElementById('producto-form');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            // Obtener el método del formulario
            const methodInput = form.querySelector('input[name="_method"]');
            const method = methodInput ? methodInput.value : 'POST';
            
            // Validar campos requeridos antes del envío
            const formData = new FormData(form);
            
            if (!validateProductForm(formData)) {
                return;
            }
            
            // Manejar request PUT (actualización)
            if (method === 'PUT') {
                // Convertir FormData a objeto plano para JSON
                const data = {};
                for (let [key, value] of formData.entries()) {
                    if (key !== '_method' && key !== '_token') {
                        data[key] = value;
                    }
                }
                
                console.log('Datos a enviar (PUT):', data);
                
                fetch(form.action, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { 
                            throw new Error(err.message || 'Error en la actualización'); 
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    showMessage(data.message, 'success');
                    if (data.success) {
                        setTimeout(() => {
                            resetProductForm();
                            window.location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Hubo un error al actualizar el producto: ' + error.message, 'error');
                });
                
            } else {
                // Manejar request POST (creación)
                console.log('Datos a enviar (POST):', Object.fromEntries(formData));
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { 
                            throw new Error(err.message || 'Error en la creación'); 
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    showMessage(data.message, 'success');
                    if (data.success) {
                        form.reset();
                        setTimeout(() => {
                            // Navegar a la sección de productos para ver el nuevo producto
                            if (window.showSection) {
                                window.showSection('productos');
                            }
                            window.location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Hubo un error al crear el producto: ' + error.message, 'error');
                });
            }
        });
    }

    // Configurar botones de edición existentes
    const editButtons = document.querySelectorAll('.btn-editar');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            if (productId) {
                window.loadProductData(productId);
            }
        });
    });
    
    // Configurar botones de eliminación existentes
    const deleteButtons = document.querySelectorAll('.btn-eliminar');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            
            if (productId && productName) {
                window.deleteProduct(productId, productName);
            }
        });
    });
});