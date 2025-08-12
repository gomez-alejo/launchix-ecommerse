// --- DATOS DE PRODUCTOS DE EJEMPLO ---
const products = [
    {
        id: 1,
        name: "Smartphone Premium X1",
        category: "electronics",
        price: 899.99,
        originalPrice: 1099.99,
        rating: 4.8,
        reviews: 256,
        image: "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&h=300&fit=crop",
        description: "Smartphone de última generación con cámara de 108MP",
        inStock: true,
        isNew: true,
        discount: 18,
        gallery_images: [
            "https://tiempodeinventos.net/wp-content/uploads/smartphone-moderno-pantalla-colores.webp",
            "https://tse3.mm.bing.net/th/id/OIP.KSY9ysnznbMhezUNjw5rhAHaEn?pid=Api&P=0&h=180",
            "https://tse1.mm.bing.net/th/id/OIP.MAsmPuvdbO92c3tzrxikkwHaEO?pid=Api&P=0&h=180"
        ],
        long_description: "El Smartphone Premium X1 cuenta con una pantalla AMOLED de 6.7 pulgadas, procesador de última generación, batería de larga duración y cámara de 108MP para fotos increíbles en cualquier situación. Incluye carga rápida y resistencia al agua."
    },
    {
        id: 2,
        name: "Laptop Gamer Pro",
        category: "electronics",
        price: 1299.99,
        originalPrice: 1499.99,
        rating: 4.9,
        reviews: 128,
        image: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&h=300&fit=crop",
        description: "Laptop gaming con RTX 4060 y 16GB RAM",
        inStock: true,
        isNew: false,
        discount: 13,
        gallery_images: [
            "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&h=300&fit=crop",
            "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300&h=300&fit=crop",
            "https://images.unsplash.com/photo-1519864600265-abb23847ef2c?w=300&h=300&fit=crop"
        ],
        long_description: "La Laptop Gamer Pro está equipada con una tarjeta gráfica RTX 4060, 16GB de RAM DDR5, almacenamiento SSD de 1TB y pantalla de 144Hz. Ideal para juegos exigentes y tareas de alto rendimiento."
    },
    {
        id: 3,
        name: "Camiseta Deportiva",
        category: "clothing",
        price: 29.99,
        originalPrice: 39.99,
        rating: 4.5,
        reviews: 89,
        image: "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&h=300&fit=crop",
        description: "Camiseta deportiva transpirable de alta calidad",
        inStock: true,
        isNew: false,
        discount: 25,
        gallery_images: [
            "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&h=300&fit=crop",
            "https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=300&h=300&fit=crop",
            "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop"
        ],
        long_description: "Camiseta deportiva fabricada con materiales de alta tecnología que absorben la humedad y permiten una ventilación óptima. Perfecta para entrenamientos intensos y actividades al aire libre."
    },
    {
        id: 4,
        name: "Sofá Moderno 3 Plazas",
        category: "home",
        price: 599.99,
        originalPrice: 799.99,
        rating: 4.7,
        reviews: 45,
        image: "https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=300&h=300&fit=crop",
        description: "Sofá moderno y cómodo para tu sala de estar",
        inStock: true,
        isNew: true,
        discount: 25
    },
    {
        id: 5,
        name: "Balón de Fútbol Profesional",
        category: "sports",
        price: 49.99,
        originalPrice: 69.99,
        rating: 4.6,
        reviews: 156,
        image: "https://images.unsplash.com/photo-1486286701208-1d58e9338013?w=300&h=300&fit=crop",
        description: "Balón oficial FIFA para partidos profesionales",
        inStock: true,
        isNew: false,
        discount: 29
    },
    {
        id: 6,
        name: "Auriculares Inalámbricos",
        category: "electronics",
        price: 159.99,
        originalPrice: 199.99,
        rating: 4.4,
        reviews: 312,
        image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop",
        description: "Auriculares con cancelación de ruido activa",
        inStock: false,
        isNew: false,
        discount: 20
    },
    {
        id: 7,
        name: "Vestido Elegante",
        category: "clothing",
        price: 89.99,
        originalPrice: 129.99,
        rating: 4.3,
        reviews: 67,
        image: "https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=300&h=300&fit=crop",
        description: "Vestido elegante para ocasiones especiales",
        inStock: true,
        isNew: true,
        discount: 31
    },
    {
        id: 8,
        name: "Mesa de Comedor",
        category: "home",
        price: 399.99,
        originalPrice: 499.99,
        rating: 4.8,
        reviews: 78,
        image: "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=300&h=300&fit=crop",
        description: "Mesa de comedor de madera maciza para 6 personas",
        inStock: true,
        isNew: false,
        discount: 20
    },
    {
        id: 9,
        name: "Bicicleta Montaña",
        category: "sports",
        price: 699.99,
        originalPrice: 899.99,
        rating: 4.7,
        reviews: 94,
        image: "https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=300&h=300&fit=crop",
        description: "Bicicleta de montaña con suspensión completa",
        inStock: true,
        isNew: true,
        discount: 22
    },
    {
        id: 10,
        name: "Tablet Pro 12",
        category: "electronics",
        price: 549.99,
        originalPrice: 699.99,
        rating: 4.6,
        reviews: 189,
        image: "https://images.unsplash.com/photo-1561154464-82e9adf32764?w=300&h=300&fit=crop",
        description: "Tablet profesional con pantalla de 12 pulgadas",
        inStock: true,
        isNew: true,
        discount: 21
    },
    {
        id: 11,
        name: "Zapatillas Running",
        category: "sports",
        price: 129.99,
        originalPrice: 159.99,
        rating: 4.4,
        reviews: 203,
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&h=300&fit=crop",
        description: "Zapatillas de running con tecnología de amortiguación",
        inStock: true,
        isNew: false,
        discount: 19
    },
    {
        id: 12,
        name: "Chaqueta Invierno",
        category: "clothing",
        price: 179.99,
        originalPrice: 229.99,
        rating: 4.7,
        reviews: 76,
        image: "https://images.unsplash.com/photo-1551028719-00167b16eac5?w=300&h=300&fit=crop",
        description: "Chaqueta de invierno impermeable y cálida",
        inStock: true,
        isNew: true,
        discount: 22
    },
    // NUEVOS PRODUCTOS
    {
        id: 13,
        name: "Smartwatch Fitness Pro",
        category: "electronics",
        price: 249.99,
        originalPrice: 299.99,
        rating: 4.6,
        reviews: 142,
        image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=300&h=300&fit=crop",
        description: "Reloj inteligente con monitor cardíaco y GPS integrado",
        inStock: true,
        isNew: true,
        discount: 17
    },
    {
        id: 14,
        name: "Jeans Premium Hombre",
        category: "clothing",
        price: 79.99,
        originalPrice: 99.99,
        rating: 4.4,
        reviews: 98,
        image: "https://images.unsplash.com/photo-1542272604-787c3835535d?w=300&h=300&fit=crop",
        description: "Jeans de mezclilla premium con corte slim fit",
        inStock: true,
        isNew: false,
        discount: 20
    },
    {
        id: 15,
        name: "Lámpara LED Moderna",
        category: "home",
        price: 149.99,
        originalPrice: 189.99,
        rating: 4.5,
        reviews: 67,
        image: "https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=300&h=300&fit=crop",
        description: "Lámpara de pie moderna con control remoto y regulador",
        inStock: true,
        isNew: true,
        discount: 21
    },
    {
        id: 16,
        name: "Raqueta Tenis Profesional",
        category: "sports",
        price: 199.99,
        originalPrice: 249.99,
        rating: 4.8,
        reviews: 85,
        image: "https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=300&h=300&fit=crop",
        description: "Raqueta de tenis profesional con marco de grafito",
        inStock: true,
        isNew: false,
        discount: 20
    },
    {
        id: 17,
        name: "Cámara DSLR 4K",
        category: "electronics",
        price: 899.99,
        originalPrice: 1199.99,
        rating: 4.9,
        reviews: 234,
        image: "https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=300&h=300&fit=crop",
        description: "Cámara réflex digital con grabación 4K y lente 18-55mm",
        inStock: true,
        isNew: true,
        discount: 25
    },
    {
        id: 18,
        name: "Monitor UltraWide 34''",
        category: "electronics",
        price: 499.99,
        originalPrice: 599.99,
        rating: 4.7,
        reviews: 120,
        image: "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300&h=300&fit=crop",
        description: "Monitor UltraWide de 34 pulgadas con resolución QHD y tecnología IPS.",
        inStock: true,
        isNew: false,
        discount: 17
    },
    {
        id: 19,
        name: "Set de Ollas Antiadherentes",
        category: "home",
        price: 89.99,
        originalPrice: 129.99,
        rating: 4.5,
        reviews: 85,
        image: "https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=300&h=300&fit=crop&auto=format",
        description: "Set de 5 ollas antiadherentes para cocina saludable y fácil limpieza.",
        inStock: true,
        isNew: true,
        discount: 31,
        gallery_images: [
            "https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=300&h=300&fit=crop&auto=format",
            "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300&h=300&fit=crop&auto=format",
            "https://images.unsplash.com/photo-1519864600265-abb23847ef2c?w=300&h=300&fit=crop&auto=format"
        ],
        long_description: "Set completo de ollas antiadherentes, ideales para cocinar sin que los alimentos se peguen y facilitar la limpieza."
    },
    {
        id: 20,
        name: "Patines en Línea Pro",
        category: "sports",
        price: 159.99,
        originalPrice: 199.99,
        rating: 4.8,
        reviews: 60,
        image: "https://images.unsplash.com/photo-1510557880182-3d4d3c1b9021?w=300&h=300&fit=crop&auto=format",
        description: "Patines en línea profesionales con rodamientos ABEC-9 y bota reforzada.",
        inStock: true,
        isNew: true,
        discount: 20,
        gallery_images: [
            "https://images.unsplash.com/photo-1510557880182-3d4d3c1b9021?w=300&h=300&fit=crop&auto=format",
            "https://images.unsplash.com/photo-1512499617640-c2f999098c01?w=300&h=300&fit=crop&auto=format",
            "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop&auto=format"
        ],
        long_description: "Patines en línea de alto rendimiento, perfectos para entrenamiento y competencias, con excelente soporte y durabilidad."
    }
];

