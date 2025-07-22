<div>
    {{-- Success is as dangerous as failure. --}}

    @if ($bookCopies->total() === 0)
        <div class="mb-4 px-4 py-3 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
            No se encontraron ejemplares para este libro.
        </div>
    @else
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
            Se encontraron {{ $bookCopies->total() }} ejemplares.
        </div>
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
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
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

                    <!-- Botón Añadir libro -->
                    <button wire:click="openBookModal('create', null)"
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
                                        <span>Código de barras</span>
                                        <button wire:click="sortBy('codigo_barras')" class="ml-2">
                                            <i
                                                class="fa-solid fa{{ $sortField === 'codigo_barras' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                        </button>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                    <div class="flex items-center justify-between">
                                        <span>Ubicación estante</span>
                                        <button wire:click="sortBy('ubicacion_estante')" class="ml-2">
                                            <i
                                                class="fa-solid fa{{ $sortField === 'ubicacion_estante' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                        </button>
                                    </div>
                                </th>



                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                    <div class="flex items-center justify-between">
                                        <span>Disponible</span>
                                        <button wire:click="sortBy('status')" class="ml-2">
                                            <i
                                                class="fa-solid fa{{ $sortField === 'status' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                        </button>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                    {{-- <div class="flex items-center justify-between">
                                        <span>Días máximo de prestamo</span>
                                        <button wire:click="sortBy('dias_maximos_prestamo')" class="ml-2">
                                            <i
                                                class="fa-solid fa{{ $sortField === 'dias_maximos_prestamo' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                        </button>
                                    </div> --}}
                                </th>




                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach ($bookCopies as $ejemplar)
                                <!-- Ejemplo con datos estáticos -->

                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                    wire:key="ejemplar-{{ $ejemplar->id_ejemplar }}">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        <div class="flex justify-center bg-white rounded-md p-1">
                                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($ejemplar->codigo_barras, 'C128') }}"
                                                alt="Código de barras">
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $ejemplar->ubicacion_estante }}</td>


                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $ejemplar->status == 'disponible' ? 'Sí' : 'No' }}</td>

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{-- {{ $ejemplar->dias_maximos_prestamo }} --}}</td>
                                    </td>


                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            {{--  Botón prestar libro --}}
                                            <button wire:click="{{-- openBookModal('prestamo',{{ $ejemplar->id_ejemplar }}) --}}"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400"
                                                title="Prestar libro">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4m0 0v4m0-4h4m-4 0H8m1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>

                <!-- Paginación -->
                <div class="bg-white px-6 py-3 border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div class="justify-between items-center">
                        {{ $bookCopies->links() }}
                    </div>

                </div>
            </div>

        </div>
    @endif







</div>
