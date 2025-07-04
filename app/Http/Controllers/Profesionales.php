<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\facades\Http;
use Illuminate\Http\Controllers;


class Profesionales extends BaseController
{
    public function profesional($IDespecialidad)
    {
        $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
            ->get('https://universitario.alephoo.com/api/v3/admin/profesionales?filter[instituciones]=1&filter[especialidades]=' . $IDespecialidad);

        $datas = $response->json();
        $data = $datas['data'] ?? [];       // IDs
        $included = $datas['included'] ?? []; // Nombres y apellidos

        $resultado = [];

        // Asegurarnos de que ambos arrays tienen la misma cantidad de elementos
        $count = min(count($data), count($included));

        for ($i = 0; $i < $count; $i++) {
            $resultado[] = [
                'id' => $data[$i]['id'],
                'nombres' => $this->capitalizarNombres($included[$i]['attributes']['nombres'] ?? ''),
                'apellidos' => $this->capitalizarNombres($included[$i]['attributes']['apellidos'] ?? ''),
            ];
        }

        return response()->json($resultado);
    }

    private function capitalizarNombres($string)
    {
        $string = preg_replace('/[-_]+/', ' ', $string);
        return ucwords(strtolower(trim($string)));
    }

}