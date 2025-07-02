<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Códigos QR</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/qr/gen_qr.css') }}" rel="stylesheet">
    <link href="{{ asset('css/qr/barra_nav.css') }}" rel="stylesheet">
</head>

<body>
    {{-- Incluir la barra de navegación --}}
    @include('qr.barra_nav_qr')

    {{-- Contenido principal --}}
    <div class="container">
        <h1>Generador de Códigos QR</h1>

        <div class="qr-display">
            <div id="qrPlaceholder" class="qr-placeholder">
                <p>Tu código QR aparecerá aquí</p>
            </div>
            <canvas id="qrCanvas" style="display: none;"></canvas>
        </div>

        <div class="customize">
            <h3>Personalización</h3>

            {{-- se genera la configuracion de forma dinamica con blade --}}
            <div class="customize-options">
                <div hidden>
                    <input type="text" id="residente_id" placeholder="" value="{{ $residente_id ?? '' }}" hidden readonly>
                </div>

                <div hidden>
                    <input type="text" id="privada_id" placeholder="" value="{{ $privada_id ?? '' }}" hidden readonly>
                </div>
                <br>
                <div class="option-group">
                    <label for="qr_invitados">Invitado:</label>
                    <select id="qr_invitados">
                        {{-- se generan los options dinamicamente por medio de un array pasado del controlador y un foreach para recorrer dicho array --}}

                        <option value="">Selecciona un invitado</option>

                        @foreach ($invitados as $invitado)
                        <option value="{{ $invitado->id ?? '' }}"> {{$invitado->alias . ": " . $invitado->nombre_completo }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="option-group">
                    <label for="usos">Cantidad usos:</label>
                    <input type="number" id="usos" placeholder="" value="1" min="1" max="10">
                </div>

                <div class="option-group">
                    <label for="dias_caducar">Dias antes de caducar:</label>
                    <input type="number" id="dias_caducar" placeholder="" value="1" min="1" max="7">
                </div>

            </div>
        </div>

        <div class="qr-controls">
            <button id="generateBtn" class="btn">Generar QR</button>
            <button id="downloadBtn" class="btn" disabled>Descargar QR</button>
        </div>

    </div>

    {{-- agregamos el codigo javascript --}}
    <script src="{{ asset('js/qr/gen_qr.js') }}"></script>
    @stack('scripts')
</body>

</html>