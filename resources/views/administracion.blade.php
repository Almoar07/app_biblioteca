<x-app-layout>
    @section('title', ' - Administración')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                        {{-- Gestión de Usuarios --}}
                        <x-card-administracion title="Gestión de Usuarios"
                            description="Añadir, editar o eliminar usuarios."
                            route="{{ route('administracion.usuarios') }}" wire:navigate buttonText="Gestionar Usuarios">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </x-card-administracion>

                        {{-- Gestión de Estudiantes --}}
                        <x-card-administracion title="Gestión de Estudiantes"
                            description="Añadir, editar o eliminar estudiantes."
                            route="{{ route('administracion.estudiantes') }}" buttonText="Gestionar Estudiantes">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </x-card-administracion>

                        {{-- Gestión de Autores --}}
                        <x-card-administracion title="Gestión de Autores"
                            description="Añadir, editar o eliminar autores."
                            route="{{ route('administracion.autores') }}" buttonText="Gestionar Autores">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                                </path>
                            </svg>
                        </x-card-administracion>

                        {{-- Gestión de Categorías --}}
                        <x-card-administracion title="Gestión de Categorías"
                            description="Añadir, editar o eliminar categorías."
                            route="{{ route('administracion.categorias') }}" buttonText="Gestionar Categorias">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </x-card-administracion>

                        {{-- Gestión de Libros --}}
                        <x-card-administracion title="Gestión de Libros" description="Añadir, editar o eliminar libros."
                            route="{{ route('administracion.libros') }}" buttonText="Gestionar Libros">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </x-card-administracion>

                        {{-- Gestión de Préstamos --}}
                        <x-card-administracion title="Gestión de Préstamos"
                            description="Registrar y gestionar préstamos de libros."
                            route="{{ route('administracion.prestamos') }}" {{-- Añade la ruta cuando esté lista --}}
                            buttonText="Gestionar Préstamos">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </x-card-administracion>

                        {{-- Gestión de Editoriales --}}
                        <x-card-administracion title="Gestión de Editoriales"
                            description="Añadir, editar o eliminar editoriales."
                            route="{{ route('administracion.editoriales') }}" buttonText="Gestionar Editoriales">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </x-card-administracion>
                        <x-card-administracion title="Reportes" description="Generar reportes en formato Excel."
                            route="{{ route('reportes.index') }}" buttonText="Generar Reporte">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </x-card-administracion>


                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
