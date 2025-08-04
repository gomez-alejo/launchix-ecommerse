
        // Navigation functionality
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.section-content');
            sections.forEach(section => {
                section.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            
            // Update menu active state
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.classList.remove('active');
            });
            
            // Find and activate the clicked menu item
            event.target.closest('.menu-item').classList.add('active');
        }
        
        // Image upload functionality for products
        function setupImageUpload(dropzoneId, inputId, previewId) {
            const dropzone = document.getElementById(dropzoneId);
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            dropzone.addEventListener('click', () => input.click());
            
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('drag-over');
            });
            
            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('drag-over');
            });
            
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('drag-over');
                const files = e.dataTransfer.files;
                handleFiles(files, preview);
            });
            
            input.addEventListener('change', (e) => {
                handleFiles(e.target.files, preview);
            });
        }
        
        function handleFiles(files, preview) {
            preview.innerHTML = '';
            
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'image-preview';
                        
                        const container = document.createElement('div');
                        container.className = 'relative';
                        
                        const deleteBtn = document.createElement('button');
                        deleteBtn.innerHTML = '×';
                        deleteBtn.className = 'absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600';
                        deleteBtn.onclick = () => container.remove();
                        
                        container.appendChild(img);
                        container.appendChild(deleteBtn);
                        preview.appendChild(container);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Form submission handlers
        document.getElementById('producto-form').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Producto publicado exitosamente!');
            showSection('productos');
        });
        
        document.getElementById('servicio-form').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Servicio publicado exitosamente!');
            showSection('servicios');
        });
        
        document.getElementById('perfil-form').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Perfil actualizado exitosamente!');
        });
        
        document.getElementById('password-form').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Contraseña cambiada exitosamente!');
        });
        
        // Initialize image upload functionality
        document.addEventListener('DOMContentLoaded', () => {
            setupImageUpload('product-dropzone', 'product-images', 'product-preview');
            setupImageUpload('service-main-dropzone', 'service-main-image', 'service-main-preview');
            setupImageUpload('service-gallery-dropzone', 'service-gallery-images', 'service-gallery-preview');
        });