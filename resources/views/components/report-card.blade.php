@props(['icon', 'color' => 'blue', 'title', 'description', 'formAction' => null, 'link' => null])

<div
    class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 border border-gray-200 dark:border-gray-700 flex flex-col">
    <div class="flex items-center space-x-4 mb-4">
        <i class="{{ $icon }} text-{{ $color }}-600 text-3xl"></i>
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $description }}</p>
        </div>
    </div>

    <div class="mt-auto">
        @if ($formAction)
            {{-- Formulario dinámico con slot --}}
            <form action="{{ $formAction }}" method="GET"
                class="flex-col sm:flex-row sm:items-end gap-4 grid grid-cols-1 md:grid-cols-2">
                <div class="flex-grow">
                    {{ $formInputs ?? '' }}
                </div>
                <button type="submit"
                    class="w-full sm:w-auto  bg-{{ $color }}-600 text-white px-6 py-2 rounded-lg hover:bg-{{ $color }}-700 transition font-semibold">
                    Generar Reporte
                </button>
            </form>
        @elseif ($link)
            {{-- Botón simple --}}
            <a href="{{ $link }}"
                class="inline-block w-full text-center px-6 py-2 bg-{{ $color }}-600 text-white rounded-lg hover:bg-{{ $color }}-700 transition font-semibold">
                Generar Reporte
            </a>
        @endif
    </div>
</div>
