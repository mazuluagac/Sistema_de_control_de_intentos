<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Control;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class ControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Devolver primer lote de registros (solo nombre y estudios)
        // Usamos cache corto para mejorar latencia en solicitudes repetidas
        $cacheKey = 'controls:preview';
        $data = Cache::remember($cacheKey, 30, function () {
            return Control::select('nombre', 'estudios')->limit(50)->get();
        });

        return response()->json(['usuarios' => $data], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function registros(){
        // método vacío, reservado
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Obtener solo los campos requeridos y cachear por 30s
        $cacheKey = "control:{$id}";
        $usuario = Cache::remember($cacheKey, 30, function () use ($id) {
            return Control::select('nombre', 'estudios')->find($id);
        });

        if (! $usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($usuario, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generateLargeData(Request $request)
    {
        $count = (int) $request->query('count', 500000);
        $chunkSize = 5000; // inserts por batch

        $programas = [
            'Ingeniería en Sistemas',
            'Ingeniería de Software',
            'Ciencias de la Computación',
            'Ingeniería en Telecomunicaciones',
            'Ingeniería Informática',
            'Matemáticas',
            'Física',
            'Biología'
        ];

        $startTime = microtime(true);
        $totalInserted = 0;

        try {
            // Único ciclo que genera e inserta en chunks
            for ($i = 1; $i <= $count; $i += $chunkSize) {
                $batch = [];
                $batchEnd = min($i + $chunkSize - 1, $count);

                // Generar lote
                for ($j = $i; $j <= $batchEnd; $j++) {
                    $batch[] = [
                        'nombre' => 'Usuario_' . str_pad($j, 6, '0', STR_PAD_LEFT),
                        'estudios' => $programas[array_rand($programas)],
                    ];
                }

                // Insertar lote
                DB::table('controls')->insert($batch);
                $totalInserted += count($batch);
            }

            $elapsed = microtime(true) - $startTime;

            return response()->json([
                'message' => 'Generación completada exitosamente',
                'total_insertados' => $totalInserted,
                'tiempo_segundos' => round($elapsed, 2),
                'registros_por_segundo' => round($totalInserted / $elapsed, 2)
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error durante la generación',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
