<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Persona as ControllersPersona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\cancelacion;
use GuzzleHttp\Promise\CancellationException;
use App\Models\Turno;

class CancelarTurnos extends Controller
{
    public function cambiarEstado($IDTurno, Request $request)
    {

        $payload = [
           "data" => [
        "type" => "Admision\\Turnoprogramado",
        "id" => $IDTurno,
        "attributes" => [
            "observacion" => "Prueba"
        ],
        "relationships" => [
            "estadoTurno" => [
                "data" => [
                    "type" => "Admision\\Estadoturno",
                    "id" => 3
                ]
            ]
        ]
    ]
        ];
        //dd($payload);
        // Enviar a la API REAL con autenticación básica
        $key = base64_decode(env('TURNO_KEY'));
    $iv  = base64_decode(env('TURNO_IV'));
        $idturno = $this->decryptTurnoId($IDTurno, $key, $iv);
        $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
            ->put('https://universitario.alephoo.com/api/v3/admision/turnos/'.$idturno, $payload);

        if ($response->successful()) {
            return response()->json([
                'mensaje' => 'Turno cancelado correctamente',
                'turno' => $response->json()
            ]);
        }

        return response()->json([
            'mensaje' => 'Error al crear el turno',
            'error' => $response->body()
        ], $response->status());
    }

    private function decryptTurnoId($encrypted, $key, $iv)
{
    $decoded = $this->base64url_decode($encrypted);
    return openssl_decrypt($decoded, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
}
    private function base64url_decode($data)
{
    $data .= str_repeat('=', 4 - (strlen($data) % 4)); // padding
    return base64_decode(strtr($data, '-_', '+/'));
}
}
