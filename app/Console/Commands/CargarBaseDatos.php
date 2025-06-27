<?php

namespace App\Console\Commands;

use App\Models\Qr\QrCodigo;
use App\Models\Qr\QrDispositivo;
use App\Models\Qr\QrInvitado;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Qr\QrPrivada;
use App\Models\Qr\QrResidente;
use App\Models\Qr\QrVivienda;
use App\Models\User;

use function Psy\debug;

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
            $this->cargarPrivadas();
        } else {
            if (in_array('usuarios', $tablas)) {
                $this->cargarUsuarios();
            }
            if (in_array('qr_todos', $tablas)) {
                $this->cargarPrivadas();
            } else {
                if (in_array('qr_privadas', $tablas)) {
                    $this->cargarPrivadas();
                }
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
     *   -  - para relaciones con llave compuesta, usar:
     *          - primer campo: nombre del campo en la tabla destino
     *          - segundo campo: nombre del modelo a buscar
     *          'campo' => [
     *            'nombre' => 'nombre_privada',
     *            'constructora' => 'constructora'
     *          ],
     *   - 'multiples_tablas' => Si el JSON tiene múltiples tablas
     *   - 'subconfigs' => Configuraciones específicas por tabla
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

    //------------------------------- Funciones de carga de datos para proyecto qr
    public function cargarPrivadas()
    {
        $config = [];

        $config = [
            'multiples_tablas' => true,
            'subconfigs' => [
                'qr_privadas' => [
                    'tabla' => 'qr_privadas',
                    'campos' => [
                        'nombre' => 'nombre',
                        'constructora' => 'constructora',
                        'direccion' => 'direccion',
                        'ciudad' => 'ciudad',
                        'configuracion' => 'configuracion'
                    ],
                    'nombreRegistro' => ['constructora', 'nombre'],
                ],
                'qr_viviendas' => [
                    'tabla' => 'qr_viviendas',
                    'relaciones' => [
                        'qr_privadas' => [
                            'modelo' => QrPrivada::class,
                            'campo' => [
                                'nombre' => 'nombre_privada',
                                'constructora' => 'constructora'
                            ],
                            'destino' => 'privada_id'
                        ]
                    ],
                    'campos' => [
                        'numero' => 'numero',
                        'tipo' => 'tipo',
                        'calle' => 'calle',
                        'seccion' => 'seccion',
                    ],
                    'nombreRegistro' => ['constructora', 'nombre'],
                ],
                'users' => [
                    'tabla' => 'users',
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
                    'nombreRegistro' => ['username'],
                ],
                'qr_residentes' => [
                    'tabla' => 'qr_residentes',
                    'relaciones' => [
                        'qr_privadas' => [
                            'modelo' => QrPrivada::class,
                            'campo' => [
                                'nombre' => 'nombre_privada',
                                'constructora' => 'constructora'
                            ],
                            'destino' => 'privada_id'
                        ],
                        'users' => [
                            'modelo' => User::class,
                            'campo' => [
                                'username' => 'username'
                            ],
                            'destino' => 'usuario_id'
                        ]
                    ],
                    'campos' => [
                        'telefono' => 'telefono',
                        'activo' => 'activo',
                        'vivienda_id' => 'vivienda_id'
                    ],
                    'transformaciones' => [
                        'vivienda_id' => function ($data_vivienda) {
                            // recuperamos el id de la vivienda
                            $vivienda = QrVivienda::with('privada')->whereHas(
                                'privada',
                                function ($query) use ($data_vivienda) {
                                    $query->where('nombre', $data_vivienda['nombre_privada'])
                                        ->where('constructora', $data_vivienda['constructora']);
                                }
                            )->where('numero', $data_vivienda['numero'])->first();
                            return $vivienda ? $vivienda->id : null;
                        }
                    ],
                    'nombreRegistro' => ['telefono'],
                ],
                'qr_invitados' => [
                    'tabla' => 'qr_invitados',
                    'campos' => [
                        'nombre' => 'nombre',
                        'apellido_pat' => 'apellido_pat',
                        'apellido_mat' => 'apellido_mat',
                        'alias' => 'alias',
                        'telefono' => 'telefono',
                        'activo' => 'activo',
                        'residente_id' => 'residente_id',
                    ],
                    'transformaciones' => [
                        'residente_id' => function ($data_residente) {
                            // recuperamos el id del invitado
                            $invitado = QrResidente::with('usuario')->whereHas(
                                'usuario',
                                function ($query) use ($data_residente) {
                                    $query->where('username', $data_residente);
                                }
                            )->first();
                            return $invitado ? $invitado->id : null;
                        }
                    ],
                    'nombreRegistro' => ['alias'],
                ],
                'qr_vehiculos' => [
                    'tabla' => 'qr_vehiculos',
                    'campos' => [
                        'invitado_id' => 'invitado_id',
                        'marca' => 'marca',
                        'modelo' => 'modelo',
                        'color' => 'color',
                        'modelo' => 'modelo',
                        'placas' => 'placas',
                        'activo' => 'activo'
                    ],
                    'transformaciones' => [
                        'invitado_id' => function ($data_invitado) {
                            // recuperamos el id del invitado
                            $invitado = QrInvitado::with('residente.usuario')->whereHas(
                                'residente.usuario',
                                function ($query) use ($data_invitado) {
                                    $query->where('username', $data_invitado['username']);
                                }
                            )->where('alias', $data_invitado['alias'])->first();
                            return $invitado ? $invitado->id : null;
                        }
                    ],
                    'nombreRegistro' => ['telefono'],
                ],
                'qr_codigos' => [
                    'tabla' => 'qr_codigos',
                    'campos' => [
                        'codigo' => 'codigo',
                        'fecha_generacion' => 'fecha_generacion',
                        'fecha_expiracion' => 'fecha_expiracion',
                        'ultima_fecha_uso' => 'ultima_fecha_uso',
                        'usos_restantes' => 'usos_restantes',
                        'estado' => 'estado',
                        'invitado_id' => 'invitado_id'
                    ],
                    'transformaciones' => [
                        'invitado_id' => function ($data_invitado) {
                            // recuperamos el id del invitado
                            $invitado = QrInvitado::with('residente.usuario')->whereHas(
                                'residente.usuario',
                                function ($query) use ($data_invitado) {
                                    $query->where('username', $data_invitado['username']);
                                }
                            )->where('alias', $data_invitado['alias'])->first();
                            return $invitado ? $invitado->id : null;
                        },
                        'codigo' => function () {
                            return Str::random(20);
                        }
                    ],
                    'nombreRegistro' => ['codigo'],
                ],
                'qr_dispositivo' => [
                    'tabla' => 'qr_dispositivo',
                    'relaciones' => [
                        'qr_privadas' => [
                            'modelo' => QrPrivada::class,
                            'campo' => [
                                'nombre' => 'nombre_privada',
                                'constructora' => 'constructora'
                            ],
                            'destino' => 'privada_id'
                        ]
                    ],
                    'campos' => [
                        'clave' => 'clave',
                        'direccion_ip' => 'direccion_ip'
                    ],
                    'nombreRegistro' => ['clave'],
                ],
                'qr_accesos' => [
                    'tabla' => 'qr_accesos',
                    'relaciones' => [
                        'qr_dispositivo' => [
                            'modelo' => QrDispositivo::class,
                            'campo' => [
                                'clave' => 'clave',
                                'direccion_ip' => 'direccion_ip'
                            ],
                            'destino' => 'dispositivo_id'
                        ]
                    ],
                    'campos' => [
                        'codigo_qr_id' => 'codigo_qr_id',
                        'fecha_hora' => 'fecha_hora',
                        'num_uso' => 'num_uso',
                        'resultado' => 'resultado'
                    ],
                    'transformaciones' => [
                        'codigo_qr_id' => function ($data_codigo) {
                            // recuperamos el id del invitado
                            $codigo = QrCodigo::with('invitado.residente.usuario', 'invitado')->whereHas(
                                'invitado.residente.usuario',
                                function ($query) use ($data_codigo) {
                                    $query->where('username', $data_codigo['username']);
                                }
                            )->whereHas(
                                'invitado',
                                function ($query) use ($data_codigo) {
                                    $query->where('alias', $data_codigo['alias']);
                                }
                            )->where('fecha_generacion', $data_codigo['fecha_generacion'])->first();
                            return $codigo ? $codigo->id : null;
                        }
                    ],
                    'nombreRegistro' => ['dispositivo', 'direccion_ip', 'fecha_hora'],
                ],
            ]
        ];

        $this->cargarDatosGenerico('recuperacionDB/qr.json', null, $config);
    }


    //-------------------------- Funciones generales para cargar datos
    public function cargarDatosGenerico($rutaArchivo, $nombreTabla, $config = [])
    {
        $this->info("Cargando de la tabla: $nombreTabla desde el archivo: $rutaArchivo");
        $this->info(' ');
        $this->info(' ');

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

            // Identificador del registro para mensajes

            // mensaje de éxito
            $identificadorRegistro = "";
            // verificamos si el nombreRegistro es un string
            if (is_string($config['nombreRegistro'])) {
                $nombreCampo = $config['nombreRegistro'] ?? 'nombre';
                $identificadorRegistro = $registro[$nombreCampo] ?? 'desconocido';
            }
            // si no es un string, verificamos si es un array
            else if (is_array($config['nombreRegistro'])) {
                // si es un array, recorremos el array y concatenamos los valores
                foreach ($config['nombreRegistro'] as $nombreCampo) {
                    // revisamos todos los campos que se encuentran dentro del array y los concatenamos en la variable identificadorRegistro
                    if (isset($registro[$nombreCampo])) {
                        $identificadorRegistro .= $nombreCampo . ": " . $registro[$nombreCampo] . ' --- ';
                    } else {
                        $identificadorRegistro .= $nombreCampo . ": " . 'Desconocido ' . ' --- ';
                    }
                }
            } else {
                $identificadorRegistro = "Campos desconocidos";
            }

            try {
                $datosInsertar = $this->mapearDatos($registro, $config);

                DB::table($nombreTabla)->insert($datosInsertar);
                $this->info("Registro {$identificadorRegistro} cargado correctamente en $nombreTabla");
                $exitosos++;
            } catch (\Exception $e) {
                // mensaje de error
                $nombre = $identificadorRegistro ?: 'Registro desconocido';
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
            // generamos el array de datos para la relacion
            $condiciones_busqueda = array();

            foreach ($configRelacion['campo'] as $key => $valorCondicion) {

                $condiciones_busqueda[$key] = $registro[$valorCondicion] ?? null;
            }
            // Verificar si el modelo existe
            $registroRelacionado = $modelo::where(
                $condiciones_busqueda
            )->first();

            if (!$registroRelacionado) {
                throw new \Exception("No se encontró el registro relacionado para $condiciones_busqueda en {$configRelacion['modelo']}");
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
