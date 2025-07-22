<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }} ">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bibliolite') }}@yield('title')</title>

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/livewire-events.js'])
    <script src="https://kit.fontawesome.com/865ca92527.js" crossorigin="anonymous"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @stack('modals')
    @stack('scripts')
    @livewireScripts()
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('copias-creadas', (event) => {
                console.log('Evento "copias-creadas" recibido:', event);
                console.log('Payload del evento:', event); // **Añadido para depurar**

                // Extraemos los datos del payload del evento
                // Asegúrate de que barCodeIDs exista y sea un array. Si no, usa un array vacío por defecto.
                const ids = Array.isArray(event.barCodeIDs) ? event.barCodeIDs : [];
                const cantidad = event.cantidad;
                const isDarkMode = document.documentElement.classList.contains("dark");

                Swal.fire({
                    title: '¡Copias Creadas!',
                    text: `Se han registrado ${cantidad} nuevas copias. ¿Desea generar el PDF con las etiquetas?`,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, generar PDF',
                    cancelButtonText: 'No, gracias',
                    background: isDarkMode ? "#1f2937" : "#f9fafb",
                    color: isDarkMode ? "#f9fafb" : "#111827",
                    confirmButtonColor: isDarkMode ? "#3b82f6" : "#2563eb",
                    cancelButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si no hay IDs, quizás no queramos abrir el PDF o mostrar un mensaje.
                        if (ids.length === 0) {
                            Swal.fire({
                                title: 'Advertencia',
                                text: 'No hay IDs de códigos de barra para generar el PDF.',
                                icon: 'warning',
                                background: isDarkMode ? "#1f2937" : "#f9fafb",
                                color: isDarkMode ? "#f9fafb" : "#111827",
                                confirmButtonColor: isDarkMode ? "#3b82f6" : "#2563eb",
                            });
                            return; // Salir de la función si no hay IDs
                        }

                        const baseUrl = "{{ route('etiquetas.pdf') }}";
                        const url = `${baseUrl}?ids=${ids.join(',')}`;

                        console.log('URL generada para abrir:', url);
                        window.open(url, '_blank');
                    }
                });
            });
        });

        document.addEventListener('alpine:init', () => {
            Alpine.store('ui', {
                showBarCodeSelect: false
            })
        })
    </script>

</body>

</html>
