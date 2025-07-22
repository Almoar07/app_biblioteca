<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/865ca92527.js" crossorigin="anonymous"></script>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <a href="/" wire:navigate>
                <x-application-logo-horizontal class="w-full h-28 fill-current text-blue-800 dark:text-white" />
            </a>
        </div>

        <div class="flex justify-center mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md sm:rounded-lg">
            {{ $slot }}
        </div>

        {{-- Footer datos de la institución --}}
        <div class="mt-10 text-center text-gray-600 text-sm w-full max-w-xl">
            <div class="inline-flex items-center justify-center w-full">
                <div class="inline-flex items-center justify-center w-full">
                    <hr class="w-64 h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

                </div>
            </div>
            <div class="mb-4">
                <img src="{{ asset('logos/institucion.png') }}" alt="Insignia Liceo Bicentenario Óscar Castro Zúñiga"
                    class="mx-auto max-w-[300px] h-auto mb-2">
            </div>
            <p class="mb-1">Agustin Almarza #410, Rancagua</p>
            <p class="mb-1">Correo: <a href="mailto:oscar.castro@cormun.cl"
                    class="text-blue-600 hover:underline">oscar.castro@cormun.cl</a></p>
            <p class="mb-1">Teléfono: <a href="tel:+56722230648" class="text-blue-600 hover:underline">722230648</a>
            </p>
            <p class="font-bold mt-2 mb-10">Liceo Bicentenario Óscar Castro Zúñiga</p>
            <div class="inline-flex items-center justify-center w-full">
                <hr class="w-64 h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
                <span class="absolute px-3 font-medium -translate-x-1/2 bg-gray-100 left-1/2 dark:bg-gray-900">
                    <p>Desarrollado
                        por</p>
                </span>
            </div>
            <p>Alejandro Moya</p>
            <i class="fa-brands fa-linkedin"></i>

            <a href="https://www.linkedin.com/in/almoar/" class="text-blue-600 hover:underline"
                target="_blank">LinkedIn</a>
        </div>
    </div>

</body>


</html>
