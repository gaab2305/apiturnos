<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class  CrearTurno extends Controller
{
    public function nuevoturno(Request $request)
{
    Log::info($request);

    $payload = [
        "data" => [
            "type" => "Admision\\Turnoprogramado",
            "id" => null,
            "attributes" => [
                "hora" => $request->hora,
                "fecha" => $request->fecha,
                'orden' => -1,
                "sobreturno" => false,
                "observacion" => null
            ],
            "relationships" => [
                "agenda" => [
                    "data" => [
                        "type" => "Admision\\Agenda",
                        "id" => $request->agenda_id
                    ]
                ],
                "persona" => [
                    "data" => [
                        "type" => "Admin\\Persona",
                        "id" => $request->persona_id
                    ]
                ],
                "especialidad" => [
                    "type" => "Admin\\Especialidad",
                    "id" => $request->especialidad_id
                ]
            ]
        ]
    ];

    // Enviar a la API REAL con autenticación básica
    $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
        ->post('https://universitario.alephoo.com/api/v3/admision/turnos', $payload);

    if ($response->successful()) {
        return response()->json([
            'mensaje' => 'Turno creado correctamente',
            'turno' => $response->json()
        ]);
    }

    return response()->json([
        'mensaje' => 'Error al crear el turno',
        'error' => $response->body()
    ], $response->status());
}

}