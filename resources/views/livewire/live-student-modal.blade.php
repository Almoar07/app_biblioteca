<div x-data="{ open: @entangle('showModal') }" x-cloak>
    @php
        switch ($mode) {
            case 'create':
                $tituloModal = 'Crear estudiante';
                $subtituloModal = 'Ingrese los datos del nuevo estudiante';
                $buttonAction = 'createStudent()';
                $elementDisabled = false;
                break;
            case 'edit':
                $tituloModal = 'Editar estudiante';
                $subtituloModal = 'Complete o modifique la información del estudiante';
                $buttonAction = 'updateStudent';
                break;
            case 'delete':
                $tituloModal = 'Eliminar estudiante';
                $subtituloModal = '¿Está seguro de que desea eliminar este estudiante?';
                $buttonAction = 'deleteStudent';
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
                            <!-- Formulario con 3 columnas -->
                            <form>
                                <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-5">
                                    <!-- Columna 1 - Información personal -->
                                    <div class="space-y-5">
                                        <fieldset class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                                Información Personal</p>
                                            <!-- Rut estudiante -->
                                            <div>
                                                <x-input-label for="studentId" :value="__('Rut del estudiante')" class="sr-only" />
                                                <x-text-input wire:model="studentId" id="studentId" name="studentId"
                                                    type="text" class="block w-full" placeholder="Rut del estudiante"
                                                    required autofocus autocomplete="given-name" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentId')" />
                                            </div>
                                            <!-- Nombre -->
                                            <div>
                                                <x-input-label for="studentName" :value="__('Nombres')" class="sr-only" />
                                                <x-text-input wire:model="studentName" id="studentName"
                                                    name="studentName" type="text" class="block w-full"
                                                    placeholder="Nombres" required autofocus
                                                    autocomplete="given-name" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentName')" />
                                            </div>
                                            <!-- Apellidos -->
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <x-input-label for="studentLastname" :value="__('Paterno')"
                                                        class="sr-only" />
                                                    <x-text-input wire:model="studentLastname" id="studentLastname"
                                                        placeholder="Apellido paterno" class="block w-full" required />
                                                </div>
                                                <div>
                                                    <x-input-label for="studentLastname2" :value="__('Materno')"
                                                        class="sr-only" />
                                                    <x-text-input wire:model="studentLastname2"
                                                        placeholder="Apellido materno" class="block w-full" required />
                                                </div>
                                            </div>
                                            <!-- Fecha de Nacimiento -->
                                            <div>
                                                <x-input-label for="studentBirthday" :value="__('Fecha nacimiento')"
                                                    class="sr-only" />
                                                <x-text-input wire:model="studentBirthday" id="studentBirthday"
                                                    type="date" class="block w-full" required />
                                            </div>
                                            <p class="mt-4 text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                                Información académica</p>
                                            <!-- Grado y Letra del estudiante -->
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <x-input-label for="studentGrade" :value="__('Grado')"
                                                        class="sr-only" />
                                                    <select wire:model="studentGrade" id="studentGrade"
                                                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150">
                                                        <option value="" disabled>Seleccione curso</option>
                                                        @foreach (['7°', '8°', '1°', '2°', '3°', '4°'] as $grado)
                                                            <option value="{{ $grado }}">{{ $grado }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentGrade')" />
                                                </div>
                                                <div>
                                                    <x-input-label for="studentLetter" :value="__('Letra')"
                                                        class="sr-only" />
                                                    <select wire:model="studentLetter" id="studentLetter"
                                                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150">
                                                        <option value="" disabled>Letra del curso</option>
                                                        @foreach (range('A', 'M') as $letter)
                                                            <option value="{{ $letter }}">{{ $letter }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentLetter')" />
                                                </div>
                                            </div>
                                            @php
                                                $studentCourse =
                                                    ($studentGrade ?? '') && ($studentLetter ?? '')
                                                        ? $studentGrade . ' ' . $studentLetter
                                                        : '';
                                            @endphp
                                        </fieldset>
                                    </div>
                                    <!-- Columna 2 - Información de contacto -->
                                    <div class="space-y-5">
                                        <fieldset class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                                Información de Contacto</p>
                                            <!-- Dirección del estudiante-->
                                            <div>
                                                <x-input-label for="studentAddress" :value="__('Dirección del estudiante')"
                                                    class="sr-only" />
                                                <x-text-input wire:model="studentAddress" id="studentAddress"
                                                    type="text" class="block w-full"
                                                    placeholder="Direccion del estudiante" required
                                                    autocomplete="address" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentAddress')" />
                                            </div>
                                            <!-- Comuna de la direccion del estudiante -->
                                            <div>
                                                <x-input-label for="studentMunicipalityId" :value="__('Comuna del estudiante')"
                                                    class="sr-only" />
                                                <select wire:model="studentMunicipalityId" id="studentMunicipalityId"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150">
                                                    <option value="" disabled>Seleccione una comuna</option>
                                                    @foreach ($municipalities as $comuna)
                                                        <option value="{{ $comuna->id_comuna }}">
                                                            {{ $comuna->nombre_comuna }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentComuna')" />
                                            </div>
                                            <!-- Correo Electrónico -->
                                            <div>
                                                <x-input-label for="studentEmail" :value="__('Correo electrónico')"
                                                    class="sr-only" />
                                                <x-text-input wire:model="studentEmail" id="studentEmail"
                                                    type="email" class="block w-full"
                                                    placeholder="Correo electrónico" required autocomplete="email" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentEmail')" />
                                            </div>
                                            <!-- Teléfono -->
                                            <div>
                                                <x-input-label for="studentPhone" :value="__('Teléfono')"
                                                    class="sr-only" />
                                                <x-text-input wire:model="studentPhone" id="studentPhone"
                                                    type="tel" class="block w-full" placeholder="Teléfono"
                                                    required autocomplete="tel" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentPhone')" />
                                            </div>
                                        </fieldset>
                                    </div>
                                    <!-- Columna 3 - Estado del estudiante u otros -->
                                    <div class="space-y-5">
                                        <fieldset class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm space-y-3">
                                            <x-input-label :value="__('Estado del Estudiante')" />
                                            <select wire:model="studentStatus" id="studentStatus"
                                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150">
                                                <option value="" disabled>Seleccione estado</option>
                                                <option value="activo">Activo</option>
                                                <option value="inactivo">Inactivo</option>
                                                <option value="egresado">Egresado</option>
                                                <option value="bloqueado">Bloqueado</option>
                                            </select>
                                            <x-input-error class="mt-1 text-sm" :messages="$errors->get('userStatus')" />
                                        </fieldset>
                                    </div>
                                </div>
                            </form>
                            <!-- Botones fuera del grid, ocupando toda la fila -->
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
</div>
</div>