// --- RENDERIZA PRODUCTOS DE EJEMPLO SI EL GRID ESTÁ VACÍO ---
function renderExampleProducts() {
    const grid = document.getElementById('productsGrid');
    if (grid && grid.children.length === 0) {
        grid.innerHTML = products.map(product => `
            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col group relative">
                <div class="relative">
                    <img src="${product.image}" alt="${product.name}" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                    <button 
                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white font-semibold text-lg view-details-btn"
                        data-id="${product.id}">
                        Ver detalle
                    </button>
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <h2 class="text-lg font-semibold mb-1">${product.name}</h2>
                    <p class="text-gray-600 mb-2">$${product.price.toFixed(2)}</p>
                    <button class="mt-auto bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded add-to-cart-btn">
                        Agregar al carrito
                    </button>
                </div>
            </div>
        `).join('');
    }
}

// --- MODAL DETALLE ---
function showProductModal(product) {
    // Obtén referencias a los elementos del modal
    const galleryDiv = document.getElementById('modalProductGallery');
    const modalMainImg = document.getElementById('modalProductImage');
    const modalName = document.getElementById('modalProductName');
    const modalPrice = document.getElementById('modalProductPrice');
    const modalDesc = document.getElementById('modalProductDescription');
    const ul = document.getElementById('modalProductFeatures');
    const specsDiv = document.getElementById('modalProductSpecs');

    // Si alguno de los elementos no existe, muestra en consola cuáles faltan
    if (!galleryDiv || !modalMainImg || !modalName || !modalPrice || !modalDesc || !ul || !specsDiv) {
        console.error('Faltan elementos del modal:', {
            galleryDiv, modalMainImg, modalName, modalPrice, modalDesc, ul, specsDiv
        });
        alert('Error: Faltan elementos del modal en el HTML. Verifica los IDs en tu archivo Blade.');
        return;
    }

    // Galería de imágenes
    galleryDiv.innerHTML = '';
    let images = [];
    // Siempre usa gallery_images si existe y tiene imágenes, si no, usa la principal
    if (Array.isArray(product.gallery_images) && product.gallery_images.length > 0) {
        images = product.gallery_images;
    } else if (product.gallery && typeof product.gallery === 'string') {
        images = product.gallery.split(',');
    } else if (product.image) {
        images = [product.image];
    }

    // Mostrar la primera imagen como principal y el resto como miniaturas
    if (images.length > 0) {
        modalMainImg.src = images[0];
        images.forEach((img, idx) => {
            const imgEl = document.createElement('img');
            imgEl.src = img;
            imgEl.className = 'w-24 h-24 object-cover rounded cursor-pointer border-2 border-transparent hover:border-primary';
            imgEl.onclick = () => {
                modalMainImg.src = img;
            };
            galleryDiv.appendChild(imgEl);
        });
    } else {
        modalMainImg.src = product.image || '';
    }

    modalName.textContent = product.name;
    modalPrice.textContent = `$${product.price.toFixed(2)}`;
    modalDesc.textContent = product.long_description || product.description;

    ul.innerHTML = "";
    if (product.features && product.features.length) {
        product.features.forEach(f => {
            const li = document.createElement('li');
            li.textContent = f;
            ul.appendChild(li);
        });
    }

    specsDiv.innerHTML = "";
    if (product.specs) {
        for (let [key, val] of Object.entries(product.specs)) {
            const row = document.createElement('div');
            row.innerHTML = `<b>${key}:</b> <span>${val}</span>`;
            specsDiv.appendChild(row);
        }
    }

    document.getElementById('productModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideProductModal() {
    document.getElementById('productModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// --- CARRITO DE COMPRAS DEMO ---
let cart = [];

// Función para agregar producto al carrito
function addToCart(product) {
    // Busca si ya está en el carrito
    const existing = cart.find(item => item.id === product.id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ ...product, qty: 1 });
    }
    // Puedes mostrar un mensaje o actualizar un contador visual aquí
    alert(`"${product.name}" agregado al carrito`);
    // Si tienes un contador en el header, actualízalo aquí
    updateCartCounter();
}

// Actualiza el contador visual del carrito (si tienes un span con id cartCount)
function updateCartCounter() {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        const total = cart.reduce((sum, item) => sum + item.qty, 0);
        cartCount.textContent = total;
    }
}

