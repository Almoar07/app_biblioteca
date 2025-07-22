@props([
    'title' => '',
    'description' => '',
    'icon' => '',
    'route' => '#',
    'buttonText' => 'Gestionar',
])

<div class="bg-gray-200 dark:bg-gray-700 p-4 rounded-lg shadow-md flex flex-col transition-shadow hover:shadow-lg">
    <!-- Imagen o ícono -->
    <div class="h-40 bg-gray-300 dark:bg-gray-600 rounded-md mb-4 flex items-center justify-center">
        {{-- Ícono SVG que llega como slot --}}
        @if ($icon)
            {!! $icon !!}
        @else
            {{ $slot }}
        @endif
    </div>

    <!-- Título -->
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</h3>

    <!-- Descripción -->
    <p class="mt-2 text-gray-600 dark:text-gray-300 flex-grow">{{ $description }}</p>

    {{-- <!-- Botón -->
    <button onclick="window.location.href='{{ $route }}'" wire.navigate
        class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
        {{ $buttonText }}
    </button> --}}

    <a href="{{ $route }}" wire:navigate
        class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-full inline-block text-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
        {{ $buttonText }}
    </a>

</div>
