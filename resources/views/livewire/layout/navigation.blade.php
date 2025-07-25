<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-16 w-auto fill-current text-blue-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 lg:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (auth()->user()->isAdmin())
                        <x-nav-link :href="route('administracion')" :active="request()->routeIs('administracion')" wire:navigate>
                            {{ __('Administración') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.index')" wire:navigate>
                            {{ __('Reportes') }}
                        </x-nav-link>
                    @endif
                    @if (auth()->user()->isLibrarian())
                        <x-nav-link :href="route('administracion.estudiantes')" :active="request()->routeIs('administracion.estudiantes')" wire:navigate>
                            {{ __('Estudiantes') }}
                        </x-nav-link>
                        <x-nav-link :href="route('administracion.autores')" :active="request()->routeIs('administracion.autores')" wire:navigate>
                            {{ __('Autores') }}
                        </x-nav-link>
                        <x-nav-link :href="route('administracion.editoriales')" :active="request()->routeIs('administracion.editoriales')" wire:navigate>
                            {{ __('Editoriales') }}
                        </x-nav-link>
                        <x-nav-link :href="route('administracion.categorias')" :active="request()->routeIs('administracion.categorias')" wire:navigate>
                            {{ __('Categorías') }}
                        </x-nav-link>
                        <x-nav-link :href="route('administracion.libros')" :active="request()->routeIs('administracion.libros')" wire:navigate>
                            {{ __('Libros') }}
                        </x-nav-link>
                        <x-nav-link :href="route('administracion.prestamos')" :active="request()->routeIs('administracion.prestamos')" wire:navigate>
                            {{ __('Préstamos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.index')" wire:navigate>
                            {{ __('Reportes') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Navigation Menu con transición -->
        <div x-show="open" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2" class="lg:hidden">
            <!-- Responsive Links -->
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                        x-text="name" x-on:profile-updated.window="name = $event.detail.name">
                    </div>
                    <div class="font-medium text-sm text-gray-500">
                        {{ auth()->user()->email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Logout -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>

                    <hr class="w-64 h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">

                    @if (auth()->user()->isAdmin())
                        <x-responsive-nav-link :href="route('administracion')" :active="request()->routeIs('administracion')" wire:navigate>
                            {{ __('Administración') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.index')" wire:navigate>
                            {{ __('Reportes') }}
                        </x-responsive-nav-link>
                    @endif

                    @if (auth()->user()->isLibrarian())
                        <x-responsive-nav-link :href="route('administracion.estudiantes')" :active="request()->routeIs('administracion.estudiantes')" wire:navigate>
                            {{ __('Estudiantes') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('administracion.autores')" :active="request()->routeIs('administracion.autores')" wire:navigate>
                            {{ __('Autores') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('administracion.editoriales')" :active="request()->routeIs('administracion.editoriales')" wire:navigate>
                            {{ __('Editoriales') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('administracion.categorias')" :active="request()->routeIs('administracion.categorias')" wire:navigate>
                            {{ __('Categorías') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('administracion.libros')" :active="request()->routeIs('administracion.libros')" wire:navigate>
                            {{ __('Libros') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('administracion.prestamos')" :active="request()->routeIs('administracion.prestamos')" wire:navigate>
                            {{ __('Préstamos') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.index')" wire:navigate>
                            {{ __('Reportes') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            </div>
        </div>

    </div>
</nav>
