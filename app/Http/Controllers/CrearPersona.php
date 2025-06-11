<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Persona as ControllersPersona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Persona;

class  CrearPersona extends Controller
{

    public function nuevaPersona(Request $request)
    {
        $item = $request->all();

        $attributes = $item['data']['attributes'];

        $payload = [
            'data' => [
                'attributes' => [
                    'hcCarpeta' => null,
                    'nombres' => $attributes['nombres'],
                    'apellidos' => $attributes['apellidos'],
                    'nacimiento' => $attributes['nacimiento'],
                    'tipoDocumento' => 1,
                    'documento' => $attributes['documento'],
                    'cuil' => "",
                    'nacionalidad' => 1,
                    'generoDocumento' => $attributes['sexo'],
                    'grupoSanguineo' => 1,
                    'estadoCivil' => 2,
                    'sexo' => $attributes['sexo'],
                    'incapacidad' => false,
                    'cronico' => false,
                    'celulares' => [
                        [
                            "paisCelularSelected" => [
                                "attributes" => [
                                    "codigo" => "AR"
                                ]
                            ],
                            "codigoCelular" => $attributes['celulares']['codigoCelular'],
                            "numCelular" => $attributes['celulares']['numCelular']
                        ]
                    ],
                    'emailsGmail' => [
                        [
                            'emailGmail' => ''
                        ]
                    ],
                    'email' => $attributes['email'],
                    'observacion' => '',
                    'calle' => 'Calle',
                    'numero' => '123',
                    'piso' => '',
                    'depto' => '',
                    'barrio' => null,
                    'ciudad' => null,
                    'partido' => null,
                    'provincia' => null,
                    'pais' => 1,
                    'coberturaMedica' => [
                        [
                            "ppd" => "false",
                            "obraSocialSelected" => [
                                "id" => $attributes['obraSocialSelectedId']
                            ],
                            "planSelected" => [
                                "id" => $attributes['planSelectedId']
                            ]
                        ]
                    ],
                    'notaAuditor' => '',
                    'empadronamiento' => null,
                    'noAceptaDonacion' => false,
                    'educacion' => [
                        'escuelas' => '',
                        'observaciones' => '',
                        'aniosAprobados' => '',
                        'problemasInstitucion' => '',
                        'nivelInstruccion' => null
                    ],
                    'trabajo' => [
                        'empleadorid' => null,
                        'observacion' => '',
                        'horasFueraCasa' => '',
                        'trabajoRemunerado' => 0,
                        'horarioTrabajo' => 0,
                        'tipoOcupacion' => 0,
                        'trabajoLegal' => 0,
                        'trabajoInsalubre' => 0,
                        'edadInicio' => ''
                    ]
                ]
            ]
        ];

        $response = Http::timeout(60)->withBasicAuth(env('usernamealephoo'), env('userpw'))
            ->post('https://universitario.alephoo.com/api/v3/admin/personas', $payload);

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
