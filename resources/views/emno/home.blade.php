<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - Emmanuel Gonzalez García</title>
    <style>
        /* Estilos para el curriculum */
        :root {
            --primary-color: #1a2a6c;
            --accent-color: #3498db;
            --background-color: #f4f6f9;
            --card-bg: #ffffff;
            --header-text: #ffffff;
            --text-color: #333;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.8;
            color: var(--text-color);
            max-width: 960px;
            margin: 0 auto;
            padding: 100px 20px 40px 20px;
            background-color: var(--background-color);
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 30px 20px;
            background-color: var(--primary-color);
            color: var(--header-text);
            border-radius: 12px;
        }

        header#presentacion {
            margin-top: 30px;
            text-align: center;
        }


        h1 {
            font-size: 2.4em;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: var(--header-text);
        }

        section {
            background-color: var(--card-bg);
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        h2 {
            font-size: 1.4em;
            color: var(--primary-color);
            border-left: 5px solid var(--accent-color);
            padding-left: 10px;
            margin-bottom: 15px;
            text-align: left;
        }

        .contact-info {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .contact-info div {
            flex: 1 1 45%;
            margin-bottom: 8px;
        }

        .job-title {
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            color: var(--primary-color);
        }

        ul {
            padding-left: 20px;
            list-style-type: disc;
        }

        li {
            margin-bottom: 6px;
        }

        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .skill-category {
            flex: 1 1 30%;
        }

        .project-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .project {
            background-color: var(--card-bg);
            box-shadow: var(--shadow);
            padding: 15px;
            border-radius: 8px;
            flex: 1 1 calc(50% - 20px);
        }

        .project-title {
            font-weight: bold;
            color: var(--accent-color);
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            body {
                padding: 90px 5px 20px 5px;
            }

            .contact-info div,
            .skill-category,
            .project {
                flex: 1 1 100%;
            }
        }

        /* Estilos de la barra de navegacion superior */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #1a2a6c;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background-color 0.3s ease;
            z-index: 1000;
            transform: translateY(0);
            color: white;
        }

        .navbar.hidden {
            transform: translateY(-100%);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            gap: 1.5rem;
        }

        .nav-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            position: relative;
            padding: 0.5rem 0;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #667eea;
            transition: width 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #667eea;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 40px;
            width: 40px;
            z-index: 1100;
        }


        .hamburger {
            width: 28px;
            height: 4px;
            background: #fff;
            margin: 4px 0;
            border-radius: 2px;
            transition: all 0.3s;
            display: block;
        }

        .nav-toggle.open .hamburger:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .nav-toggle.open .hamburger:nth-child(2) {
            opacity: 0;
        }

        .nav-toggle.open .hamburger:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }


        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            font-family: inherit;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #3a2368;
            transform: translateY(-2px);
        }


        /* Estilos responsive */
        @media (max-width: 768px) {
            .nav-menu {
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background-color: #1a2a6c;
                flex-direction: column;
                align-items: center;
                padding: 1rem 0;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                transform: translateY(-150%);
                transition: transform 0.3s ease;
                z-index: 999;
                gap: 0;
            }

            .nav-menu.active {
                transform: translateY(0);
            }

            .nav-toggle {
                display: flex;
            }

            .nav-link {
                padding: 1rem;
                width: 100%;
                text-align: center;
                border-bottom: 1px solid #334;
            }

            .nav-container {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <!-- Incluir la barra de navegación -->
    @include('emno.barra_nav')

    <!-- Contenido del CV -->
    <header id="presentacion">
        <h1>Emmanuel Gonzalez García</h1>
        <p class="subtitle">Desarrollador de software | Doctor en Sistemas Computacionales | Inteligencia Artificial · Análisis de Datos · Aplicaciones Web</p>
        <p style="text-align: justify;">
            Soy desarrollador de software con formación académica sólida y enfoque analítico. Finalicé el doctorado en Sistemas Computacionales (en proceso de titulación), con especialización en desarrollo de aplicaciones web y móviles, análisis de datos e inteligencia artificial.
            <br>
            Combino mi experiencia técnica con la docencia en niveles medio superior y superior, impartiendo materias como simulación de procesos, desarrollo de sistemas e IA. También trabajo como freelancer desarrollando soluciones tecnológicas para pequeñas y medianas empresas, lo que me mantiene actualizado en herramientas y lenguajes como Python, Django, Laravel y Angular.
            <br>
            Mi formación en biotecnología complementa mi perfil con una visión integral para resolver problemas complejos, desde el modelado de datos hasta la implementación de soluciones funcionales y escalables.
        </p>
    </header>

    <section id="contacto">
        <h2>Información de Contacto</h2>
        <div class="contact-info">
            <div><strong>Teléfono:</strong> 8112512469</div>
            <div><strong>Email:</strong> emmanuelgoga92@gmail.com</div>
            <div><strong>LinkedIn:</strong> LinkedIn Profile</div>
            <div><strong>Ubicación:</strong> Calle del Peñon #602 , Lomas Jardines, García, Nuevo León</div>
        </div>
    </section>

    <section id="estudios">
        <h2>Formación Académica</h2>
        <div class="job">
            <div class="job-title">
                <span><strong>Doctorado en Sistemas Computacionales</strong></span>
                <span>UNISUR - Nov 2023 – Feb 2025 (Título en trámite)</span>
            </div>
            <!-- <p>Promedio: 9.93</p> -->
        </div>

        <div class="job">
            <div class="job-title">
                <span><strong>Maestría en Educación</strong></span>
                <span>Universidad CNCI - Agosto 2020 - Agosto 2022</span>
            </div>
            <!-- <p>Promedio: 9.9</p> -->
        </div>

        <div class="job">
            <div class="job-title">
                <span><strong>Licenciatura en Biotecnología</strong></span>
                <span>Universidad Autónoma de Querétaro - Agosto 2010 – Diciembre 2014</span>
            </div>
            <!-- <p>Promedio: 7.8</p> -->
        </div>
    </section>

    <section id="experiencia">
        <h2>Experiencia Laboral</h2>
        <div class="job">
            <div class="job-title">
                <span><strong>Freelancer</strong></span>
                <span>AGOSTO 2024 – ACTUALMENTE</span>
            </div>
            <p>Experiencia en el desarrollo de diversos sistemas para pequeñas y medianas empresas por encargo.</p>
        </div>

        <div class="job">
            <div class="job-title">
                <span><strong>UPG</strong></span>
                <span>FEBRERO 2022 – ACTUALMENTE</span>
            </div>
            <p>Docente impartiendo diferentes clases de especialidad a la carrera de ing. en sistemas e ing. industrial.</p>
        </div>

        <div class="job">
            <div class="job-title">
                <span><strong>CONALEP</strong></span>
                <span>ENERO 2016 – JULIO 2023</span>
            </div>
            <p>Docente impartiendo diferentes clases de especialidad a la carrera de técnico informático.</p>
        </div>
    </section>

    <section id="habilidades">
        <h2>Habilidades Técnicas</h2>
        <div class="skills">
            <div class="skill-category">
                <strong>Lenguajes de Programación:</strong>
                <ul>
                    <li>Python</li>
                    <li>Java</li>
                    <li>C#</li>
                    <li>GDscript</li>
                    <li>PHP</li>
                    <li>SQL</li>
                    <li>VBA</li>
                </ul>
            </div>

            <div class="skill-category">
                <strong>Frameworks:</strong>
                <ul>
                    <li>Django</li>
                    <li>Laravel</li>
                    <li>Angular</li>
                    <li>Bootstrap</li>
                </ul>
            </div>

            <div class="skill-category">
                <strong>Herramientas:</strong>
                <ul>
                    <li>Git</li>
                    <li>Visual Studio Code</li>
                    <li>Docker</li>
                    <li>Pandas</li>
                    <li>NumPy</li>
                    <li>Matplotlib</li>
                </ul>
            </div>

            <div class="skill-category">
                <strong>Análisis de Datos:</strong>
                <ul>
                    <li>Machine Learning (K-means, Kohonen, árboles de decisión, redes neuronales)</li>
                    <li>Tableau</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="proyectos">
        <h2>Proyectos Relevantes</h2>
        <div class="project">
            <div class="project-title">Aplicación Móvil y Web para Ferretería</div>
            <p>Desarrollo de una aplicación para el manejo de inventario y ventas en tres sucursales.</p>
            <p><strong>Tecnologías:</strong> Java (Android), Django, microservicios.</p>
        </div>

        <div class="project">
            <div class="project-title">Aplicación de Juego de Rol</div>
            <p>Creación de una aplicación móvil y de escritorio para el manejo de información de un juego de rol.</p>
            <p><strong>Tecnologías:</strong> GDscript, Django, API.</p>
        </div>

        <div class="project">
            <div class="project-title">Macros en Excel para Base de Datos</div>
            <p>Automatización del manejo de una base de datos de Access para un almacén.</p>
            <p><strong>Tecnologías:</strong> VBA.</p>
        </div>

        <div class="project">
            <div class="project-title">Aplicación de Escritorio para Escuela</div>
            <p>Desarrollo de una aplicación de consola para el manejo de escolares.</p>
            <p><strong>Tecnologías:</strong> Python.</p>
        </div>

        <div class="project">
            <div class="project-title">Aplicación Web para Almacén</div>
            <p>Creación de una aplicación web para el manejo de inventario.</p>
            <p><strong>Tecnologías:</strong> Laravel.</p>
        </div>
    </section>
    <section id="habilidades-blandas">
        <h2>Habilidades Blandas</h2>
        <h3 class="project-title">Idiomas</h3>
        <ul>
            <li>Español: Nativo</li>
            <li>Inglés: Intermedio (B1)</li>
        </ul>
    </section>

    @stack('scripts')
</body>

</html>