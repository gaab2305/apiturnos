<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Persona as ControllersPersona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Persona;

class  CrearPersona extends Controller
{
    
public function nuevaPersona($item, $resultado2 = [])
{
    // Armamos el payload con la estructura requerida
    $payload = [
        'data' => [
            'attributes' => [
                'id' => $item['id'],
                'nombres' => $item['attributes']['nombres'],
                'apellidos' => $item['attributes']['apellidos'],
                'documento' => $item['attributes']['documento'],
                'sexo' => $item['attributes']['sexo'],
                'fechaNacimiento' => $item['attributes']['fechaNacimiento'],
                'celular' => $item['attributes']['celular'],
                'email' => $item['attributes']['email'],
                'idObraSocial' => $resultado2['idObraSocial'] ?? null,
                'obraSocial' => $resultado2['obraSocial'] ?? null,
            ]
        ]
    ];

    // Enviar la solicitud HTTP POST
    $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
        ->post('https://universitario.alephoo.com/api/v3/admin/personas', $payload);

    // Retornar la respuesta o manejar errores
    if ($response->successful()) {
        return response()->json(['mensaje' => 'Persona enviada con éxito', 'respuesta' => $response->json()], 200);
    } else {
        return response()->json([
            'mensaje' => 'Error al enviar persona',
            'status' => $response->status(),
            'error' => $response->json()
        ], $response->status());
    }
}
}