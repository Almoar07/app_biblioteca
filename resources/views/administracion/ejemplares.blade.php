<x-app-layout>
    @section('title', ' - Detalle Libro')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle del Libro') }}
        </h2>
    </x-slot>



    @livewire('live-book-copies-table')
    @push('modals')
        @livewire('live-book-copies-modal')
        @livewire('live-loan-modal')
    @endpush
</x-app-layout>
