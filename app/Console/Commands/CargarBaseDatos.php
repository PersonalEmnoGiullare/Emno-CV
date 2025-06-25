<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CargarBaseDatos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cargar-base-datos {tablas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que se utilizara para reestablecer los registros que se encuentran dentro de la base de datos mediante archivos de tipo JSON.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // recuperrar las tablas a cargar
        $tablas = explode(',', $this->argument('tablas'));
        // presentar mensaje en consola
        $this->info('Cargando la base de datos desde el respaldo en servidor...');
        $this->info('');
        $this->info('');

        // cargar los datos del empleado
        if (in_array('todos', $tablas)) {
            $this->cargarUsuarios();
        } else {
            if (in_array('usuarios', $tablas)) {
                $this->cargarUsuarios();
            }
        }
    }

    //-------------------------------------- Funciones de carga

    /**
     * Método genérico para cargar datos desde JSON
     *
     * @param string $rutaArchivo Ruta del archivo JSON
     * @param string $nombreTabla Nombre de la tabla destino
     * @param array $config Configuración especial:
     *   - 'campos' => Mapeo de campos [campo_json => campo_bd]
     *   - 'relaciones' => Campos que requieren búsqueda en otras tablas
     *   - 'transformaciones' => Callbacks para transformar valores
     *   - 'nombreRegistro' => Campo a usar para mensajes (ej: 'name', 'nombre')
     */
    public function cargarUsuarios()
    {
        $config = [
            'campos' => [
                'name' => 'name',
                'last_name' => 'last_name',
                'mothers_last_name' => 'mothers_last_name',
                'username' => 'username',
                'rol' => 'rol',
                'email' => 'email',
                'password' => 'password'
            ],
            'transformaciones' => [
                'password' => function ($pass) {
                    return Hash::make($pass);
                },
                'email_verified_at' => function () {
                    return now();
                },
                'remember_token' => function () {
                    return Str::random(10);
                }
            ],
            'nombreRegistro' => 'username'
        ];

        $this->cargarDatosGenerico('recuperacionDB/usuario.json', 'users', $config);
    }


    //-------------------------- Funciones generales para cargar datos
    public function cargarDatosGenerico($rutaArchivo, $nombreTabla, $config = [])
    {
        if (!Storage::exists($rutaArchivo)) {
            $this->error("El archivo $rutaArchivo no existe.");
            return false;
        }

        $datos = json_decode(Storage::get($rutaArchivo), true);

        // Si el JSON tiene estructura de múltiples tablas (como datos_basicos.json)
        if (isset($config['multiples_tablas'])) {
            foreach ($datos as $tabla => $registros) {
                if (isset($config['subconfigs'][$tabla])) {
                    $this->procesarRegistros(
                        $registros,
                        $tabla,
                        $config['subconfigs'][$tabla]
                    );
                }
            }
            return true;
        }

        return $this->procesarRegistros($datos, $nombreTabla, $config);
    }

    protected function procesarRegistros($registros, $nombreTabla, $config)
    {
        $total = 0;
        $exitosos = 0;

        foreach ($registros as $registro) {
            $total++;

            try {
                $datosInsertar = $this->mapearDatos($registro, $config);

                DB::table($nombreTabla)->insert($datosInsertar);

                $nombreCampo = $config['nombreRegistro'] ?? 'nombre';
                $this->info("Registro {$registro[$nombreCampo]} cargado correctamente en $nombreTabla");
                $exitosos++;
            } catch (\Exception $e) {
                $nombre = $registro[$config['nombreRegistro'] ?? 'desconocido'];
                $this->error("Error al cargar $nombre en $nombreTabla: " . $e->getMessage());
            }
        }

        $this->info("\nResumen $nombreTabla: $exitosos/$total registros procesados\n");
        return $exitosos > 0;
    }

    protected function mapearDatos($registro, $config)
    {
        $datos = [];

        // Mapeo simple de campos
        $mapeoCampos = $config['campos'] ?? [];
        foreach ($mapeoCampos as $origen => $destino) {
            $datos[$destino] = $registro[$origen] ?? null;
        }

        // Procesar relaciones
        $relaciones = $config['relaciones'] ?? [];
        foreach ($relaciones as $campo => $configRelacion) {
            $modelo = app($configRelacion['modelo']);
            $valor = $registro[$campo];

            $registroRelacionado = $modelo::where(
                $configRelacion['campo'],
                $valor
            )->first();

            if (!$registroRelacionado) {
                throw new \Exception("No se encontró $campo: $valor");
            }

            $datos[$configRelacion['destino']] = $registroRelacionado->id;
        }

        // Aplicar transformaciones
        $transformaciones = $config['transformaciones'] ?? [];
        foreach ($transformaciones as $campo => $callback) {
            if (isset($datos[$campo])) {
                $datos[$campo] = $callback($datos[$campo]);
            }
        }

        // Campos fijos
        $datos['created_at'] = now();

        return $datos;
    }
}
