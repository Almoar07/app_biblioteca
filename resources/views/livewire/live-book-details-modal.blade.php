<div x-data="{ open: @entangle('showModal') }" x-cloak>
    <!-- Fondo oscuro -->
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity" aria-hidden="true">
    </div>

    <!-- Contenedor principal del modal -->
    <div x-show="open" class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300">
                <!-- Contenedor principal del modal con grid de 3 columnas -->
                <div class="bg-gray-100 dark:bg-gray-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-lg"
                    style="max-width: 80vw; max-height: 80vh; overflow-y: auto; overflow-x: auto; width: 80vw;">
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Columna 1: vacía (reservada para ti) -->
                        <div>
                            @if ($libro)
                                <img src="{{ asset('storage/' . $libro->portada) }}"
                                    alt="Portada de {{ $libro->titulo }}"
                                    class="w-full h-full object-cover rounded shadow"
                                    style="max-height:100%; max-width:100%;" />
                            @endif
                            {{-- Columna vacía para uso futuro --}}


                        </div>
                        <!-- Columnas 2 y 3 unidas -->
                        <div class="col-span-2">
                            <!-- Contenido principal con mejor organización -->
                            @switch($mode)

                                {{-- MODO DETALLES DE LOS EJEMPLARES --}}
                                @case('details')
                                    @if ($libro)
                                        <!-- Botón cerrar -->
                                        <button wire:click="closeModal"
                                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">&times;</button>

                                        <!-- Cabecera -->
                                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">
                                            Ejemplares de: {{ $libro->titulo }}
                                        </h2>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            ISBN: {{ $libro->isbn }} • Autor: {{ $libro->autor->nombre ?? '-' }}
                                            {{ $libro->autor->apellido_paterno ?? '-' }}
                                            {{ $libro->autor->apellido_materno ?? '-' }}
                                        </p>

                                        @livewire('live-book-details-table', ['id_libro' => $libro->id_libro], key($libro->id_libro))

                                        <!-- Acciones del modal -->
                                        <div class="mt-4 text-right">
                                            <button wire:click="clearFilters"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                                                Limpiar filtros
                                            </button>
                                        </div>
                                    @endif
                                @break

                                {{-- MODO SINOPSIS DEL LIBRO --}}
                                @case('sinopsis')
                                    @if ($libro)
                                        <div class="flex items-center justify-center min-h-[60vh] bg-white dark:bg-gray-800 w-full max-w-4xl p-6 rounded-lg shadow-lg relative border-2 border-blue-400 dark:border-blue-600"
                                            style="max-width: 80vw; max-height: 80vh; overflow-y: auto; overflow-x: auto;">
                                            <div class="w-full">
                                                <!-- Cabecera -->
                                                <h2
                                                    class="text-2xl font-extrabold mb-4 text-blue-700 dark:text-blue-300 text-center">
                                                    Sinopsis de: {{ $libro->titulo }}
                                                </h2>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 text-center">
                                                    ISBN: <span class="font-semibold">{{ $libro->isbn }}</span> • Autor: <span
                                                        class="font-semibold">{{ $libro->autor->nombre ?? '-' }}
                                                        {{ $libro->autor->apellido_paterno ?? '-' }}
                                                        {{ $libro->autor->apellido_materno ?? '-' }}</span>
                                                </p>
                                                <!-- Contenido de la sinopsis -->
                                                <div
                                                    class="prose dark:prose-invert bg-blue-50 dark:bg-blue-900/30 p-6 rounded-md shadow-inner text-lg text-gray-800 dark:text-gray-100 max-h-72 overflow-y-auto">
                                                    <p>{{ $libro->sinopsis }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @break

                                @default
                            @endswitch
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                    style="max-width: 80vw;">
                    <button type="button" wire:click={{ $mode === 'create' ? 'createCategory' : 'updateCategory' }}
                        class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                        Guardar
                    </button>
                    <button @click="open = false" type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
