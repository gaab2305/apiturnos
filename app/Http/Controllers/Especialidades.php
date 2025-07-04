<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\facades\Http;
use Illuminate\Http\Controllers;

class Especialidades extends Controller
{
    public function especialidad()
    {
        $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
        ->get('https://universitario.alephoo.com//api/v3/admin/especialidades');

    $data = $response->json();
    $items = $data['data'] ?? []; // asumiendo que la clave es 'data'
    $resultado = [];

    foreach ($items as $item) {
        $resultado[] = [
            'id' => $item['id'],
            'nombre' => $item['attributes']['nombre']
        ];
    }

    return response()->json($resultado);
    }
}
