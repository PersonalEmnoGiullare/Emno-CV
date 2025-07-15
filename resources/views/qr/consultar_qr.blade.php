<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    {{-- Meta tags de autenticación --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(session('api_token'))
    <meta name="api-token" content="{{ session('api_token') }}">
    @endif
    <title>Consulta de Códigos QR</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/qr/barra_nav.css') }}?v=1.0.0" rel="stylesheet">
    <link href="{{ asset('css/qr/consultar_qr.css') }}?v=1.0.0" rel="stylesheet">
</head>

<body>
    {{-- Incluir la barra de navegación --}}
    @include('qr.barra_nav_qr')

    {{-- Contenido principal --}}
    <div class="container">
        <h1>Consulta de Códigos QR Generados</h1>

        <!-- Filtros -->
        <div class="filters">
            <div class="filter-group">
                <label for="status_filter">Estado</label>
                <select id="status_filter">
                    <option value="">Todos</option>
                    <option value="activo">Activo</option>
                    <option value="usado">Usado</option>
                    <option value="expirado">Expirado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="qr_invitados">Invitado:</label>
                <select id="qr_invitados">
                    {{-- se generan los options dinamicamente por medio de un array pasado del controlador y un foreach para recorrer dicho array --}}

                    <option value="">Selecciona un invitado</option>

                    @foreach ($invitados as $invitado)
                    <option value="{{ $invitado->id ?? '' }}"> {{$invitado->alias . ": " . $invitado->nombre_completo }}</option>
                    @endforeach

                </select>
            </div>

            <div class="filter-group">
                <label for="date-filter">Fecha</label>
                <input type="date" id="date-filter">
            </div>

            <div class="filter-actions">
                <button id="apply-filters">Aplicar Filtros</button>
                <button id="reset-filters" class="btn-secondary">Limpiar</button>
            </div>
        </div>

        <!-- Lista de QR -->
        <div class="qr-list-container">
            <div class="qr-list-header">
                <span>Alias Invitado</span>
                <span>Fecha Generación / Expiracion</span>
                <span>Estado</span>
                <span>Acciones</span>
            </div>

            <div class="qr-list" id="qr-list">
                <!-- Ejemplo de item - Esto se generaría dinámicamente con JavaScript -->
                <div class="qr-item">
                    <span class="qr-date" title="2023-11-15 14:30">2023-11-15 14:30</span>
                    <span class="qr-alias" title="Invitado Ejemplo">Invitado Ejemplo</span>
                    <span class="status status-activo">Activo</span>
                    <button class="view-qr-btn" data-qr="ejemplo_codigo_qr">Ver QR</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar QR -->
    <div class="modal" id="qr-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Código QR</h2>
            <div class="modal-qr">
                <canvas id="modal-qr-canvas"></canvas>
            </div>
            <div class="modal-info">
                <p><strong>Alias:</strong> <span id="modal-alias">Invitado Ejemplo</span></p>
                <p><strong>Estado:</strong> <span id="modal-status">Activo</span></p>
                <p><strong>Fecha:</strong> <span id="modal-date">2023-11-15 14:30</span></p>
            </div>
            <div class="modal-actions">
                <button id="download-qr">Descargar QR</button>
                <button class="btn-secondary" id="close-modal-btn">Cerrar</button>
            </div>
        </div>
    </div>

    {{-- agregamos el codigo javascript --}}
    <script src="{{ asset('js/qr/consultar_qr.js') }}?v=1.0.0"></script>
    @stack('scripts')

</body>

</html>