<div x-data="{ open: @entangle('showModal') }" x-cloak>
    <!-- Fondo oscuro -->
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity" aria-hidden="true">
    </div>

    <!-- Contenedor principal del modal -->
    <div x-show="open" class="fixed inset-0 z-10 w-screen overflow-y-auto ">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300">
                <!-- Contenedor principal del modal con mejor espaciado -->
                <div class="bg-gray-100 dark:bg-gray-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-lg">
                    <div class="sm:flex sm:items-start">

                        <!-- Contenido principal con mejor organización -->
                        <div class="mt-4 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">

                            <div class="border-b border-gray-200 dark:border-gray-700 pb-3 flex items-start">
                                <div
                                    class="mr-4 flex size-14 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 sm:size-12">
                                    <svg class="size-7 text-blue-600 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        {{ $mode === 'create' ? 'Crear categoría' : 'Editar categoría' }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $mode === 'create' ? 'Ingrese los datos de la nueva categoría' : 'Complete o modifique información de la categoría' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Formulario con mejor agrupación semántica -->
                            <form>
                                <div class="mt-5 grid grid-cols-1 sm:grid-cols-1 gap-5">
                                    <!-- Columna 1 - Información personal -->
                                    <div class="space-y-5">
                                        <fieldset class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                                Información de la categoría</p>

                                            <!-- Nombre -->
                                            <div>
                                                <x-input-label for="categoryName" :value="__('Nombre')" class="sr-only" />
                                                <x-text-input wire:model="categoryName" id="categoryName"
                                                    name="categoryName" type="text" class="block w-full"
                                                    placeholder="Nombre" required autofocus autocomplete="given-name" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('categoryName')" />
                                            </div>

                                            <!-- Descripción de la categoría -->
                                            <div>
                                                <div>
                                                    <x-input-label for="categoryDescription" :value="__('Descripción')"
                                                        class="sr-only" />
                                                    <x-textarea-input wire:model="categoryDescription"
                                                        id="categoryDescription"
                                                        placeholder="Descripción de la categoría" class="block w-full"
                                                        required />
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class=" bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">

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
