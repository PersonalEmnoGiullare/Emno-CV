<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GuardarBaseDatos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:guardar-base-datos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este es un comando que podemos utilizar para respaldar los registros en la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //array que me permite agregar o borrar tablas
        $tablas = [
            ['usuario', 'usuarios', 'users'],
            ['consumible', 'consumible', 'tbl_consumible'],
            ['datosdevolucion', 'datosdevolucion', 'tbl_datosdevolucion'],
            ['departamentos', 'departamentos', 'tbl_departamentos'],
            ['devoluciones', 'devoluciones', 'tbl_devoluciones'],
            ['empleados', 'empleados', 'tbl_empleados'],
            ['entradas', 'entradas', 'tbl_entradas'],
            ['herramientas', 'herramientas', 'tbl_herramientas'],
            ['marcas', 'marcas', 'tbl_marcas'],
            ['modelos', 'modelos', 'tbl_modelos'],
            ['nombre_herramientas', 'nombre herramientas', 'tbl_nombreherramientas'],
            ['ordenes', 'ordenes', 'tbl_ordenes'],
            ['peticiones', 'peticiones', 'tbl_peticiones'],
            ['relacion_entrada_consumible', 'relacion entrada consumible', 'tbl_relacionentradaconsumible'],
            ['relacion_entrada_herramientas', 'relacion entrada herramientas', 'tbl_relacionentradaherramientas'],
            ['relacion_salida_consumible', 'relacion salida consumible', 'tbl_relacionsalidaconsumible'],
            ['relacion_salida_herramientas', 'relacion salida herramientas', 'tbl_relacionsalidaherramientas'],
            ['roles', 'roles', 'tbl_roles'],
            ['salidas', 'salidas', 'tbl_salidas'],
            ['tipos_consumible', 'tipos consumible', 'tbl_tiposconsumible'],
            ['turnos', 'turnos', 'tbl_turnos'],
            ['unidades_medida', 'unidades medida', 'tbl_unidadesmedida']

        ];

        // for each que repita mi codigo para mis tabals
        foreach ($tablas as $tabla) {
            // definir ruta del archivo JSON
            $ruta = "recuperacionDB/{$tabla[0]}.json";

            // lanzamos una info que se esta intentando recuperar la tabla
            $this->info("Consultando la tabla {$tabla[1]}... ");
            $this->info('');
            try {
                $datos = DB::table($tabla[2])->get();
                $this->info("La tabla {$tabla[1]} recuperada con exito");
                $this->info('');

                if ($datos->isEmpty()) {
                    $this->error("La tabla {$tabla[1]} esta vacia.");
                    $this->info('');
                } else {
                    try {
                        $json = json_encode($datos);
                        $exito = Storage::disk('local')->put($ruta, $json);

                        if ($exito) {
                            $this->info("La tabla {$tabla[1]} se ha guardado correctamente");
                            $this->info('');
                        } else {
                            $this->error("Error en el guardado de la tabla {$tabla[1]}.\nError desconocido");
                            $this->info('');
                        }
                    } catch (\Exception $e) {
                        $this->error("Error en el guardado de la tabla {$tabla[1]}.\n" . $e->getMessage());
                        $this->info('');
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error en la recuperacion de la tabla {$tabla[1]}.\n" . $e->getMessage());
                $this->info('');
            }
        }
    }
}
