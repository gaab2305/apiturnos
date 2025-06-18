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
        $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
            ->put('https://universitario.alephoo.com/api/v3/admision/turnos/'.$IDTurno, $payload);

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
}
