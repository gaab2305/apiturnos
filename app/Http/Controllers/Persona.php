<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\facades\Http;
use Illuminate\Http\Controllers;
use Illuminate\Support\Facades\Log;

class Persona extends Controller
{
    public static function persona($dni)
    {
        if (empty($dni)) {
            return response()->json(['error' => 'Debe proporcionar un DNI'], 400);
        }

        try {
            // Paso 1: Buscar persona
            $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
                ->get('https://universitario.alephoo.com/api/v3/admin/personas?filter[documento]=' . $dni);

            if ($response->failed()) {
                return response()->json(['error' => 'No se pudo obtener información del paciente'], 500);
            }

            $apiData = $response->json();
            $personaData = $apiData['data'][0];

            $persona = $personaData['attributes'];
            $personaPlanId = $personaData['relationships']['personaPlanPorDefecto']['data']['id'] ?? null;

            $nombreObraSocial = null;

            // Paso 2: Buscar personaPlan y obtener ID del plan
            if ($personaPlanId) {
                $personaPlanResponse = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
                    ->get("https://universitario.alephoo.com/api/v3/admin/personaPlanes/{$personaPlanId}");

                if ($personaPlanResponse->ok()) {
                    $planId = $personaPlanResponse['data']['relationships']['plan']['data']['id'] ?? null;

                    // Paso 3: Buscar plan y obtener nombre de obra social
                    if ($planId) {
                        $planResponse = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
                            ->get("https://universitario.alephoo.com/api/v3/admin/Planes/{$planId}");

                        if ($planResponse->ok()) {
                            $planData = $planResponse['data'];
                            $nombreObraSocial = $planData['attributes']['nombre'] ?? null;

                            // (Opcional) Si querés ir más allá:
                            // $obraSocialId = $planData['relationships']['obraSocial']['data']['id'] ?? null;
                            // podrías luego buscar /admin/Coberturas/{id}
                        }
                    }
                }
            }


            $transformedData = [
                'id' => $personaData['id'],
                'nombres' => $persona['nombres'],
                'apellidos' => $persona['apellidos'],
                'documento' => $persona['documento'],
                'fecha_nacimiento' => $persona['fechaNacimiento'],
                //'edad' => $this->calculateAge($persona['fechaNacimiento'] ?? null),
                'genero' => $persona['sexo'],
                'obra_social' => $nombreObraSocial,
                'obra_social_id'=> $planId,
                'plan_id'=> $personaPlanId,
                'email' => $persona['email'],
                'contacto_telefono' => $persona['celular'],
                'contacto_telefono_2' => $persona['telefono'],
            ];

            return response()->json([$transformedData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud'], 500);
        }
    }
}
