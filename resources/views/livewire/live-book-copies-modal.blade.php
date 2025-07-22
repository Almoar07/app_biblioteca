<div x-data="{ open: @entangle('showModal') }" x-cloak>
    @php
        switch ($mode) {
            case 'create':
                $tituloModal = 'Registrar ejemplar de libro';
                $subtituloModal = 'Ingrese los datos del nuevo ejemplar';
                $buttonAction = 'createBookCopy()';
                $elementDisabled = false;
                break;
            case 'edit':
                $tituloModal = 'Editar ejemplar de libro';
                $subtituloModal = 'Complete o modifique la información del ejemplar';
                $buttonAction = 'updateBookCopy()';
                break;
            default:
                $tituloModal = 'Sin título';
                $subtituloModal = 'No hay un modo definido';
                $buttonAction = '';
                break;
        }
    @endphp

    <!-- Overlay de fondo -->
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity" aria-hidden="true">
    </div>

    <!-- Contenedor principal del modal -->
    <div x-show="open" class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Contenido del modal -->
            <div x-show="open" x-transition:enter="ease-out duration-300"
                class="bg-gray-100 dark:bg-gray-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-lg w-full sm:w-1/2">
                <div class="sm:flex sm:items-start">
                    <div class="mt-4 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <!-- Encabezado del modal -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3 flex items-start">
                            <div
                                class="mr-4 flex size-14 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 sm:size-12">
                                <svg class="size-7 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                    {{ $tituloModal }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $subtituloModal }}
                                </p>
                            </div>
                        </div>
                        <form>
                            <!-- Contenedor de las columnas (ahora se ajusta al 50% del modal) -->
                            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <!-- Primera columna -->
                                <div class="space-y-5 col-span-full sm:col-span-1">
                                    <fieldset class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                            Información del Ejemplar</p>
                                        <div>
                                            <x-input-label for="bookISBN" :value="__('ISBN')" />
                                            <div class="flex items-center">
                                                <input wire:model="bookISBN" wire:keydown.enter="searchBookByISBN()"
                                                    id="bookISBN" type="text"
                                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full"
                                                    placeholder="Ingrese el ISBN del libro" />

                                                <button type="button" wire:click="searchBookByISBN()"
                                                    class="inline-flex items-center p-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 h-9 w-9 justify-center"
                                                    title="Buscar libro por ISBN">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <x-text-input wire:model="bookTitle" id="bookTitle" type="text"
                                                class="block w-full mt-2" readonly placeholder="Título del libro" />
                                            <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookISBN')" />
                                        </div>
                                        <div>
                                            <x-input-label for="bookDeweyLocation" :value="__('Ubicación en estante')" />
                                            <x-text-input wire:model="bookDeweyLocation" id="bookDeweyLocation"
                                                name="bookDeweyLocation" type="text" class="block w-full"
                                                placeholder="Ubicación (Dewey)" required />
                                            <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookDeweyLocation')" />
                                        </div>
                                    </fieldset>
                                </div>
                                <!-- Segunda columna -->
                                <div class="space-y-5 col-span-full sm:col-span-1">
                                    <fieldset class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                            Detalles del Ejemplar</p>
                                        <div>
                                            <x-input-label for="bookStatus" :value="__('Estado del ejemplar')" />
                                            <select wire:model="bookStatus" id="bookStatus" name="bookStatus"
                                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150">
                                                <option value="" disabled>Seleccione el estado</option>
                                                <option value="Disponible">Disponible</option>
                                                <option value="Prestado">Prestado</option>
                                                <option value="Mantenimiento">Mantenimiento</option>
                                            </select>
                                            <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookStatus')" />
                                        </div>
                                        <div>
                                            <x-input-label for="bookEntryDate" :value="__('Fecha de ingreso')" />
                                            <x-text-input wire:model="bookEntryDate" id="bookEntryDate"
                                                name="bookEntryDate" type="date" class="block w-full"
                                                placeholder="Fecha de ingreso" />
                                            <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookEntryDate')" />
                                        </div>
                                        <div>
                                            <x-input-label for="bookCopyAmount" :value="__('Cantidad de ejemplares')" />
                                            <x-text-input wire:model="bookCopyAmount" id="bookCopyAmount"
                                                name="bookCopyAmount" type="number" min="1" max="100"
                                                class="block w-full" placeholder="Ej: 10" />
                                            <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookCopyAmount')" />
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                        <!-- Botones de acción del modal -->
                        <div
                            class="w-full flex flex-col sm:flex-row-reverse gap-2 bg-gray-50 dark:bg-gray-900 px-4 py-3 mt-6 sm:px-6">
                            <button type="button" wire:click="{{ $buttonAction }}"
                                class="inline-flex w-full sm:w-auto justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3">Guardar</button>
                            <button @click="open = false" type="button"
                                class="inline-flex w-full sm:w-auto justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
