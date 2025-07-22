<x-app-layout>

    @section('title', ' - Dashboard')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}

        </h2>
    </x-slot>

    @if (Auth::user()->status === \App\Enums\UserStatus::BLOQUEADO)
        {{-- Add flexbox classes here to center the content vertically and horizontally --}}
        <div class="flex items-center justify-center py-12">
            @include('auth.usuario-bloqueado')
        </div>
    @else
        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    @livewire('live-dashboard')
                </div>
            </div>

        </div>
    @endif

</x-app-layout>
