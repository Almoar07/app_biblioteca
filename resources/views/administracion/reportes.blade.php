<x-app-layout>
    @section('title', ' - Generación de reportes')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administración - Generación de reportes') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div
            class=" bg-white dark:bg-gray-800 rounded-lg grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-10">

            {{-- Libros más prestados --}}
            <x-report-card icon="fas fa-file-alt" title="Libros más prestados entre fechas"
                description="Reporte de libros más prestados entre 2 fechas seleccionadas."
                formAction="{{ route('reportes.mas-prestados') }}" color="green">
                <x-slot name="formInputs">
                    <div class="mb-2">
                        <label class="block text-sm font-medium dark:text-gray-300">Fecha inicio</label>
                        {{-- <input type="date" name="fecha_inicio" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"> --}}
                        <x-text-input wire:model="fecha_inicio" id="fecha_inicio" name="fecha_inicio" type="date"
                            class="mt-1 block w-full" required autofocus autocomplete="fecha_inicio" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium dark:text-gray-300">Fecha fin</label>
                        {{-- <input type="date" name="fecha_fin" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"> --}}
                        <x-text-input wire:model="fecha_fin" id="fecha_fin" name="fecha_fin" type="date"
                            class="mt-1 block w-full" required autofocus autocomplete="fecha_fin" />
                    </div>
                </x-slot>
            </x-report-card>

            {{-- Stock de libros --}}
            <x-report-card icon="fa-solid fa-book-open" title="Stock de libros"
                description="Genera un reporte con las cantidades de todos los libros" :link="url('/reportes/stock')" color="indigo">
            </x-report-card>

            {{-- Prestamos por fecha --}}
            <x-report-card icon="fa-solid fa-book-open" title="Préstamos por fecha"
                description="Muestra todos los préstamos realizados entre las fechas indicadas"
                formAction="{{ route('reportes.prestamos-por-fechas') }}" color="red">
                <x-slot name="formInputs">
                    <div class="mb-2">
                        <label class="block text-sm font-medium dark:text-gray-300">Fecha inicio</label>
                        {{--                         <input type="date" name="fecha_inicio" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
 --}} <x-text-input wire:model="fecha_inicio" id="fecha_inicio"
                            name="fecha_inicio" type="date" class="mt-1 block w-full" required autofocus
                            autocomplete="fecha_inicio" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium dark:text-gray-300">Fecha fin</label>
                        {{--                         <input type="date" name="fecha_fin" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
 --}}
                        <x-text-input wire:model="fecha_fin" id="fecha_fin" name="fecha_fin" type="date"
                            class="mt-1 block w-full" required autofocus autocomplete="fecha_fin" />
                    </div>
                </x-slot>
            </x-report-card>
            {{-- Prestamos por lector --}}
            <x-report-card icon="fa-solid fa-user-graduate" title="Préstamos estudiante"
                description="Muestra el historial de prestamos de un lector"
                formAction="{{ route('reportes.prestamos-por-lector') }}" color="blue">
                <x-slot name="formInputs">
                    <div>
                        <label for="studentRUT" class="block text-sm font-medium dark:text-gray-300">RUT del
                            estudiante</label>
                        {{-- <input type="text" name="studentRUT" id="studentRUT" required placeholder="Ej: 12.345.678-9"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"> --}}
                        <x-text-input wire:model="studentRUT" id="studentRUT" name="studentRUT" type="text"
                            class="mt-1 block w-full" placeholder="Ej: 12.345.678-9" required autofocus
                            autocomplete="studentRUT" />
                    </div>
                </x-slot>


            </x-report-card>


        </div>
    </div>
</x-app-layout>
