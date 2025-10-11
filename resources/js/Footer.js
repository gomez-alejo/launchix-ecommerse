        // Animación de entrada para elementos del footer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'slideUp 0.6s ease forwards';
                }
            });
        }, observerOptions);

        document.querySelectorAll('footer > div > div').forEach(el => {
            observer.observe(el);
        });