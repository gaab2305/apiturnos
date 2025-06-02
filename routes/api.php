<?php

use App\Http\Controllers\CrearPersona;
use App\Http\Controllers\CrearTurno;
use App\Http\Controllers\Especialidades;
use App\Http\Controllers\getobrasocial;
use App\Http\Controllers\GetObraSocial as ControllersGetObraSocial;
use App\Http\Controllers\getobrasociales;
use Illuminate\Http\Request;
use Illuminate\Support\facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Persona;
use App\Http\Controllers\Profesionales;
use App\Http\Controllers\Turnos;

Route::get('/v1/obrasocial',[GetObraSocial::class, 'os']);
Route::get('/v1/personas/{DNI}' , [Persona::class, 'persona']);
Route::get('/v1/especialidades/' , [Especialidades::class, 'especialidad']);
Route::get('/v1/profesionales/{IDespecialidad}' , [Profesionales::class, 'profesional']);
Route::get('/v1/turnos/{IDprofesional?}/{IDespecialidad?}', [Turnos::class, 'turno']);
Route::post('/v1/crear/turno', [CrearTurno::class, 'nuevoturno']);
Route::post('/v1/crear/persona', [CrearPersona::class, 'nuevapersona']);