<!-- Sección de Perfil del Emprendedor -->
<div id="perfil" class="section-content">
@vite(['resources/js/entrepreneurProfile.js'])

<!-- Hero Section con Logo -->
<div class="relative bg-gradient-warm h-64 overflow-hidden mb-8">
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="currentColor" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container mx-auto px-4 h-full flex items-end pb-8 relative z-10">
        <div class="flex items-end gap-6 w-full">
            <!-- Logo del Emprendedor -->
            <div class="relative flex-shrink-0">
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-2xl bg-white shadow-primary-lg overflow-hidden border-4 border-white">
                    @if($entrepreneur->logo)
                        <img src="{{ asset('storage/' . $entrepreneur->logo) }}" alt="{{ $entrepreneur->business_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-accent flex items-center justify-center">
                            <svg class="w-16 h-16 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                
                @if($entrepreneur->verified)
                    <div class="absolute -bottom-2 -right-2 bg-green text-white rounded-full p-2 shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @endif
            </div>
            
            <!-- Info Básica -->
            <div class="flex-1 text-white pb-4 min-w-0">
                <h1 class="text-3xl md:text-4xl font-bold mb-2 truncate">{{ $entrepreneur->business_name }}</h1>
                <div class="flex items-center gap-4 flex-wrap">
                    <!-- Rating -->
                    <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-lg">
                        <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="font-semibold">{{ number_format($entrepreneur->average_rating, 1) }}</span>
                    </div>
                    
                    <!-- Fecha de registro -->
                    <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm">Desde {{ \Carbon\Carbon::parse($entrepreneur->registered_at)->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 pb-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Columna Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Descripción -->
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-dark flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Sobre Nosotros
                    </h2>
                    <button onclick="editDescription()" class="px-4 py-2 bg-accent hover:bg-primary text-dark rounded-lg transition-colors duration-300 text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </button>
                </div>
                
                @if($entrepreneur->description)
                    <p class="text-medium leading-relaxed">{{ $entrepreneur->description }}</p>
                @else
                    <p class="text-light italic">Este emprendedor aún no ha agregado una descripción.</p>
                @endif
            </div>

            <!-- Ubicaciones -->
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-dark flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Nuestras Ubicaciones
                    </h2>
                    <button onclick="addAddress()" class="px-4 py-2 bg-primary hover:bg-secondary text-dark rounded-lg transition-colors duration-300 text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Agregar
                    </button>
                </div>

                @forelse($entrepreneur->addresses as $address)
                    <div class="mb-4 last:mb-0 p-4 bg-light rounded-xl hover:shadow-primary transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                @if($address->is_main)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary text-dark mb-2">
                                        Principal
                                    </span>
                                @endif
                                
                                <div class="flex items-start gap-2 mb-2">
                                    <svg class="w-5 h-5 text-orange mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-dark break-words">{{ $address->address }}</p>
                                        <p class="text-sm text-medium">{{ $address->city }}, {{ $address->department }}</p>
                                        @if($address->postal_code)
                                            <p class="text-sm text-light">CP: {{ $address->postal_code }}</p>
                                        @endif
                                        @if($address->reference)
                                            <p class="text-sm text-medium mt-1">
                                                <span class="font-medium">Referencia:</span> {{ $address->reference }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                                @if($address->latitude && $address->longitude)
                                    <a href="https://www.google.com/maps?q={{ $address->latitude }},{{ $address->longitude }}" 
                                       target="_blank"
                                       class="p-2 bg-accent hover:bg-primary text-dark rounded-lg transition-colors duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                    </a>
                                @endif
                                <button onclick="editAddress({{ $address->id }})" class="p-2 bg-accent hover:bg-primary text-dark rounded-lg transition-colors duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-medium">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p>No hay ubicaciones registradas</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Información de Contacto -->
            <div class="bg-white rounded-2xl shadow-soft p-6 lg:sticky lg:top-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-dark flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contacto
                    </h3>
                    <button onclick="editContact()" class="p-2 hover:bg-accent rounded-lg transition-colors duration-300">
                        <svg class="w-4 h-4 text-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <!-- Email -->
                    <div class="flex items-center gap-3 p-3 bg-light rounded-lg hover:bg-accent transition-colors duration-300">
                        <div class="flex-shrink-0 w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-light mb-0.5">Email</p>
                            <a href="mailto:{{ $entrepreneur->email }}" class="text-sm font-medium text-dark hover:text-orange truncate block">
                                {{ $entrepreneur->email }}
                            </a>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    @if($entrepreneur->phone)
                        <div class="flex items-center gap-3 p-3 bg-light rounded-lg hover:bg-accent transition-colors duration-300">
                            <div class="flex-shrink-0 w-10 h-10 bg-green rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-light mb-0.5">Teléfono</p>
                                <a href="tel:{{ $entrepreneur->phone }}" class="text-sm font-medium text-dark hover:text-green truncate block">
                                    {{ $entrepreneur->phone }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Logo -->
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-dark flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Logo
                    </h3>
                    <button onclick="editLogo()" class="p-2 hover:bg-accent rounded-lg transition-colors duration-300">
                        <svg class="w-4 h-4 text-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="flex items-center justify-center p-6 bg-light rounded-xl">
                    @if($entrepreneur->logo)
                        <img src="{{ asset('storage/' . $entrepreneur->logo) }}" alt="{{ $entrepreneur->business_name }}" class="max-w-full max-h-32 object-contain">
                    @else
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-gray mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm text-medium">Sin logo</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Estado del Emprendedor -->
            <div class="bg-gradient-sunshine rounded-2xl shadow-soft p-6">
                <h3 class="text-lg font-bold text-dark mb-3">Estado</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-white/60 backdrop-blur-sm rounded-lg">
                        <span class="text-sm text-medium">Estado</span>
                        @if($entrepreneur->active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green text-white">
                                <span class="w-2 h-2 bg-white rounded-full mr-1.5"></span>
                                Activo
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-400 text-white">
                                Inactivo
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-white/60 backdrop-blur-sm rounded-lg">
                        <span class="text-sm text-medium">Verificado</span>
                        @if($entrepreneur->verified)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green text-white">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Verificado
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-light text-orange">
                                Pendiente
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

@vite(['resources/js/entrepreneurProfile.js'])