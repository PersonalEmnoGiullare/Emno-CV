<?php

namespace App\Http\Controllers\Qr\Api;

use App\Http\Controllers\Controller;
use App\Models\Qr\QrCodigo;
use App\Models\Qr\QrPrivada;
use App\Models\Qr\QrAcceso;
use App\Models\Qr\QrDispositivo;
use App\Models\Qr\QrInvitado;
use App\Models\Qr\QrResidente;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CodigoQrController extends Controller
{
    /**
     * Genera un nuevo código QR para un invitado
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generarCodigo(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'invitado_id' => 'required|exists:qr_invitados,id',
            'privada_id' => 'required|exists:qr_privadas,id',
            'residente_id' => 'required|exists:qr_residentes,id',
            'usos' => 'sometimes|integer|min:1|max:10',
            'validez_horas' => 'sometimes|integer|min:1|max:168',
        ], [
            // Mensajes personalizados
            'invitado_id.required' => 'El ID del invitado es obligatorio para generar el código QR.',
            'invitado_id.exists' => 'El invitado seleccionado no existe en nuestros registros.',
            'privada_id.required' => 'Debe especificar la privada donde se generará el acceso.',
            'privada_id.exists' => 'La privada seleccionada no es válida.',
            'residente_id.required' => 'El ID del residente es necesario para la autorización.',
            'residente_id.exists' => 'El residente especificado no está registrado.',
            'usos.integer' => 'El número de usos debe ser un valor numérico entero.',
            'usos.min' => 'El código debe permitir al menos 1 uso.',
            'usos.max' => 'No se pueden permitir tal cantidad de usos por código de seguridad.',
            'validez_horas.integer' => 'Las horas de validez deben ser un número entero.',
            'validez_horas.min' => 'El código debe ser válido por al menos 1 hora.',
            'validez_horas.max' => 'Por seguridad, el código no puede ser válido por más de 7 días.',
        ]);

        // Si la validación falla, retornar errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error en los datos proporcionados',
                'errors' => $validator->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422);
        }

        // Verificar que el recidente pertenece a la privada
        // * buscamos al residente 
        $residente = QrResidente::with('privada')
            ->whereHas('privada', function ($query) use ($request) {
                $query->where('id', $request->privada_id);
            })
            ->where('id', $request->residente_id)
            ->first();

        // * si no existe, retornamos error
        if (!$residente) {
            return response()->json([
                'success' => false,
                'message' => 'El residente no pertenece a la privada especificada',
                'error_code' => 'RESIDENTE_NOT_IN_PRIVADA'
            ], 403);
        }

        // verificamos que el invitado pertenesca al residente
        // * buscamos al invitado
        $invitado = QrInvitado::with('residente')
            ->whereHas('residente', function ($query) use ($residente) {
                $query->where('id', $residente->id);
            })
            ->where('id', $request->invitado_id)
            ->first();

        // * si no existe, retornamos error
        if (!$invitado) {
            return response()->json([
                'success' => false,
                'message' => 'El invitado no pertenece al residente especificado',
                'error_code' => 'INVITADO_NOT_IN_RESIDENTE'
            ], 403);
        }

        // Generamos el código único
        do {
            $codigo = Str::upper(Str::random(20));
        } while (QrCodigo::where('codigo', $codigo)->exists());

        // Crear el código QR
        $qrCode = QrCodigo::create([
            'invitado_id' => $request->invitado_id,
            'codigo' => $codigo,
            'fecha_expiracion' => $request->validez_horas
                ? Carbon::now()->addHours($request->validez_horas)
                : Carbon::now()->addDay(),
            'usos_restantes' => $request->usos ?? 1,
            'estado' => 'activo'
        ]);

        return response()->json([
            'success' => true,
            'codigo' => $qrCode->codigo,
            'fecha_expiracion' => $qrCode->fecha_expiracion,
            'usos_restantes' => $qrCode->usos_restantes,
            'invitado' => $invitado->getNombreCompletoAttribute(),
            'privada' => QrPrivada::find($request->privada_id)->nombre
        ]);
    }

    /**
     * Verifica un código QR y registrar el acceso
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verificarCodigo(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make(
            $request->all(),
            [
                'codigo' => 'required|string|size:20|exists:qr_codigos,codigo',
                'clave' => 'required|string|max:100|exists:qr_dispositivo,clave'
            ],
            [ // generamos mensajes personalizados
                'codigo.required' => 'El código QR es obligatorio para verificar el acceso.',
                'codigo.string' => 'El código QR debe ser una cadena de texto.',
                'codigo.size' => 'El código QR debe tener exactamente 20 caracteres.',
                'codigo.exists' => 'El código QR proporcionado no existe en nuestros registros.',
                'clave.required' => 'La clave del dispositivo es obligatoria para verificar el acceso.',
                'clave.string' => 'La clave del dispositivo debe ser una cadena de texto.',
                'clave.max' => 'La clave del dispositivo no puede exceder los 100 caracteres.',
                'clave.exists' => 'La clave del dispositivo proporcionada no es válida.'
            ]
        );

        // Si la validación falla, retornar errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Recuperamos la ip de la peticion
        $clienteIp = $request->ip();
        // $clienteIp = "172.16.254.5"; // Para pruebas unitarias, usar una IP fija

        // validamos que la clave del dispositivo corresponda con la ip del dispositivo 
        // * buscamos el código QR
        $dispositivo = QrDispositivo::where('clave', $request->clave)
            ->where('direccion_ip', $clienteIp)
            ->first();

        // * Si no existe el dispositivo o la clave no coincide, retornar error
        if (!$dispositivo) {
            return response()->json([
                'success' => false,
                'message' => 'Dispositivo no autorizado o clave incorrecta',
                'error_code' => 'DEVICE_NOT_AUTHORIZED'
            ], 403);
        }

        // verificamos que el codigo y el dispositivo pertenecen a la misma privada
        // * buscamos el código QR asociado al invitado y residente
        $codigoQr = QrCodigo::with('invitado.residente.privada')
            ->whereHas('invitado.residente.privada', function ($query) use ($dispositivo) {
                $query->where('id', $dispositivo->privada_id);
            })
            ->where('codigo', $request->codigo)
            ->first();

        // * Si no existe el código QR o no pertenece a la privada del dispositivo, retornar error
        if (!$codigoQr) {
            return response()->json([
                'success' => false,
                'message' => 'Código QR no válido para este dispositivo',
                'error_code' => 'QR_CODE_NOT_VALID_FOR_DEVICE'
            ], 403);
        }

        // Verificar estado del código
        if (!$codigoQr->estaActivo()) {
            $motivo = match ($codigoQr->estado) {
                'usado' => 'Límite de usos alcanzado',
                'expirado' => 'Código expirado',
                'cancelado' => 'Código cancelado',
                default => 'Código no activo'
            };

            // Registrar acceso denegado
            $this->registrarAcceso($codigoQr, $dispositivo, $request, 'denegado', $motivo);

            return response()->json([
                'success' => false,
                'message' => $motivo
            ], 403);
        }

        // Actualizar el código QR (reducir usos y actualizar fecha de uso)
        $codigoQr->decrementUsosRestantes('usos_restantes');

        // Registrar acceso exitoso
        $acceso = $this->registrarAcceso($codigoQr, $dispositivo, $request, 'permitido', 'Acceso concedido');

        return response()->json([
            'success' => true,
            'message' => 'Acceso permitido',
            'acceso' => [
                'id' => $acceso->id,
                'fecha_hora' => $acceso->fecha_hora,
                'invitado' => $codigoQr->invitado->nombre_completo,
                'usos_restantes' => $codigoQr->usos_restantes
            ]
        ]);
    }

    /**
     * Registra un intento de acceso en la base de datos
     * 
     * @param QrCodigo $qrCode
     * @param Request $request
     * @param string $resultado
     * @param string $observaciones
     * @return QrAcceso
     */
    protected function registrarAcceso(QrCodigo $codigoQr, QrDispositivo $dispositivo, Request $request, string $resultado, string $observaciones = null)
    {
        return QrAcceso::create([
            'codigo_qr_id' => $codigoQr->id,
            'fecha_hora' => now(),
            'num_uso' => $codigoQr->usos_restantes,
            'dispositivo_id' => $dispositivo->id,
            'resultado' => $resultado,
            'observaciones' => $observaciones,
        ]);
    }

    /**
     * Obtiene el listado de códigos QR generados para un residente
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarCodigos(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make(
            $request->all(),
            [
                'estado' => 'sometimes|nullable|string|in:activo,expirado,usado,cancelado',
                'invitado_id' => 'sometimes|nullable|integer|exists:qr_invitados,id',
                'fecha' => 'sometimes|nullable|date_format:Y-m-d',
            ],
            [ // mensajes personalizados existentes...
                'estado.string' => 'El estado debe ser una cadena de texto.',
                'estado.in' => 'El estado debe ser: activo, expirado, usado o cancelado.',
                'invitado_id.integer' => 'El ID del invitado debe ser un número entero.',
                'invitado_id.exists' => 'El invitado especificado no existe en nuestros registros.',
                'fecha.date_format' => 'La fecha debe estar en el formato YYYY-MM-DD.'
            ]
        );

        // Si la validación falla, retornar errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error en los datos proporcionados',
                'errors' => $validator->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422);
        }

        // Recuperamos el ID del residente por medio del usuario autenticado
        // * Recuperamos el ID del usuario autenticado
        $UsuarioId = Auth::id();
        // $UsuarioId = 5; // Para pruebas unitarias, usar un ID de usuario fijo
        // * Buscamos al residente asociado al usuario
        $residente = QrResidente::where('usuario_id', $UsuarioId)->first();
        // * Si no existe el residente, retornar error
        if (!$residente) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un residente asociado al usuario autenticado.',
                'error_code' => 'RESIDENTE_NOT_FOUND',
                'UsuarioId' => $UsuarioId
            ], 404);
        }
        // obtener los códigos QR del residente
        $codigos = QrCodigo::with('invitado.residente.privada')
            ->whereHas('invitado.residente', function ($query) use ($residente) {
                $query->where('id', $residente->id);
            })
            // Filtrar por estado solo si se proporciona y no está vacío
            ->when($request->filled('estado'), function ($query) use ($request) {
                $query->where('estado', $request->estado);
            })
            // Filtrar por invitado solo si se proporciona
            ->when($request->filled('invitado_id'), function ($query) use ($request) {
                $query->where('invitado_id', $request->invitado_id);
            })
            // Filtrar por fecha solo si se proporciona
            ->when($request->filled('fecha'), function ($query) use ($request) {
                $query->whereDate('fecha_generacion', $request->fecha);
            })
            ->orderBy('fecha_generacion', 'desc')
            ->get();

        // Si no hay códigos, retornar mensaje
        if ($codigos->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No se encontraron códigos QR para el residente especificado.',
                'data' => []
            ]);
        }
        // Formatear la respuesta
        $data = $codigos->map(function ($codigo) {
            return [
                'id' => $codigo->id,
                'codigo' => $codigo->codigo,
                'fecha_expiracion' => $codigo->fecha_expiracion,
                'fecha_generacion' => $codigo->fecha_generacion,
                'usos_restantes' => $codigo->usos_restantes,
                'estado' => $codigo->estado,
                'invitado' => $codigo->invitado->getNombreCompletoAttribute(),
                'alias' => $codigo->invitado->alias
            ];
        });
        // Retornar la respuesta
        return response()->json([
            'success' => true,
            'message' => 'Códigos QR obtenidos correctamente.',
            'data' => $data
        ]);
    }
}
