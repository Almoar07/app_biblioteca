{{-- Props para los datos del estudiante y préstamos --}}
@props([
    'studentData' => [
        'nombre' => '',
        'direccion' => '',
        'comuna' => '',
        'curso' => '',
        'rut' => '',
        'telefono' => '',
        'fechaNac' => '',
        'validoHasta' => '',
        'socioNumero' => '',
    ],
    'studentLoans' => [],
])

<div class="container w-full max-w-4xl border border-black p-6 bg-white shadow-lg rounded-lg box-border">
    <!-- Header section -->
    <div class="header flex flex-wrap items-center mb-5 pb-3 border-b border-gray-300">
        <!-- Logo placeholder -->
        <img src="https://placehold.co/60x60/000080/FFFFFF?text=LOGO" alt="Logo Liceo Oscar Castro Z."
            class="h-16 w-16 mr-4 rounded-md flex-shrink-0">
        <!-- School name -->
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900 flex-grow text-center md:text-left">LICEO OSCAR CASTRO Z.
        </h1>
        <!-- Library title -->
        <h2 class="text-lg md:text-xl font-semibold text-blue-900 text-right mt-2 md:mt-0 md:ml-auto w-full md:w-auto">
            BIBLIOTECA</h2>
    </div>

    <!-- Information section (personal details and socio number) -->
    <div class="info-section grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
        <!-- Left information block -->
        <div
            class="info-left border border-black p-4 rounded-lg col-span-1 md:col-span-2 grid grid-cols-2 items-center gap-y-3 gap-x-2">
            <!-- Name field -->
            <label for="nombre" class="font-bold whitespace-nowrap">Nombre:</label>
            <input type="text" id="nombre" aria-label="Nombre del estudiante"
                class="w-full border-none outline-none bg-transparent input-dashed-border px-1 py-0.5"
                value="{{ $studentData['nombre'] ?? '' }}" readonly>

            <!-- Address field -->
            <label for="direccion" class="font-bold whitespace-nowrap">Dirección:</label>
            <input type="text" id="direccion" aria-label="Dirección del estudiante"
                class="w-full border-none outline-none bg-transparent input-dashed-border px-1 py-0.5"
                value="{{ $studentData['direccion'] ?? '' }}" readonly>

            <!-- Comuna field -->
            <label for="comuna" class="font-bold whitespace-nowrap">Comuna:</label>
            <input type="text" id="comuna" aria-label="Comuna del estudiante"
                class="w-full border-none outline-none bg-transparent input-dashed-border px-1 py-0.5"
                value="{{ $studentData['comuna'] ?? '' }}" readonly>

            <!-- Course field -->
            <label for="curso" class="font-bold whitespace-nowrap">Curso:</label>
            <input type="text" id="curso" aria-label="Curso del estudiante"
                class="w-full border-none outline-none bg-transparent input-dashed-border px-1 py-0.5"
                value="{{ $studentData['curso'] ?? '' }}" readonly>

            <!-- RUT field -->
            <label for="rut" class="font-bold whitespace-nowrap">R.U.T.:</label>
            <input type="text" id="rut" aria-label="RUT del estudiante"
                class="w-full border-none outline-none bg-transparent input-dashed-border px-1 py-0.5"
                value="{{ $studentData['rut'] ?? '' }}" readonly>

            <!-- Phone field -->
            <label for="telefono" class="font-bold whitespace-nowrap">Teléfono:</label>
            <input type="text" id="telefono" aria-label="Teléfono del estudiante"
                class="w-full border-none outline-none bg-transparent input-dashed-border px-1 py-0.5"
                value="{{ $studentData['telefono'] ?? '' }}" readonly>

            <!-- Date of Birth field -->
            <label for="fechaNac" class="font-bold whitespace-nowrap">Fecha de Nac.:</label>
            <input type="text" id="fechaNac" aria-label="Fecha de nacimiento del estudiante"
                class="w-full border-none outline-none bg-transparent input-dashed-border px-1 py-0.5"
                value="{{ $studentData['fechaNac'] ?? '' }}" readonly>

            <!-- Valid Until field -->
            <label for="validoHasta" class="font-bold whitespace-nowrap">Válido hasta:</label>
            <input type="text" id="validoHasta" aria-label="Fecha de validez de la tarjeta"
                class="w-full border-none outline-none bg-transparent input-dashed-border px-1 py-0.5"
                value="{{ $studentData['validoHasta'] ?? '' }}" readonly>
        </div>

        <!-- Right information block (Socio N°) -->
        <div class="info-right border border-black p-4 rounded-lg flex flex-col items-center justify-start">
            <p class="font-bold mb-3 text-center">Socio N°</p>
            <!-- Square box for socio number/photo -->
            <div
                class="square-box w-full max-w-[150px] h-32 border border-black bg-white rounded-md flex items-center justify-center text-2xl font-bold">
                {{ $studentData['socioNumero'] ?? '' }}
            </div>
        </div>
    </div>

    <!-- Main table for loan records -->
    <table class="table-section w-full border border-black border-collapse rounded-lg overflow-hidden">
        <thead>
            <tr>
                <th class="border border-black p-3 text-left bg-gray-200 font-bold uppercase text-sm w-1/4">FECHA DE
                    PRESTAMO</th>
                <th class="border border-black p-3 text-left bg-gray-200 font-bold uppercase text-sm w-1/2">TITULO</th>
                <th class="border border-black p-3 text-left bg-gray-200 font-bold uppercase text-sm w-1/4">FECHA DE
                    DEVOLUCION</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($studentLoans as $loan)
                <tr>
                    <td class="border border-black p-2 h-9 bg-gray-50">
                        {{ $loan['fecha_prestamo'] ?? '' }}
                    </td>
                    <td class="border border-black p-2 h-9 bg-gray-50">
                        {{ $loan['titulo'] ?? '' }}
                    </td>
                    <td class="border border-black p-2 h-9 bg-gray-50">
                        {{ $loan['fecha_devolucion_esperada'] ?? '' }}
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
