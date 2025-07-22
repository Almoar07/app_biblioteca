<div>

    {{-- Success is as dangerous as failure. --}}
    <div class="mx-auto px-16 py-8">
        <!-- Encabezado con buscador y botón -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Gestión de ejemplares de libros</h1>

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
                    <input type="text" wire:model.live="search" placeholder="Buscar ejemplares de libros..."
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
                <!-- Botón descargar códigos de barra -->
                <button id="descargarEtiquetas" type="button"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2.5 rounded-lg inline-flex items-center gap-2 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 whitespace-nowrap">
                    <i class="fa-solid fa-download"></i>
                    Descargar códigos de barra
                </button>

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
                    Nuevo ejemplar de libro
                </button>
            </div>
        </div>

        <!-- Tabla de ejemplares de libros -->
        <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr class="whitespace-nowrap">

                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                <div class="flex items-center justify-between">
                                    <span>Titulo del libro</span>
                                    <button wire:click="sortBy('titulo_libro')" class="ml-2">
                                        <i
                                            class="fa-solid fa{{ $sortField === 'titulo_libro' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                    </button>
                                </div>
                            </th>



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
                                    <span>Estado del libro</span>
                                    <button wire:click="sortBy('status')" class="ml-2">
                                        <i
                                            class="fa-solid fa{{ $sortField === 'status' ? $icon : '-circle' }} text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"></i>
                                    </button>
                                </div>
                            </th>



                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                Acciones
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-1/4">
                                <input type="checkbox" id="checkAll"
                                    class="mr-2 rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800">
                                Descargar códigos de barra
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @foreach ($bookCopies as $bookCopy)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                wire:key="bookCopy-{{ $bookCopy->id_ejemplar }}">

                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $bookCopy->titulo_libro }}</td>


                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <div class="bg-white rounded p-1">
                                        <div class="flex flex-col items-center">
                                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($bookCopy->codigo_barras, 'C128') }}"
                                                alt="Código de barras">
                                            <div class="mt-1 text-center text-xd text-gray-900">
                                                {{ $bookCopy->codigo_barras }}</div>
                                        </div>
                                    </div>

                                </td>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ Str::ucfirst($bookCopy->status) }}
                                </td>


                                </td>


                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2 items-center">
                                        {{-- Botón editar --}}
                                        <a wire:click="openModal('edit',{{ $bookCopy->id_ejemplar }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 flex items-center"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                      m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828
                      l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        @if (auth()->user()->isAdmin())
                                            {{-- Botón eliminar --}}
                                            <button
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 flex items-center"
                                                title="Eliminar"
                                                wire:click="showDeleteConfirmation({{ $bookCopy->id_ejemplar }}, '{{ $bookCopy->titulo_libro }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                      01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                      00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif

                                        @switch($bookCopy->status)
                                            @case('prestado')
                                                <button
                                                    wire:click="returnBookCopy('{{ $bookCopy->codigo_barras }}', '{{ $bookCopy->id_ejemplar }}')"
                                                    class="flex items-center justify-center w-32 h-10 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
                                                    title="Devolver libro">
                                                    <i class="fas fa-undo mr-2 text-sm"></i> Devolver
                                                </button>
                                            @break

                                            @case('disponible')
                                                <button
                                                    class="flex items-center justify-center w-32 h-10 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                                                    title="Prestar libro"
                                                    wire:click="loanBookCopy('{{ $bookCopy->codigo_barras }}', '{{ $bookCopy->id_ejemplar }}')">
                                                    <i class="fas fa-hand-holding mr-2 text-sm"></i> Prestar
                                                </button>
                                            @break
                                        @endswitch
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <input type="checkbox" name="ids[]" value="{{ $bookCopy->id_ejemplar }}"
                                        class="checkbox-ejemplar rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800">
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>

            <!-- Paginación (opcional) -->
            <div class="bg-white px-6 py-3 border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="justify-between items-center">
                    {{ $bookCopies->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.getElementById('descargarEtiquetas').addEventListener('click', function() {
            // Tomar todos los checkboxes marcados
            const seleccionados = Array.from(document.querySelectorAll('.checkbox-ejemplar:checked'))
                .map(cb => cb.value);

            if (seleccionados.length === 0) {
                window.dispatchEvent(new CustomEvent("info-alert", {
                    detail: {
                        title: "Selecciona ejemplares",
                        text: "Por favor, selecciona al menos un ejemplar para descargar sus códigos de barra."
                    }
                }));

                return;
            }

            // Construir URL con los IDs seleccionados
            const url = "{{ route('etiquetas.pdf') }}" + "?ids[]=" + seleccionados.join("&ids[]=");

            // Redirigir para descargar el PDF
            window.open(url, '_blank');
        });

        document.getElementById('checkAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.checkbox-ejemplar');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        document.querySelectorAll('.checkbox-ejemplar').forEach(cb => {
            cb.addEventListener('change', function() {
                const all = document.querySelectorAll('.checkbox-ejemplar');
                const checked = document.querySelectorAll('.checkbox-ejemplar:checked');
                document.getElementById('checkAll').checked = all.length === checked.length;
            });
        });
    </script>
@endpush
