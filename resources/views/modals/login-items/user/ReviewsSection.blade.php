@extends('layouts.app')

@section('title', 'Mis Reseñas')

@vite(['resources/css/profile.css', 'resources/css/reviews.css'])

@section('content')

<style>
    :root {
        --primary: #FDC040;
        --secondary: #FFD166;
        --accent: #E8E8E8;
        --text-dark: #1F2937;
        --text-medium: #6B7280;
        --text-light: #9CA3AF;
        --green: #10B981;
        --green-light: #D1FAE5;
        --error: #EF4444;
        --bg-gray-50: #F9FAFB;
        --bg-gray-100: #F3F4F6;
        --star-gold: #FBBF24;
    }

    /* Review Cards */
    .review-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .review-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Product Info */
    .product-info {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--bg-gray-100);
    }

    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.5rem;
        flex-shrink: 0;
    }

    .product-details {
        flex: 1;
    }

    .product-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .product-category {
        color: var(--text-medium);
        font-size: 0.875rem;
    }

    /* Star Rating */
    .star-rating {
        display: flex;
        gap: 0.25rem;
        margin: 0.5rem 0;
    }

    .star {
        width: 1.25rem;
        height: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .star.filled {
        color: var(--star-gold);
        fill: var(--star-gold);
    }

    .star.empty {
        color: var(--bg-gray-100);
        fill: var(--bg-gray-100);
    }

    .star:hover {
        transform: scale(1.2);
    }

    /* Review Content */
    .review-content {
        margin-top: 1rem;
    }

    .review-text {
        color: var(--text-dark);
        line-height: 1.6;
        margin-bottom: 0.75rem;
    }

    .review-date {
        color: var(--text-light);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Review Stats */
    .review-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        display: block;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: var(--text-medium);
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Filters */
    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        background: white;
        border: 2px solid var(--bg-gray-100);
        color: var(--text-medium);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-chip:hover {
        border-color: var(--primary);
        color: var(--text-dark);
    }

    .filter-chip.active {
        background: var(--primary);
        border-color: var(--primary);
        color: var(--text-dark);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary);
        color: var(--text-dark);
        border: none;
    }

    .btn-primary:hover {
        background: var(--secondary);
        transform: translateY(-1px);
    }

    .btn-outline {
        background: transparent;
        color: var(--text-medium);
        border: 2px solid var(--bg-gray-100);
    }

    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--text-dark);
    }

    .btn-danger {
        background: transparent;
        color: var(--error);
        border: 2px solid var(--error);
    }

    .btn-danger:hover {
        background: var(--error);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .empty-icon {
        width: 5rem;
        height: 5rem;
        margin: 0 auto 1.5rem;
        color: var(--text-light);
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 1rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.show {
        opacity: 1;
        pointer-events: auto;
    }

    .modal-content {
        background: white;
        border-radius: 1rem;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }

    .modal-overlay.show .modal-content {
        transform: scale(1);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--bg-gray-100);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--bg-gray-100);
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-dark);
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid var(--bg-gray-100);
        border-radius: 0.5rem;
        font-family: inherit;
        resize: vertical;
        transition: all 0.3s ease;
    }

    .form-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(253, 192, 64, 0.1);
    }

    /* Alert */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideDown 0.3s ease-out;
    }

    .alert-success {
        background: var(--green-light);
        color: var(--green);
        border: 1px solid var(--green);
    }

    .alert-error {
        background: #FEE2E2;
        color: var(--error);
        border: 1px solid var(--error);
    }

    /* Animations */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .review-stats {
            grid-template-columns: 1fr;
        }

        .product-info {
            flex-direction: column;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Utility Classes */
    .container {
        width: 100%;
        max-width: 1280px;
        margin-left: auto;
        margin-right: auto;
    }

    .flex {
        display: flex;
    }

    .flex-1 {
        flex: 1;
    }

    .gap-8 {
        gap: 2rem;
    }

    .mb-8 {
        margin-bottom: 2rem;
    }

    .mb-6 {
        margin-bottom: 1.5rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .py-8 {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    .text-3xl {
        font-size: 1.875rem;
    }

    .font-bold {
        font-weight: 700;
    }

    .hidden {
        display: none;
    }
</style>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <div class="flex gap-8">
        <!-- Sidebar -->
        @include('modals.login-items.user.sideBar')

        <!-- Main Content Area -->
        <main class="flex-1">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold" style="color: var(--text-dark); margin-bottom: 0.5rem;">Mis Reseñas</h1>
                <p style="color: var(--text-medium);">Gestiona tus opiniones sobre los productos</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats -->
            <div class="review-stats">
                <div class="stat-card">
                    <span class="stat-value">{{ $totalReviews ?? 0 }}</span>
                    <span class="stat-label">Total de Reseñas</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">{{ number_format($averageRating ?? 0, 1) }}</span>
                    <span class="stat-label">Calificación Promedio</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">{{ $pendingReviews ?? 0 }}</span>
                    <span class="stat-label">Pendientes por Escribir</span>
                </div>
            </div>

            <!-- Filters -->
            <div style="background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); margin-bottom: 1.5rem;">
                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                    <button class="filter-chip active" data-filter="all">
                        Todas ({{ $totalReviews ?? 0 }})
                    </button>
                    <button class="filter-chip" data-filter="5">
                        ⭐⭐⭐⭐⭐ ({{ $fiveStarReviews ?? 0 }})
                    </button>
                    <button class="filter-chip" data-filter="4">
                        ⭐⭐⭐⭐ ({{ $fourStarReviews ?? 0 }})
                    </button>
                    <button class="filter-chip" data-filter="3">
                        ⭐⭐⭐ ({{ $threeStarReviews ?? 0 }})
                    </button>
                    <button class="filter-chip" data-filter="2">
                        ⭐⭐ ({{ $twoStarReviews ?? 0 }})
                    </button>
                    <button class="filter-chip" data-filter="1">
                        ⭐ ({{ $oneStarReviews ?? 0 }})
                    </button>
                </div>
            </div>

            <!-- Reviews List -->
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($reviews ?? [] as $review)
                    <div class="review-card" data-rating="{{ $review->rating }}">
                        <!-- Product Info -->
                        <div class="product-info">
                            <img src="{{ $review->product->image_url ?? '/images/placeholder.jpg' }}" 
                                 alt="{{ $review->product->name }}" 
                                 class="product-image">
                            <div class="product-details">
                                <h3 class="product-name">{{ $review->product->name }}</h3>
                                <p class="product-category">{{ $review->product->category->name ?? 'Sin categoría' }}</p>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="star {{ $i <= $review->rating ? 'filled' : 'empty' }}" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>

                        <!-- Review Content -->
                        <div class="review-content">
                            <p class="review-text">{{ $review->comment }}</p>
                            <div class="review-date">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Publicada el {{ $review->created_at->format('d/m/Y') }}
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button onclick="openEditModal({{ $review->id }}, {{ $review->rating }}, '{{ addslashes($review->comment) }}')" 
                                    class="btn btn-outline">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </button>
                            <button onclick="confirmDelete({{ $review->id }})" 
                                    class="btn btn-danger">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.5rem;">
                            No has escrito ninguna reseña
                        </h3>
                        <p style="color: var(--text-medium); margin-bottom: 1.5rem;">
                            Comparte tu opinión sobre los productos que has comprado
                        </p>
                        <a href="{{ route('productos') }}" class="btn btn-primary">
                            Explorar Productos
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(isset($reviews) && $reviews->hasPages())
                <div style="margin-top: 2rem;">
                    {{ $reviews->links() }}
                </div>
            @endif
        </main>
    </div>
</div>

<!-- Edit Review Modal -->
<div id="editReviewModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-dark);">Editar Reseña</h3>
            <button onclick="closeEditModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-medium);">
                ×
            </button>
        </div>
        <form id="editReviewForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Calificación</label>
                    <div class="star-rating" id="editStarRating">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="star empty" 
                                 data-rating="{{ $i }}"
                                 onclick="setRating({{ $i }})"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="editRatingInput" required>
                </div>

                <div class="form-group">
                    <label for="editComment" class="form-label">Tu opinión</label>
                    <textarea id="editComment" 
                              name="comment" 
                              rows="5" 
                              class="form-textarea"
                              placeholder="Comparte tu experiencia con este producto..."
                              required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn btn-outline">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-dark);">Confirmar Eliminación</h3>
        </div>
        <div class="modal-body">
            <p style="color: var(--text-medium);">¿Estás seguro de que deseas eliminar esta reseña? Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-footer">
            <button onclick="closeDeleteModal()" class="btn btn-outline">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@vite('resources/js/reviews.js')

<script>
// Edit Modal
function openEditModal(reviewId, rating, comment) {
    const modal = document.getElementById('editReviewModal');
    const form = document.getElementById('editReviewForm');
    const commentTextarea = document.getElementById('editComment');
    
    form.action = `/reviews/${reviewId}`;
    commentTextarea.value = comment;
    setRating(rating);
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    const modal = document.getElementById('editReviewModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

// Delete Modal
function confirmDelete(reviewId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    
    form.action = `/reviews/${reviewId}`;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

// Star Rating
function setRating(rating) {
    document.getElementById('editRatingInput').value = rating;
    const stars = document.querySelectorAll('#editStarRating .star');
    
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('empty');
            star.classList.add('filled');
        } else {
            star.classList.remove('filled');
            star.classList.add('empty');
        }
    });
}

// Filter Reviews
document.querySelectorAll('.filter-chip').forEach(chip => {
    chip.addEventListener('click', function() {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const reviews = document.querySelectorAll('.review-card');
        
        reviews.forEach(review => {
            if (filter === 'all' || review.dataset.rating === filter) {
                review.style.display = 'block';
            } else {
                review.style.display = 'none';
            }
        });
    });
});

// Close modals on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeDeleteModal();
    }
});

// Close modals on outside click
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
            closeDeleteModal();
        }
    });
});

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'slideDown 0.3s ease-out reverse';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>