<?php

namespace App\Http\Controllers\Emno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PortafolioController extends Controller
{
    //
    public function mostrarPortafolio()
    {
        // portafolio de proyectos de Emmanuel
        $projects = [
            [
                'title' => 'Sistema de Análisis y Reporte de Afluencia en Estaciones de Metro',
                'description' => 'Desarrollé una aplicación web orientada a la visualización y análisis de la afluencia de pasajeros en estaciones de metro, con capacidad de filtrado por fechas, estaciones, tipos de tarifa y método de pago. La plataforma ofrecía generación de reportes detallados y gráficos interactivos, facilitando la toma de decisiones operativas. Aunque el cliente decidió no continuar con la implementación, el sistema fue funcional con los requerimientos minimos y probado en ambiente de desarrollo.',
                'image' => '',
                'test_users' => ['admin:admin123', 'user:user123'],
                'tools' => ['PHP', 'Laravel', 'MySQL', 'Bootstrap', 'JavaScript', 'Chart.js'],
                'features' => [
                    'Autenticación de usuarios con control de acceso por roles (admin y usuario)',
                    'Filtrado dinámico de reportes por fecha, estación, tipo de tarifa y método de pago',
                    'Generación de reportes en PDF con totales de afluencia y recaudación',
                    'Gráficos interactivos para visualización comparativa entre estaciones',
                    'Optimización de consultas mediante tablas secundarias de resumen actualizadas en paralelo',
                    'Interfaz responsive y accesible basada en Bootstrap'
                ],
                'project_link' => '',
                'video_link' => '',
                'github_link' => '',
                'highlight' => 'El proyecto resolvía una necesidad real de análisis y visualización de datos de movilidad urbana, integrando filtros complejos, visualización de gráficos y consultas optimizadas con Laravel. Aunque quedó como prototipo, demostró mi capacidad para desarrollar soluciones escalables y listas para ambientes productivos.'
            ],
            [
                'title' => 'Modelo Predictivo para Mortalidad por Diabetes en México usando Árboles de Decisión con Pesos de Clase',
                'description' => 'Proyecto de investigación doctoral que desarrolló un modelo de aprendizaje automático para predecir el riesgo de muerte en pacientes con diabetes en México. Utilizando árboles de decisión con ajuste de clases desbalanceadas, se analizaron variables demográficas, socioeconómicas y de acceso a salud para detectar pacientes en riesgo con una precisión del 99.98%. La investigación destaca el valor del modelado interpretable y escalable para la toma de decisiones en salud pública.',
                'image' => '',
                'test_users' => [],
                'tools' => ['Python', 'Scikit-learn', 'Pandas', 'Matplotlib', 'Jupyter Notebooks', 'CSV', 'Government Open Data (INEGI, Secretaría de Salud)'],
                'features' => [
                    'Modelo predictivo basado en árboles de decisión con ajuste por clases desbalanceadas',
                    'Precisión del 99.98%, recall del 100% y F1-score de 0.96',
                    'Procesamiento y limpieza de datos públicos nacionales sobre mortalidad por diabetes',
                    'Análisis de variables críticas como edad, educación, acceso a salud y nivel económico',
                    'Visualización de resultados e interpretabilidad para uso por profesionales de salud',
                    'Propuesta metodológica replicable en otras enfermedades crónicas y contextos de salud pública'
                ],
                'project_link' => '',
                'video_link' => '',
                'github_link' => '',
                'highlight' => 'Este proyecto propone una solución real y escalable para reducir la mortalidad por diabetes en México mediante inteligencia artificial interpretable. Su precisión, capacidad para abordar datos desbalanceados y enfoque en variables sociales lo convierten en una herramienta potente para la toma de decisiones clínicas y políticas de salud.'
            ],
            [
                'title' => 'Optimización de Estrategias de Prevención de Drogadicción en México con Machine Learning',
                'description' => 'Proyecto de investigación orientado a la aplicación de ciencia de datos y aprendizaje automático para mejorar las estrategias de prevención de drogadicción en los estados del centro de México. Utilizando datos abiertos gubernamentales, análisis geoespacial y el algoritmo k-means, se identificaron patrones de consumo, zonas vulnerables y perfiles de riesgo. Este enfoque predictivo permitió optimizar la asignación de recursos y proponer campañas preventivas más efectivas y focalizadas.',
                'image' => '',
                'test_users' => [],
                'tools' => ['Python', 'Pandas', 'Scikit-learn', 'Matplotlib', 'K-means', 'CSV', 'Open Government Data'],
                'features' => [
                    'Análisis descriptivo y segmentación por edad, sexo, escolaridad y tipo de sustancia',
                    'Visualización geográfica del consumo de sustancias por entidad federativa',
                    'Identificación de perfiles de riesgo con clustering no supervisado (k-means)',
                    'Procesamiento y limpieza de datos reales sobre consumo de drogas en México',
                    'Extracción de conclusiones accionables para campañas de prevención basadas en datos',
                    'Propuesta de expansión futura con modelos como Random Forest y XGBoost'
                ],
                'project_link' => '',
                'video_link' => '',
                'github_link' => '',
                'highlight' => 'Este proyecto demostró cómo técnicas de Machine Learning aplicadas a datos sociodemográficos y de salud pública pueden transformar la forma en que se diseñan las estrategias de prevención de adicciones, enfocando los recursos donde más se necesitan y mejorando la efectividad de las intervenciones sociales.'
            ],
            [
                'title' => 'Administrador de Partidas y Personajes de Juegos de Rol (Godot + Django REST)',
                'description' => 'Aplicación multiplataforma desarrollada para la gestión avanzada de personajes y campañas en juegos de rol. Permite a narradores y jugadores organizar múltiples partidas, controlar el acceso y realizar cálculos automáticos sobre estadísticas, combate y progresión. Desarrollado con Godot para la interfaz multiplataforma y Django REST para la lógica del servidor, incluye autenticación avanzada y reglas personalizadas de juego.',
                'image' => '',
                'test_users' => [],
                'tools' => ['Godot Engine', 'Django', 'Django REST Framework', 'Python', 'GDScript', 'SQLite/PostgreSQL', 'JWT Auth', 'API Key'],
                'features' => [
                    'Gestión de múltiples campañas de rol con separación de personajes por partida',
                    'Roles diferenciados: narradores con control total, jugadores con acceso limitado a sus personajes',
                    'Sistema de autenticación robusto (Auth, JWT, API Key)',
                    'Restricciones dinámicas: subida de nivel solo con experiencia suficiente, distribución de puntos limitada',
                    'Gestión completa de personajes: estadísticas, habilidades (con catálogo), equipo, inventario, clases',
                    'Cálculo automático de daño, gasto de maná, estrés y otras mecánicas de combate y magia',
                    'Cambio de clase y evolución de personaje con validaciones integradas',
                    'Sincronización cliente-servidor entre Godot y Django REST API',
                    'Exportación multiplataforma: ejecutable para escritorio y móvil (Android)'
                ],
                'project_link' => '',
                'video_link' => '',
                'github_link' => '',
                'highlight' => 'Este proyecto combina diseño de sistemas de juego con desarrollo full stack, integrando una arquitectura cliente-servidor Godot+Django para gestionar personajes de rol con validaciones complejas, control de permisos y cálculos dinámicos. Representa una solución sólida y escalable para juegos personalizados con narrativa dirigida y evolución de personajes en campañas independientes.'
            ]

        ];

        return view('emno.portafolio', [
            'projects' => $projects,
            'userName' => 'Emmanuel',
            'email' => 'emmanuelgoga92@gmail.com'
        ]);
    }
}
