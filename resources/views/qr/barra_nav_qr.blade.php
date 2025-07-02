<!-- Barra de navegación -->
<nav id="navbar" class="navbar">
    <div class="nav-container">
        <a href="#" class="nav-logo">{{ $privada_nombre ?? '' }}</a>
        <!-- Un solo botón de hamburguesa -->
        <button class="nav-toggle" aria-label="Menú">
            <i class="fas fa-bars hamburger-icon"></i>
        </button>
        <div class="nav-menu">
            <a href="{{route('qr.generar') }}" class="nav-link">Generar Qr</a>
            <a href="{{route('qr.consultar') }}" class="nav-link">Consultar Qr</a>
            @auth
            <form action="{{route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Cerrar sesion</button>
            </form>
            @endauth
        </div>
    </div>
</nav>

<!-- Funciones de control de la barra -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('navbar');
        const navLinks = document.querySelectorAll('.nav-link');
        const navToggles = document.querySelectorAll('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');

        let lastScrollY = window.scrollY;

        // Controlar visibilidad de la barra al hacer scroll
        window.addEventListener('scroll', function() {
            const currentScrollY = window.scrollY;

            // Ocultar al bajar, mostrar al subir
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                // Scroll hacia abajo
                navbar.classList.add('hidden');
            } else {
                // Scroll hacia arriba
                navbar.classList.remove('hidden');
            }

            lastScrollY = currentScrollY;

            // Resaltar enlace activo
            highlightActiveLink();
        });

        // Toggle del menú móvil para todos los botones
        navToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                // this.classList.toggle('open');
                this.querySelector('.hamburger-icon').classList.toggle('fa-times');
            });
        });

        // Mostrar la barra si el mouse se acerca al borde superior
        document.addEventListener('mousemove', function(e) {
            if (e.clientY < 40) {
                navbar.classList.remove('hidden');
            }
        });

        // Cerrar menú al hacer clic en un enlace (móvil)
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
                document.querySelector('.nav-toggle').classList.remove('open');

                // Scroll suave
                const targetId = this.getAttribute('href');
                if (targetId.startsWith('#')) {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 70,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // Resaltar enlace activo
        function highlightActiveLink() {
            const sections = document.querySelectorAll('section');
            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;

                if (window.scrollY >= sectionTop - sectionHeight / 3) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                if (window.location.href.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        // Inicializar el estado activo
        highlightActiveLink();
    });
</script>
@endpush