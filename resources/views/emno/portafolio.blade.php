<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portafolio de Emmanuel</title>

    <!-- Iconos de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('css/portafolio.css') }}?v=1.0.1" rel="stylesheet">

</head>

<body>

    <!-- Incluir la barra de navegación -->
    @include('emno.barra_nav')
    <!-- Contenido del portafolio -->
    <header>
        <div class="container">
            <h1>Portafolio de Proyectos</h1>
            <p>{{ $userName }} - Desarrollador de Software</p>
        </div>
    </header>

    <div class="container">
        <div class="projects-grid">
            @foreach($projects as $project)
            <div class="project-card">

                <div class="project-content">
                    <h2 class="project-title">{{ $project['title'] }}</h2>
                    @if($project['image'])
                    <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}" class="project-image">
                    @else
                    <div class="project-image" style="background: #eee; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-code" style="font-size: 3rem; color: #ccc;"></i>
                    </div>
                    @endif
                    <p class="project-description">{{ $project['description'] }}</p>

                    @if(isset($project['highlight']))
                    <div class="project-highlight">
                        <strong><i class="fas fa-star"></i> Destacado:</strong> {{ $project['highlight'] }}
                    </div>
                    @endif

                    <div class="project-section">
                        <h3 class="section-title"><i class="fas fa-users"></i> Usuarios de prueba</h3>
                        <div class="test-users">
                            @foreach($project['test_users'] as $user)
                            <span class="test-user">{{ $user }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="project-section">
                        <h3 class="section-title"><i class="fas fa-tools"></i> Tecnologías utilizadas</h3>
                        <div class="tools-list">
                            @foreach($project['tools'] as $tool)
                            <span class="tool">{{ $tool }}</span>
                            @endforeach
                        </div>
                    </div>

                    @if(isset($project['features']))
                    <div class="project-section">
                        <h3 class="section-title"><i class="fas fa-list-check"></i> Funcionalidades clave</h3>
                        <ul style="padding-left: 20px; font-size: 0.9rem;">
                            @foreach($project['features'] as $feature)
                            <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="project-links">
                        @if($project['project_link'])
                        <a href="{{ $project['project_link'] }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> Ver proyecto
                        </a>
                        @endif

                        @if($project['video_link'])
                        <a href="{{ $project['video_link'] }}" target="_blank" class="btn btn-secondary">
                            <i class="fas fa-video"></i> Video demo
                        </a>
                        @endif

                        @if($project['github_link'])
                        <a href="{{ $project['github_link'] }}" target="_blank" class="btn btn-accent">
                            <i class="fab fa-github"></i> Código fuente
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <footer>
        <div class="container">
            <p>© {{ date('Y') }} {{ $userName }}. Todos los derechos reservados.</p>
            <p>
                <a href="mailto:{{ $email }}" style="color: white; text-decoration: none;">
                    <i class="fas fa-envelope"></i> {{ $email }}
                </a>
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>