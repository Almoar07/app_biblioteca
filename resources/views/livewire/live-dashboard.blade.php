<div class="font-sans antialiased">
    <!-- Chart.js CDN -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <style>
        /* Estilos personalizados para la barra de desplazamiento */
        .scrollbar-thin::-webkit-scrollbar {
            width: 8px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Color de fondo de la pista */
            border-radius: 10px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            /* Color del "pulgar" de la barra de desplazamiento */
            border-radius: 10px;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-track {
            background: #374151;
            /* Color de fondo de la pista en modo oscuro */
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #4b5563;
            /* Color del "pulgar" en modo oscuro */
        }
    </style>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Estadísticas principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    $cards = [
                        [
                            'title' => 'Total Libros',
                            'value' => number_format($totalLibros),
                            'bg' => 'bg-blue-500',
                            'icon' => 'book',
                        ],
                        [
                            'title' => 'Ejemplares Disponibles',
                            'value' => number_format($ejemplaresDisponibles),
                            'bg' => 'bg-green-500',
                            'icon' => 'check',
                        ],
                        [
                            'title' => 'Préstamos Activos',
                            'value' => number_format($prestamosActivos),
                            'bg' => 'bg-yellow-500',
                            'icon' => 'clock',
                        ],
                        [
                            'title' => 'Préstamos Vencidos',
                            'value' => number_format($prestamosVencidos),
                            'bg' => 'bg-red-500',
                            'icon' => 'alert',
                        ],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 {{ $card['bg'] }} rounded-lg flex items-center justify-center">
                                    @switch($card['icon'])
                                        @case('book')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13M12 6.253C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13m9-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13" />
                                            </svg>
                                        @break

                                        @case('check')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @break

                                        @case('clock')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @break

                                        @case('alert')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01M4.5 20h15a2 2 0 001.73-2.5L13.73 4a2 2 0 00-3.46 0L2.77 17.5A2 2 0 004.5 20z" />
                                            </svg>
                                        @break
                                    @endswitch
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $card['title'] }}</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $card['value'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Contenido principal -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Préstamos Recientes -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Préstamos Recientes</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">

                            @forelse($prestamosRecientes as $prestamo)
                                <a href="{{ route('administracion.prestamos.show', ['id' => $prestamo->id_prestamo]) }}"
                                    class="hover:scale-105 hover: shadow-md transition-all flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $prestamo->estudiante->nombres ?? 'N/A' }}
                                                {{ $prestamo->estudiante->apellido_paterno ?? 'N/A' }}
                                                {{ $prestamo->estudiante->apellido_materno ?? 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-300">
                                                {{ $prestamo->ejemplar->libro->titulo ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right space-y-1">
                                        <p class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ $prestamo->fecha_prestamo }}
                                        </p>
                                        @php
                                            $clases = [
                                                'activo' => 'bg-blue-100 text-blue-800',
                                                'retrasado' => 'bg-yellow-500 text-gray-800',
                                                'devuelto_al_dia' => 'bg-green-100 text-green-800',
                                                'devuelto_con_retraso' => 'bg-red-100 text-red-800',
                                            ];
                                            $labels = [
                                                'activo' => 'Activo',
                                                'retrasado' => 'Retrasado',
                                                'devuelto_al_dia' => 'Devuelto a tiempo',
                                                'devuelto_con_retraso' => 'Devuelto con retraso',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 py-1 rounded-full font-semibold {{ $clases[$prestamo->estado] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $labels[$prestamo->estado] ?? 'Desconocido' }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No hay préstamos recientes
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Libros Más Populares -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Libros Más Populares</h3>
                    </div>
                    <div class="p-6">
                        <div
                            class="space-y-4 max-h-[340px] overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700">

                            @forelse($librosPopulares as $libro)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-10 h-10 bg-purple-100 dark:bg-purple-800 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13M12 6.253C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13M13.168 6.253C14.754 5 16.5 5 18.5 5s3.332.477 4.5 1.253v13" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $libro->titulo }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-300">
                                                {{ $libro->autor->nombre }}
                                                {{ $libro->autor->apellido_paterno }}
                                                {{ $libro->autor->apellido_materno }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            {{ $libro->prestamos_count }} préstamos
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No hay datos de popularidad
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Gráficos -->
            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Estadísticas de Préstamos por
                    Mes</h3>

                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <div class="flex-1">
                        <label for="chartType"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de
                            Gráfico:</label>
                        <select id="chartType" wire:model.live="chartType"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="books_per_month">Libros Prestados por Mes</option>
                            <option value="overdue_loans_per_month">Préstamos Vencidos por Mes</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="selectedYear"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Año:</label>
                        <select id="selectedYear" wire:model.live="selectedYear"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            @foreach (range(Carbon\Carbon::now()->year, 2000) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="relative h-96">
                    <canvas id="myChart"></canvas>
                </div>
            </div>

            <!-- Estadísticas adicionales -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $extras = [
                        [
                            'label' => 'Total Estudiantes',
                            'count' => $totalEstudiantes,
                            'bg' => 'bg-indigo-100 dark:bg-indigo-900',
                            'iconColor' => 'text-indigo-600 dark:text-indigo-300',
                        ],
                        [
                            'label' => 'Total Categorías',
                            'count' => $totalCategorias,
                            'bg' => 'bg-green-100 dark:bg-green-900',
                            'iconColor' => 'text-green-600 dark:text-green-300',
                        ],
                        [
                            'label' => 'Total Ejemplares',
                            'count' => $totalEjemplares,
                            'bg' => 'bg-yellow-100 dark:bg-yellow-900',
                            'iconColor' => 'text-yellow-600 dark:text-yellow-300',
                        ],
                    ];
                @endphp

                @foreach ($extras as $extra)
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $extra['label'] }}
                                </p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format($extra['count']) }}</p>
                            </div>
                            <div class="w-12 h-12 {{ $extra['bg'] }} rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 {{ $extra['iconColor'] }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        let myChart; // Variable global para la instancia del gráfico

        // Función para renderizar/actualizar el gráfico
        function renderChart(chartData) {
            const ctx = document.getElementById('myChart').getContext('2d');

            // Destruir el gráfico existente si lo hay para evitar duplicados
            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'bar', // Puedes cambiar a 'line', 'pie', etc.
                data: {
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
                        'Octubre', 'Noviembre', 'Diciembre'
                    ],
                    datasets: [{
                        label: chartData.label,
                        data: chartData.data,
                        backgroundColor: chartData.backgroundColor,
                        borderColor: chartData.borderColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Mes'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    }
                }
            });
        }

        // Escuchar el evento de Livewire para actualizar el gráfico
        document.addEventListener('livewire:initialized', () => {
            @this.on('chartDataUpdated', (event) => {
                renderChart(event.chartData);
            });
        });

        // Renderizar el gráfico inicial cuando la página se carga (si hay datos iniciales)
        document.addEventListener('DOMContentLoaded', () => {
            if (@js($chartData)) {
                renderChart(@js($chartData));
            }
        });
    </script>
</div>
