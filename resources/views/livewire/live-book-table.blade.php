<div>
    {{-- Success is as dangerous as failure. --}}
    <div class="mx-auto px-16 py-8">
        <!-- Encabezado con buscador y botón -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Gestión de libros</h1>

            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                <!-- Selector de resultados por página -->
                <div class="flex items-center gap-2">
                    <label for="perPage" class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap ">
                        Resultados por página:
                    </label>
                    <select id="perPage" wire:model.live="perPage"
                        class="mr-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <!-- Barra de búsqueda -->
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live="search" placeholder="Buscar libros..."
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <!-- Botón Resetear Filtros -->
                <div class="relative group flex items-center">
                    <button wire:click="clear" aria-label="Resetear filtros"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <i
                            class="fa fa-eraser fa-lg text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-200"></i>
                    </button>
                    <span
                        class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 dark:bg-gray-700 text-white text-xs py-1 px-2 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                        Resetear filtros
                    </span>
                </div>

                <!-- Botón Gestión de ejemplares -->
                <a href="{{ route('administracion.ejemplares') }}" wire:navigate
                    class="bg-green-600
    hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg inline-flex items-center gap-2 transition-colors
    duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
    dark:focus:ring-offset-gray-800 whitespace-nowrap">
                    <i class="fa-solid fa-book-open"></i>
                    Gestión de ejemplares
                </a>

                <!-- Botón Añadir libro -->
                <button wire:click="openModal('create', null)"
                    class="bg-blue-600
                    hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg inline-flex items-center gap-2 transition-colors
                    duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                    dark:focus:ring-offset-gray-800 whitespace-nowrap">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 1v16M1 9h16" />
                    </svg>
                    Nuevo libro
                </button>
            </div>
        </div>

        <!-- Tabla de libros -->
        <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr class="whitespace-nowrap">
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                <div class="flex items-center justify-between">
                                    <span>Portada del libro</span>
                                    <button wire:click="sortBy('portada')" class="ml-2">
                                        <i
                                            class="fa-solid fa{{ $sortField === 'portada' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                    </button>
                                </div>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                <div class="flex items-center justify-between">
                                    <span>Titulo del libro</span>
                                    <button wire:click="sortBy('titulo')" class="ml-2">
                                        <i
                                            class="fa-solid fa{{ $sortField === 'titulo' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                    </button>
                                </div>
                            </th>



                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                <div class="flex items-center justify-between">
                                    <span>Código ISBN</span>
                                    <button wire:click="sortBy('isbn')" class="ml-2">
                                        <i
                                            class="fa-solid fa{{ $sortField === 'isbn' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                    </button>
                                </div>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                <div class="flex items-center justify-between">
                                    <span>Autor</span>
                                    <button wire:click="sortBy('id_autor')" class="ml-2">
                                        <i
                                            class="fa-solid fa{{ $sortField === 'id_autor' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                    </button>
                                </div>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                <div class="flex items-center justify-between">
                                    <span>Editorial</span>
                                    <button wire:click="sortBy('id_editorial')" class="ml-2">
                                        <i
                                            class="fa-solid fa{{ $sortField === 'id_editorial' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                    </button>
                                </div>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                <div class="flex items-center justify-between">
                                    <span>Año de publicación</span>
                                    <button wire:click="sortBy('anio_publicacion')" class="ml-2">
                                        <i
                                            class="fa-solid fa{{ $sortField === 'anio_publicacion' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                    </button>
                                </div>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                <div class="flex items-center justify-between">
                                    <span>Categoría</span>
                                    <button wire:click="sortBy('categoria')" class="ml-2">
                                        <i
                                            class="fa-solid fa{{ $sortField === 'categoria' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                    </button>
                                </div>
                            </th>

                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @foreach ($books as $book)
                            <!-- Ejemplo con datos estáticos -->

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700" wire:key="book-{{ $book->id_libro }}">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    <div class="flex justify-center">
                                        <img src="{{ asset('storage/' . $book->portada) }}"
                                            alt="Portada de {{ $book->titulo }}"
                                            class="h-16 w-12 object-cover rounded shadow" />
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $book->titulo }}</td>


                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $book->isbn }}</td>

                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $book->id_autor ? $book->autor->nombre . ' ' . $book->autor->apellido_paterno . ' ' . $book->autor->apellido_materno : 'Sin autor' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $book->id_editorial ? $book->editorial->nombre_editorial : 'Sin editorial' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $book->anio_publicacion }}</td>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $book->id_categoria ? $book->categoria->nombre_categoria : 'Sin categoría' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2">
                                        {{-- Botón editar --}}

                                        <a wire:click="openModal('edit',{{ $book->id_libro }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if (auth()->user()->isAdmin())
                                            {{-- Botón eliminar --}}
                                            <button class="text-red-600 hover:text-red-900 dark:text-red-400"
                                                title="Eliminar"
                                                wire:click="showDeleteConfirmation({{ $book->id_libro }}, '{{ $book->titulo }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                        {{-- Botón Sinopsis --}}
                                        <button class="text-red-600 hover:text-red-900 dark:text-red-400"
                                            title="Ver sinopsis"
                                            wire:click="openBookDetailsModal('sinopsis',{{ $book->id_libro }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm7 0c-1.657 3-5.03 7-10 7S2.657 15 1 12c1.657-3 5.03-7 10-7s8.343 4 10 7zm-4.5 6.5l2.5 2.5m0 0l-2.5-2.5m2.5 2.5H19" />
                                            </svg>
                                        </button>
                                        {{-- Botón ver ejemplares --}}
                                        <a href="{{ route('administracion.ejemplares', ['search' => $book->isbn]) }}"
                                            class="text-green-600 hover:text-green-900 dark:text-green-400"
                                            title="Ver ejemplares">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a8.002 8.002 0 00-6.928 4.072m13.856 0A8.002 8.002 0 0012 4.354m0 0a8.002 8.002 0 016.928 4.072m-13.856 0A8.002 8.002 0 0012 19.646m0-15.292a8.002 8.002 0 00-6.928 4.072m13.856 0A8.002 8.002 0 0012 19.646m0-15.292a8.002 8.002 0 016.928 4.072m-13.856 0A8.002 8.002 0 0012 19.646m0-15.292a8.002 8.002 0 00-6.928 4.072m13.856 0A8.002 8.002 0 0012 19.646m0-15.292a8.002 8.002 0 016.928 4.072m-13.856 0A8.002 8.002 0 0012 19.646m0-15.292a8.002 8.002 0 00-6.928 4.072m13.856 0A8.002 8.002 0 0012 19.646" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <!-- Más filas... -->
                    </tbody>
                </table>

            </div>

            <!-- Paginación (opcional) -->
            <div class="bg-white px-6 py-3 border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="justify-between items-center">
                    {{ $books->links() }}
                </div>

            </div>
        </div>

    </div>






</div>
