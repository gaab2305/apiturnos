<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\facades\Http;
use Illuminate\Http\Controllers;

class Turnos extends Controller
{
    public function turno ($IDprofesional, $IDespecialidad)
    {
        $fechaInicio = Carbon::now()->format('Y-m-d');
        $fechaFin = Carbon::now()->addDays(15)->format('Y-m-d');

        $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
        ->get('https://universitario.alephoo.com/api/v3/admision/turnos/disponibles?filter[profesionales]=' . $IDprofesional . '&filter[especialidades]=' . $IDespecialidad . '&filter[fechaInicio]=' . $fechaInicio . '&filter[fechaFin]=' . $fechaFin);
    $data = $response->json();
    $items = $data['data'] ?? []; // asumiendo que la clave es 'data'
    $resultado = [];

    foreach ($items as $item) {
        $resultado[] = [
            'hora' => $item['attributes']['hora'],
            'fecha' => $item['attributes']['fecha'],
            'agenda' => $item['relationships']['agenda']['data']['id']
        ];
    }

    return response()->json($resultado);
    }
} 