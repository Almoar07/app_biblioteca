<x-app-layout>
    @section('title', ' - Préstamos y devoluciones')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administración - Préstamos y devoluciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if (isset($prestamo))
                    @livewire('live-loan-table', ['loanByID' => $prestamo->id_prestamo])
                @else
                    @livewire('live-loan-table')
                @endif



            </div>
        </div>
    </div>
    @push('modals')
        @livewire('live-loan-modal')
    @endpush
</x-app-layout>
