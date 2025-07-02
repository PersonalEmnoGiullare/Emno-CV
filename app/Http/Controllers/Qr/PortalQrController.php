<?php

namespace App\Http\Controllers\Qr;

use App\Http\Controllers\Controller;
use App\Models\Qr\QrResidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalQrController extends Controller
{
    /**
     * Display the QR code generation page.
     *
     * @return \Illuminate\View\View
     */
    public function generarQr()
    {
        // Recuperamos el ID del usuario autentificado
        $UsuarioId = Auth::id();

        // recuperamos el residente con todas sus relaciones necesarias
        $residente = QrResidente::with([
            'privada',
            'vivienda',
            'invitados' => function ($query) {
                $query->where('activo', true)
                    ->orderBy('nombre')
                    ->orderBy('apellido_pat');
            },
            'usuario'
        ])
            ->where('usuario_id', $UsuarioId)
            ->where('activo', true)
            ->first();

        // Validaciones
        // Revisamos que exista el residente
        if (!$residente) {
            abort(403, 'No tienes permiso para acceder a esta pagina.');
        }

        // verificamos que el residente tenga una privada asignada
        if (!$residente->privada) {
            abort(403, 'No tienes permiso para acceder a esta pagina.');
        }

        // verificamos que el residente tenga invitados
        if ($residente->invitados->isEmpty()) {
            return view('qr.gen_qr', [
                'residente_id' => $residente->id,
                'privada_id' => $residente->privada_id,
                'privada_nombre' => $residente->privada->nombre,
                'invitados' => collect(), // Colección vacía
                'residente' => $residente,
                'mensaje' => 'No tienes invitados registrados. Contacta al administrador para agregar invitados.'
            ]);
        }

        // Preparar datos adicionales para la vista
        $invitadosFormateados = $residente->invitados->map(function ($invitado) {
            return [
                'id' => $invitado->id,
                'nombre_completo' => $invitado->getNombreCompletoAttribute(),
                'alias' => $invitado->alias
            ];
        });

        return view('qr.gen_qr', [
            'residente_id' => $residente->id,
            'privada_id' => $residente->privada_id,
            'privada_nombre' => $residente->privada->nombre,
            'invitados' => $residente->invitados, // Para el select
            'invitados_formateados' => $invitadosFormateados, // Para mostrar más info si necesitas
            'residente' => $residente
        ]);
    }
    /**
     * Display the QR code generation page.
     *
     * @return \Illuminate\View\View
     */
    public function consultarQr()
    {
        // Recuperamos el ID del usuario autentificado
        $UsuarioId = Auth::id();


        // recuperamos el residente con todas sus relaciones necesarias
        $residente = QrResidente::with([
            'privada',
            'vivienda',
            'invitados' => function ($query) {
                $query->where('activo', true)
                    ->orderBy('nombre')
                    ->orderBy('apellido_pat');
            },
            'usuario'
        ])
            ->where('usuario_id', $UsuarioId)
            ->where('activo', true)
            ->first();

        // Validaciones
        // Revisamos que exista el residente
        if (!$residente) {
            abort(403, 'No tienes permiso para acceder a esta pagina.');
        }

        // verificamos que el residente tenga una privada asignada
        if (!$residente->privada) {
            abort(403, 'No tienes permiso para acceder a esta pagina.');
        }

        $datos_enviar = array(
            'residente_id' => $residente->id,
            'privada_id' => $residente->privada_id,
            'privada_nombre' => $residente->privada->nombre,
            'invitados' => collect(),
            'invitados_formateados' => collect(),
            'residente' => $residente
        );

        // verificamos que el residente tenga invitados o codigos
        if (!$residente->invitados->isEmpty()) {

            // Preparar datos adicionales para la vista
            $invitadosFormateados = $residente->invitados->map(function ($invitado) {
                return [
                    'id' => $invitado->id,
                    'nombre_completo' => $invitado->getNombreCompletoAttribute(),
                    'alias' => $invitado->alias
                ];
            });

            // agregamos los valores a los datos a enviar 
            $datos_enviar['invitados'] = $residente->invitados;
            $datos_enviar['invitados_formateados'] = $invitadosFormateados;
        }

        return view('qr.consultar_qr', $datos_enviar);
    }
}
