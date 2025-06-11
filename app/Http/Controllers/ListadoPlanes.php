<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\facades\Http;
use Illuminate\Http\Controllers;


class ListadoPlanes extends BaseController
{
    public function planes($IDObraSocial)
    {
        $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
            ->get('https://universitario.alephoo.com/api/v3/admin/obrasSociales/' . $IDObraSocial);

        if (!$response->successful()) {
            return response()->json([
                'mensaje' => 'Error al obtener los datos de la obra social',
                'status' => $response->status(),
                'error' => $response->json()
            ], $response->status());
        }

        $data = $response->json();
        $included = $data['included'] ?? []; 

        $resultado = [];

        foreach ($included as $item) {
            if ($item['type'] === 'Admin\\Plan') {
                $resultado[] = [
                    'id' => $item['attributes']['id'],
                    'nombre' => $item['attributes']['nombre'],
                ];
            }
        }

        return response()->json($resultado);
    }
}
