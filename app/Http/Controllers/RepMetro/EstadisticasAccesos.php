<?php

namespace App\Http\Controllers\RepMetro;

use App\Http\Controllers\Controller;
use App\Models\RepMetro\RepMetroEmpleado;
use App\Models\RepMetro\RepMetroEstacion;
use App\Models\RepMetro\RepMetroTipoPago;
use App\Models\RepMetro\RepMetroFrecuenciaAcceso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EstadisticasAccesos extends Controller
{
    /**
     * Display the QR code generation page.
     *
     * @return \Illuminate\View\View
     */
    public function mostrarReporte(Request $request)
    {
        // obtenemos al usuario 
        $UsuarioId = Auth::id();

        // obtenemos al empleado 
        $empleado = RepMetroEmpleado::with(['usuario', 'departamento', 'puesto'])
            ->where('id_usuario', $UsuarioId)
            ->first();

        // 1. Obtener datos para los filtros
        $estaciones = RepMetroEstacion::orderBy('nombre')->get();
        $tiposPago = RepMetroTipoPago::orderBy('nombre')->get();

        // 2. Obtener períodos disponibles
        $periodos = $this->obtenerPeriodosDisponibles();

        // 3. Aplicar filtros y obtener datos
        $filtros = $this->procesarFiltros($request);
        $query = $this->construirQuery($filtros);

        // 4. Obtener todos los datos para JavaScript (sin paginación)
        $todosLosDatos = $this->obtenerDatosParaJS($query);

        // 5. Datos paginados para la tabla inicial
        $datosPaginados = $query->paginate(15);

        // 6. Calcular métricas del resumen
        $resumen = $this->calcularResumen($query);

        // 7. Preparar datos para gráficos
        $datosGraficos = $this->prepararDatosGraficos($query);

        // 8. Textos descriptivos
        $textos = $this->generarTextos($filtros, $resumen);

        return view('RepMetro.reporte', [
            // Datos de empleado
            'empleado' => $empleado->usuario->getFullNameAttribute(),
            'departamento' => $empleado->departamento->nombre,
            'puesto' => $empleado->puesto->nombre,

            // Datos para filtros
            'aniosDisponibles' => $periodos['anios'],
            'mesesDisponibles' => $periodos['meses'],
            'estaciones' => $estaciones,
            'tiposPago' => $tiposPago,

            // Datos para JavaScript (filtrado dinámico)
            'datosCompletos' => $todosLosDatos,

            // Datos para tabla inicial
            'datosPaginados' => $datosPaginados,

            // Datos del resumen
            'resumen' => $resumen,

            // Datos para gráficos
            'graficos' => $datosGraficos,

            // Textos descriptivos
            'textos' => $textos,

            // Filtros aplicados
            'filtros' => $filtros
        ]);
    }

    /**
     * AJAX: Aplicar filtros dinámicamente
     */
    public function aplicarFiltros(Request $request)
    {
        try {
            // Validar entrada
            $request->validate([
                'anio' => 'nullable|string',
                'mes' => 'nullable|string',
                'estacion' => 'nullable|string',
                'tipo_pago' => 'nullable|string'
            ]);

            // Procesar filtros
            $filtros = $this->procesarFiltros($request);
            $query = $this->construirQuery($filtros);

            // Obtener datos filtrados
            $datosFiltrados = $this->obtenerDatosParaJS($query);

            // Calcular resumen
            $resumen = $this->calcularResumen($query);

            // Preparar datos para gráficos
            $datosGraficos = $this->prepararDatosGraficos($query);

            // Generar textos
            $textos = $this->generarTextos($filtros, $resumen);

            return response()->json([
                'success' => true,
                'data' => $datosFiltrados,
                'resumen' => $resumen,
                'graficos' => $datosGraficos,
                'textos' => $textos,
                'pagination' => [
                    'total' => count($datosFiltrados),
                    'filtered' => true
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al aplicar filtros: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX: Exportar datos a CSV
     */
    public function exportarCSV(Request $request)
    {
        try {
            $filtros = $this->procesarFiltros($request);
            $query = $this->construirQuery($filtros);
            $datos = $this->obtenerDatosParaJS($query);

            if (empty($datos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay datos para exportar'
                ], 400);
            }

            // Generar CSV
            $csvContent = $this->generarCSV($datos);
            $nombreArchivo = $this->generarNombreArchivo($filtros);

            return response($csvContent)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', "attachment; filename=\"{$nombreArchivo}\"")
                ->header('Content-Encoding', 'UTF-8');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================== MÉTODOS PRIVADOS ==================

    private function obtenerPeriodosDisponibles()
    {
        $periodos = RepMetroFrecuenciaAcceso::selectRaw('YEAR(periodo) as anio, MONTH(periodo) as mes')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get();

        return [
            'anios' => $periodos->pluck('anio')->unique()->values(),
            'meses' => collect(range(1, 12)) // Todos los meses
        ];
    }

    private function procesarFiltros(Request $request)
    {
        return [
            'anio' => $request->input('anio', 'all'),
            'mes' => $request->input('mes', 'all'),
            'estacion' => $request->input('estacion', 'all'),
            'tipo_pago' => $request->input('tipo_pago', 'all')
        ];
    }

    private function construirQuery($filtros)
    {
        $query = RepMetroFrecuenciaAcceso::with(['estacion', 'tipoPago', 'tarifa']);

        if ($filtros['anio'] !== 'all') {
            $query->whereYear('periodo', $filtros['anio']);
        }

        if ($filtros['mes'] !== 'all') {
            $query->whereMonth('periodo', $filtros['mes']);
        }

        if ($filtros['estacion'] !== 'all') {
            $query->where('id_estacion', $filtros['estacion']);
        }

        if ($filtros['tipo_pago'] !== 'all') {
            $query->where('id_tipo_pago', $filtros['tipo_pago']);
        }

        return $query->orderBy('periodo', 'desc');
    }

    private function obtenerDatosParaJS($query)
    {
        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'anio' => $item->periodo->year,
                'mes' => $item->periodo->month,
                'periodo' => $item->periodo->format('F Y'),
                'estacion_id' => $item->id_estacion,
                'estacion_nombre' => $item->estacion->nombre,
                'tipo_pago_id' => $item->id_tipo_pago,
                'tipo_pago_nombre' => $item->tipoPago->nombre,
                'personas' => $item->cantidad,
                'cantidad' => $item->cantidad,
                'tarifa' => $item->tarifa->importe,
                'recaudacion' => $item->cantidad * $item->tarifa->importe,
                'created_at' => $item->created_at->format('Y-m-d H:i:s')
            ];
        })->toArray();
    }

    private function calcularResumen($query)
    {
        $datos = $query->get();

        $totalPersonas = $datos->sum('cantidad');
        $totalRecaudado = $datos->sum(function ($item) {
            return $item->cantidad * $item->tarifa->importe;
        });

        // Calcular promedio mensual
        $datosMensuales = $datos->groupBy(function ($item) {
            return $item->periodo->format('Y-m');
        });

        $promedioMensual = $datosMensuales->count() > 0
            ? round($totalPersonas / $datosMensuales->count())
            : 0;

        return [
            'totalPersonas' => $totalPersonas,
            'totalRecaudado' => $totalRecaudado,
            'promedioMensual' => $promedioMensual,
            'tarifaPromedio' => $totalPersonas > 0 ? $totalRecaudado / $totalPersonas : 0
        ];
    }

    private function prepararDatosGraficos($query)
    {
        $datos = $query->get();

        // Gráfico por estaciones
        $estaciones = $datos->groupBy('estacion.nombre')->map(function ($group, $nombre) {
            return [
                'nombre' => $nombre,
                'personas' => $group->sum('cantidad')
            ];
        })->sortByDesc('personas')->values();

        // Gráfico por tipos de pago
        $tiposPago = $datos->groupBy('tipoPago.nombre')->map(function ($group, $nombre) {
            return [
                'nombre' => $nombre,
                'personas' => $group->sum('cantidad')
            ];
        })->sortByDesc('personas')->values();

        return [
            'estaciones' => [
                'labels' => $estaciones->pluck('nombre')->toArray(),
                'data' => $estaciones->pluck('personas')->toArray()
            ],
            'tiposPago' => [
                'labels' => $tiposPago->pluck('nombre')->toArray(),
                'data' => $tiposPago->pluck('personas')->toArray()
            ]
        ];
    }

    private function generarTextos($filtros, $resumen)
    {
        $nombresMeses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        // Texto del período
        $periodo = '';
        if ($filtros['anio'] === 'all' && $filtros['mes'] === 'all') {
            $periodo = '<strong>Todos los períodos</strong>';
        } elseif ($filtros['anio'] !== 'all' && $filtros['mes'] === 'all') {
            $periodo = "<strong>Año completo {$filtros['anio']}</strong>";
        } elseif ($filtros['mes'] !== 'all') {
            $nombreMes = $nombresMeses[intval($filtros['mes'])] ?? "Mes {$filtros['mes']}";
            $anio = $filtros['anio'] !== 'all' ? $filtros['anio'] : 'todos los años';
            $periodo = "<strong>{$nombreMes} {$anio}</strong>";
        }

        // Texto de tarifas
        $tarifas = '$' . number_format($resumen['tarifaPromedio'], 2) . ' por acceso (promedio)';

        return [
            'periodo' => $periodo,
            'tarifas' => $tarifas
        ];
    }

    private function generarCSV($datos)
    {
        $encabezados = [
            'Período',
            'Año',
            'Mes',
            'Estación',
            'Tipo de Pago',
            'Personas',
            'Tarifa',
            'Recaudación'
        ];

        $filas = [];
        $filas[] = implode(',', $encabezados);

        foreach ($datos as $item) {
            $filas[] = implode(',', [
                '"' . $item['periodo'] . '"',
                $item['anio'],
                $item['mes'],
                '"' . $item['estacion_nombre'] . '"',
                '"' . $item['tipo_pago_nombre'] . '"',
                $item['personas'],
                $item['tarifa'],
                $item['recaudacion']
            ]);
        }

        return "\xEF\xBB\xBF" . implode("\n", $filas); // BOM para UTF-8
    }

    private function generarNombreArchivo($filtros)
    {
        $nombre = 'reporte_metro';

        if ($filtros['anio'] !== 'all') {
            $nombre .= "_{$filtros['anio']}";
        }

        if ($filtros['mes'] !== 'all') {
            $nombresMeses = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            ];
            $nombre .= "_{$nombresMeses[$filtros['mes']]}";
        }

        return $nombre . '_' . date('Y-m-d') . '.csv';
    }
}
