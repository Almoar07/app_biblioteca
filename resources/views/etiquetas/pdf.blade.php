<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Etiquetas de ejemplares</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .etiqueta {
            border: 1px dashed #000;
            padding: 10px;
            margin: 5px;
            width: 45%;
            display: inline-block;
            height: 160px;
            vertical-align: top;
        }

        .barcode {
            margin: 10px;
            width: 90%;
        }
    </style>
</head>

<body>
    @foreach ($ejemplares as $ejemplar)
        <div class="etiqueta">
            <strong>{{ $ejemplar->libro->titulo }}</strong><br>
            ISBN: {{ $ejemplar->libro->isbn }}<br>
            Ubicación: {{ $ejemplar->ubicacion_estante }}<br>

            <div class="barcode" style="text-align: center; margin-top: 40px;">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($ejemplar->codigo_barras, 'C128') }}"
                    alt="Código de barras" style="display: block; margin: 0 auto;">
                <div style="margin-top: 4px;">{{ $ejemplar->codigo_barras }}</div>
            </div>
        </div>
    @endforeach
</body>

</html>
