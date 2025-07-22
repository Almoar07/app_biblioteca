<div x-data="{ open: @entangle('showModal') }" x-cloak>
    @php
        switch ($mode) {
            case 'create':
                $tituloModal = 'Crear Usuario';
                $subtituloModal = 'Ingrese los datos del nuevo usuario';
                $buttonText = 'Crear Usuario';
                $buttonAction = 'createUser';

                break;
            case 'edit':
                $tituloModal = 'Editar usuario';
                $subtituloModal = 'Complete o modifique la información del usuario';
                $buttonText = 'Actualizar Usuario';
                $buttonAction = 'updateUser';

                break;
            case 'delete':
                $tituloModal = 'Eliminar usuario';
                $subtituloModal = '¿Está seguro de que desea eliminar este usuario?';
                $buttonText = 'Eliminar Usuario';
                $buttonAction = 'deleteUser';

                break;
            default:
                $tituloModal = 'Sin título';
                $subtituloModal = 'No hay un modo definido';
                $buttonAction = '';
                break;
        }
    @endphp

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
                                        {{ $tituloModal }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $subtituloModal }}
                                    </p>



                                </div>
                            </div>

                            <!-- Formulario con mejor agrupación semántica -->
                            <form>
                                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <!-- Columna 1 - Información personal -->
                                    <div class="space-y-5">
                                        <fieldset class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                                Información Personal</p>

                                            <!-- Rut Usuario -->
                                            <div>
                                                <x-input-label for="userRut" :value="__('Rut del usuario')" class="sr-only" />

                                                <x-text-input wire:model="userRut" id="userRut" name="userRut"
                                                    type="text" class="block w-full" placeholder="Rut del usuario"
                                                    required autofocus autocomplete="given-name" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('userName')" />
                                            </div>
                                            <!-- Nombre -->
                                            <div>
                                                <x-input-label for="userName" :value="__('Nombre')" class="sr-only" />

                                                <x-text-input wire:model="userName" id="userName" name="userName"
                                                    type="text" class="block w-full" placeholder="Nombre" required
                                                    autofocus autocomplete="given-name" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('userName')" />
                                            </div>

                                            <!-- Apellidos -->
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <x-input-label for="userLastname" :value="__('Paterno')"
                                                        class="sr-only" />
                                                    <x-text-input wire:model="userLastname" id="userLastname"
                                                        placeholder="Apellido paterno" class="block w-full" required />
                                                </div>
                                                <div>
                                                    <x-input-label for="userLastname2" :value="__('Materno')"
                                                        class="sr-only" />
                                                    <x-text-input wire:model="userLastname2"
                                                        placeholder="Apellido materno" class="block w-full" required />
                                                </div>
                                            </div>

                                            <!-- Fecha de Nacimiento -->
                                            <div>
                                                <x-input-label for="userBirthday" :value="__('Fecha nacimiento')" class="sr-only" />
                                                <x-text-input wire:model="userBirthday" id="userBirthday" type="date"
                                                    class="block w-full" required />
                                            </div>
                                        </fieldset>

                                        <!-- Tipo de Usuario -->
                                        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                            <x-input-label for="userTipoUsuario" :value="__('Tipo de usuario')" />
                                            <select wire:model="userTipoUsuario" id="userTipoUsuario"
                                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150">
                                                <option value="" disabled selected>Seleccione un tipo</option>
                                                <option value="admin">Administrador</option>
                                                <option value="bibliotecario">Bibliotecario</option>
                                                <option value="invitado">Invitado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Columna 2 - Información de contacto -->
                                    <div class="space-y-5">
                                        <fieldset class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                                Información de Contacto</p>

                                            <!-- Correo Electrónico -->
                                            <div>
                                                <x-input-label for="userEmail" :value="__('Correo electrónico')" class="sr-only" />
                                                <x-text-input wire:model="userEmail" id="userEmail" type="email"
                                                    class="block w-full" placeholder="Correo electrónico" required
                                                    autocomplete="email" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('userEmail')" />
                                            </div>

                                            <!-- Teléfono -->
                                            <div>
                                                <x-input-label for="userPhone" :value="__('Teléfono')" class="sr-only" />
                                                <x-text-input wire:model="userPhone" id="userPhone" type="tel"
                                                    class="block w-full" placeholder="Teléfono" required
                                                    autocomplete="tel" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('userPhone')" />
                                            </div>
                                        </fieldset>


                                        <!-- Espacio para elementos adicionales -->
                                        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm space-y-3">
                                            <x-input-label :value="__('Estado del Usuario')" />
                                            <div
                                                class="justify-center flex flex-col sm:flex-row sm:items-center sm:space-x-3 space-y-2 sm:space-y-0">
                                                <!-- Estado Activo -->
                                                <label class="inline-flex items-center justify-center">
                                                    <input type="radio" wire:model="userStatus" value="activo"
                                                        class="hidden peer"
                                                        @if ($userStatus === 'activo') checked @endif>
                                                    <div
                                                        class="px-4 py-2 rounded-full peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-700 peer-checked:text-green-700 dark:peer-checked:text-green-400 cursor-pointer transition-colors duration-200 w-1/2 sm:w-auto">
                                                        <div class="flex items-center space-x-2">
                                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                                            <span
                                                                class="text-gray-700 dark:text-gray-300">Activo</span>
                                                        </div>
                                                    </div>
                                                </label>

                                                <!-- Estado Inactivo -->
                                                <label class="inline-flex items-center justify-center">
                                                    <input type="radio" wire:model="userStatus" value="inactivo"
                                                        class="hidden peer"
                                                        @if ($userStatus === 'inactivo') checked @endif>
                                                    <div
                                                        class="px-4 py-2 rounded-full peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-700 peer-checked:text-yellow-700 dark:peer-checked:text-yellow-400 cursor-pointer transition-colors duration-200 w-1/2 sm:w-auto">
                                                        <div class="flex items-center space-x-2">
                                                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                                            <span
                                                                class="text-gray-700 dark:text-gray-300">Inactivo</span>
                                                        </div>
                                                    </div>
                                                </label>

                                                <!-- Estado Bloqueado -->
                                                <label class="inline-flex items-center justify-center">
                                                    <input type="radio" wire:model="userStatus" value="bloqueado"
                                                        class="hidden peer"
                                                        @if ($userStatus === 'bloqueado') checked @endif>
                                                    <div
                                                        class="px-4 py-2 rounded-full peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-700 peer-checked:text-red-700 dark:peer-checked:text-red-400 cursor-pointer transition-colors duration-200 w-1/2 sm:w-auto">
                                                        <div class="flex items-center space-x-2">
                                                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                            <span
                                                                class="text-gray-700 dark:text-gray-300">Bloqueado</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <x-input-error class="mt-1 text-sm" :messages="$errors->get('userStatus')" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class=" bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" wire:click={{ $buttonAction }}
                        class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                        Guardar
                    </button>
                    <button @click="open = false" type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cancelar
                    </button>
                    @if ($mode === 'edit')
                        <button wire:click="sendPasswordResetLink({{ $user->id }})"
                            class="mr-3 inline-flex items-center justify-center rounded-md sm:mt-0 sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 text-sm font-semibold shadow-sm"
                            title="Enviar enlace restablecer contraseña">
                            <i class="fa-solid fa-key mr-2"></i> Restablecer contraseña
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
