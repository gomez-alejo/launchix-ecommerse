        // Funcionalidad del menú móvil
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Modal de categorías
        const categoriesButton = document.getElementById('categoriesButton');
        const categoriesModal = document.getElementById('categoriesModal');

        if (categoriesButton) {
            categoriesButton.addEventListener('click', (e) => {
                e.stopPropagation();
                categoriesModal.classList.toggle('hidden');
            });
        }

        // Modal de perfil
        const profileButton = document.getElementById('profileButton');
        const profileModal = document.getElementById('profileModal');

        if (profileButton) {
            profileButton.addEventListener('click', (e) => {
                e.stopPropagation();
                profileModal.classList.toggle('hidden');
            });
        }

        // Cerrar modales al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (categoriesModal && !categoriesModal.contains(e.target) && !categoriesButton.contains(e.target)) {
                categoriesModal.classList.add('hidden');
            }
            if (profileModal && !profileModal.contains(e.target) && !profileButton.contains(e.target)) {
                profileModal.classList.add('hidden');
            }
        });

        // Efecto de scroll para el navbar
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');

        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop;
        });