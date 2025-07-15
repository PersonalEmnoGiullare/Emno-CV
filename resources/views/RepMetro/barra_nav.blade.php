<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg metro-header mb-4">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <!-- Logo de la empresa -->
            <img src="{{ asset('images/rep_metro/logo.png') }}" alt="Logo Empresa"
                style="height: 80px; margin-right: 15px;">
        </a>
        <span class="fw-bold">Sistema de Reportes de Afluencia</span>
        <div class="d-flex align-items-center">
            <!-- Nombre del usuario y área -->
            <span class="text-white me-3 d-none d-md-block">
                <i class="bi bi-person-circle me-1"></i>
                <span class="badge bg-secondary ms-2">
                    {{ $empleado ?? 'Usuario' }} <br>
                    {{ $puesto ?? 'Puesto' }} <br>
                    {{ $departamento ?? 'Departamento' }}
                </span>
            </span>

            <!-- Botón de registro (solo para rol 1 - Admin) -->

            <!-- Botón de cerrar sesión -->
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Cerrar sesion</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