// --- EVENTOS PRINCIPALES ---
document.addEventListener('DOMContentLoaded', () => {
    // Oculta el spinner si existe
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.style.display = 'none';

    // Renderiza productos de ejemplo si el grid está vacío
    renderExampleProducts();

    // Actualiza el contador de productos
    const grid = document.getElementById('productsGrid');
    const count = grid ? grid.children.length : 0;
    const productCount = document.getElementById('productCount');
    if (productCount) productCount.textContent = `Mostrando ${count} productos`;

    // Delegación de eventos: botón "ver detalle"
    document.getElementById('productsGrid').addEventListener('click', function(e) {
        if (e.target.classList.contains('view-details-btn')) {
            const id = Number(e.target.dataset.id);
            let product = products.find(p => p.id === id);

            // Si no está en los de ejemplo, toma los datos del botón (productos reales)
            if (!product) {
                product = {
                    id: id,
                    name: e.target.dataset.name,
                    price: Number(e.target.dataset.price),
                    image: e.target.dataset.image,
                    description: e.target.dataset.description,
                    // features y specs pueden ser agregados si los tienes en tus productos reales
                };
            }

            if (product) showProductModal(product);
        }
        // Evento para agregar al carrito (puedes personalizar la acción)
        if (e.target.classList.contains('add-to-cart-btn')) {
            const card = e.target.closest('.group');
            const id = Number(card.querySelector('.view-details-btn').dataset.id);
            let product = products.find(p => p.id === id);

            // Si no está en los de ejemplo, toma los datos del botón (productos reales)
            if (!product) {
                product = {
                    id: id,
                    name: card.querySelector('h2').textContent,
                    price: Number(card.querySelector('p').textContent.replace(/[^0-9.]/g, '')),
                    image: card.querySelector('img').src,
                    description: '', // Puedes mejorar esto si tienes más datos
                };
            }

            addToCart(product);
        }
    });

    // Cerrar modal
    document.getElementById('closeProductModal').addEventListener('click', hideProductModal);
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) hideProductModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") hideProductModal();
    });
});

