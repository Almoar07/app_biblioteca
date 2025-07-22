<x-app-layout>
    @section('title', ' - Gestión de autores')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administración - Gestión de autores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @livewire('live-author-table')
            </div>
        </div>


    </div>
    @push('modals')
        @livewire('live-author-modal')
    @endpush

</x-app-layout>
