<div x-data="{ open: @entangle('showModal') }" x-cloak>
    @php
        switch ($mode) {
            case 'create':
                $tituloModal = 'Registrar Nuevo Préstamo';
                $subtituloModal = 'Complete los datos del estudiante y luego los del libro.';
                $buttonAction = 'createLoan()';
                break;
            case 'edit':
                $tituloModal = 'Editar Préstamo';
                $subtituloModal = 'Complete o modifique los datos existentes del préstamo.';
                $buttonAction = 'updateLoan()';
                break;
            case 'return':
                $tituloModal = 'Devolver Préstamo';
                $subtituloModal = '¿Está seguro de que desea devolver este préstamo?';
                $buttonAction = 'returnBook()';
                break;
            default:
                $tituloModal = 'Sin Título';
                $subtituloModal = 'No hay un modo definido para el modal.';
                $buttonAction = '';
                break;
        }
    @endphp

    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity" aria-hidden="true">
    </div>

    <div x-show="open" class="fixed inset-0 z-10 w-screen overflow-y-auto flex items-center justify-center">
        <div x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform rounded-lg bg-gray-100 dark:bg-gray-900 text-left shadow-xl transition-all w-11/12 max-w-7xl max-h-[90vh] overflow-hidden">
            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start mb-4">
                    <div
                        class="mx-auto flex size-10 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:size-9">
                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                            {{ $tituloModal }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $subtituloModal }}
                        </p>
                    </div>
                </div>

                <form class="space-y-4">
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Información del Estudiante
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                            <div class="sm:col-span-1">
                                <x-input-label for="studentRut" :value="__('RUT del estudiante')" />
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1">
                                        <x-text-input wire:model.live="studentRut" id="studentRut" name="studentRut"
                                            type="text" class="block w-full" placeholder="Ej: 12.345.678-9"
                                            wire:keydown.enter="searchStudentData" />
                                        <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentRut')" />
                                    </div>
                                    <button type="button" wire:click="searchStudentData()"
                                        class="inline-flex items-center p-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 h-9 w-9 justify-center"
                                        title="Buscar datos del estudiante">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="sm:col-span-1">
                                <x-input-label for="studentFullName" :value="__('Nombre completo')" />
                                <x-text-input wire:model="studentFullName" id="studentFullName" name="studentFullName"
                                    type="text" class="block w-full" placeholder="Nombre y Apellido del estudiante"
                                    readonly />
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentFullName')" />
                            </div>
                            <div class="sm:col-span-1">
                                <x-input-label for="studentGrade" :value="__('Curso')" />
                                <x-text-input wire:model="studentGrade" id="studentGrade" name="studentGrade"
                                    type="text" class="block w-full" placeholder="Ej: 4° Básico" readonly />
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('studentGrade')" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div
                            class="md:col-span-1 flex flex-col items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm space-y-3">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Portada
                            </p>
                            <div
                                class="w-full max-w-[120px] h-[160px] bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center overflow-hidden border border-gray-300 dark:border-gray-600">
                                @if ($bookCoverImage)
                                    <img src="{{ $bookCoverImage }}" alt="Portada del libro"
                                        class="object-cover w-full h-full">
                                @else
                                    <span class="text-gray-400 text-xs text-center p-2">Sin Portada Disponible</span>
                                @endif
                            </div>
                            <div class="w-full mt-auto">
                                <x-input-label for="bookCopyBarCode" :value="__('Código de barras')" />
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1">
                                        <x-text-input type="text" id="bookCopyBarCode" name="bookCopyBarCode"
                                            wire:model.live="bookCopyBarCode" wire:keydown.enter="searchBookCopiesData"
                                            class="block w-full" placeholder="Código de barras" />
                                        <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookCopyBarCode')" />
                                    </div>
                                    <button type="button" wire:click="searchBookCopiesData()"
                                        class="inline-flex items-center p-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 h-9 w-9 justify-center"
                                        title="Buscar datos del libro">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-1 space-y-3 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Datos del Libro
                            </p>
                            <div>
                                <x-input-label for="bookTitle" :value="__('Título')" />
                                <x-text-input wire:model="bookTitle" id="bookTitle" name="bookTitle" type="text"
                                    class="block w-full" readonly />
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookTitle')" />
                            </div>
                            <div>
                                <x-input-label for="bookAuthorName" :value="__('Autor')" />
                                <x-text-input wire:model="bookAuthorName" id="bookAuthorName" name="bookAuthorName"
                                    type="text" class="block w-full" readonly />
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookAuthorName')" />
                            </div>
                            <div>
                                <x-input-label for="bookPublisher" :value="__('Editorial')" />
                                <x-text-input wire:model="bookPublisher" id="bookPublisher" name="bookPublisher"
                                    type="text" class="block w-full" readonly />
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookPublisher')" />
                            </div>
                            <div>
                                <x-input-label for="bookCopyStatus" :value="__('Estado Ejemplar')" />
                                <x-text-input wire:model="bookCopyStatus" id="bookCopyStatus" name="bookCopyStatus"
                                    type="text" class="block w-full" readonly />
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookCopyStatus')" />
                            </div>
                        </div>

                        <div class="md:col-span-1 space-y-3 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Detalles del Préstamo
                            </p>
                            <div>
                                <x-input-label for="loanUserID" :value="__('Bibliotecario')" />
                                <select wire:model="loanUserID" id="loanUserID" name="loanUserID"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <option value="" disabled>Seleccione un bibliotecario</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            @if ($loanUserID == $user->id) selected @endif>
                                            {{ $user->name }} {{ $user->lastname }} {{ $user->lastname2 }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('loanUserID')" />
                            </div>
                            {{--  <div>
                                <x-input-label for="loanStatus" :value="__('Estado del Préstamo')" />
                                <select wire:model="loanStatus" id="loanStatus" name="loanStatus"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <option value="" disabled>Seleccione el estado</option>
                                    <option value="prestado">Prestado</option>
                                    <option value="devuelto">Devuelto</option>
                                    <option value="retrasado">Retrasado</option>
                                </select>
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('loanStatus')" />
                            </div> --}}
                            <div>
                                <x-input-label for="loanDate" wire:model="loanDate" :value="__('Fecha de Préstamo')" />
                                <x-text-input wire:model="loanDate" id="loanDate" name="loanDate" type="date"
                                    class="block w-full" />
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('loanDate')" />
                            </div>

                            <div>
                                <label for="bookSelectedLoanDays"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Días de préstamo
                                </label>

                                <select name="bookSelectedLoanDays" id="bookSelectedLoanDays"
                                    wire:model.live="bookSelectedLoanDays"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-white">
                                    @for ($i = 1; $i <= $bookMaxLoanDays; $i++)
                                        <option value="{{ $i }}">{{ $i }}
                                            día{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>

                            </div>
                            <div>
                                <x-input-label for="loanReturnDate" :value="__('Fecha de Devolución')" />
                                <x-text-input id="loanReturnDate" name="loanReturnDate" type="date"
                                    class="block w-full" readonly :value="$loanReturnDate" />
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('loanReturnDate')" />
                            </div>

                        </div>

                        <div class="md:col-span-1 space-y-3 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Observaciones Adicionales
                            </p>
                            <div>
                                <x-input-label for="loanObservations" :value="__('Observaciones del préstamo')" />
                                <textarea wire:model="loanObservations" id="loanObservations" name="loanObservations" rows="11"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"></textarea>
                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('loanObservations')" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 rounded-b-lg">
                <button type="button" wire:click="{{ $buttonAction }}"
                    class="inline-flex w-full justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                    Guardar
                </button>
                <button type="button" @click="open = false"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