/*
INSTRUCCIONES PARA AGREGAR FOTOS MANUALMENTE:

- Para la imagen principal de cada producto, usa la propiedad `image`:
  image: "URL_DE_LA_IMAGEN_PRINCIPAL"

- Para las imágenes que aparecen en el modal de detalles, usa la propiedad `gallery_images` (array de URLs):
  gallery_images: [
      "URL_IMAGEN_1",
      "URL_IMAGEN_2",
      "URL_IMAGEN_3"
  ]

- Si no agregas `gallery_images`, solo se mostrará la imagen principal.
- Puedes usar cualquier URL pública de imagen (Unsplash, Pexels, tu propio servidor, etc).

EJEMPLO:
{
    id: 21,
    name: "Producto de ejemplo",
    image: "https://ejemplo.com/imagen-principal.jpg",
    gallery_images: [
        "https://ejemplo.com/imagen1.jpg",
        "https://ejemplo.com/imagen2.jpg",
        "https://ejemplo.com/imagen3.jpg"
    ],
    // ...otros campos...
}
*/

// --- CORRECCIÓN DE IMÁGENES QUE NO SE VEN ---
// Verifica que las URLs sean accesibles y usen HTTPS, y que no tengan restricciones de hotlinking.
// Si usas imágenes de Bing o MercadoLibre, pueden tener restricciones y no mostrarse en todos los navegadores.
// Usa imágenes de Unsplash, Pexels, Pixabay, o sube tus propias imágenes a un servidor sin restricciones.

