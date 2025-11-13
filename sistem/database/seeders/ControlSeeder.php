<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Control;

class ControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder inserts records in chunks to avoid memory/timeouts.
     * Adjust $total and $chunkSize as needed.
     *
     * @return void
     */
    public function run()
    {
        $total = 500000;
        $chunkSize = 5000; // inserts por batch

        // Usamos factory para generar arrays y hacer insert masivo
        for ($i = 0; $i < (int) ceil($total / $chunkSize); $i++) {
            $batch = [];
            $count = min($chunkSize, $total - ($i * $chunkSize));

            // Generar $count registros
            for ($j = 0; $j < $count; $j++) {
                $f = Control::factory()->make();
                $batch[] = [
                    'nombre' => $f->nombre,
                    'estudios' => $f->estudios,
                ];
            }

            // Insert masivo
            DB::table((new Control)->getTable())->insert($batch);

            $this->command->info("Insertados ".(($i+1)*$chunkSize > $total ? $total : ($i+1)*$chunkSize)." / {$total}");
        }
    }
}
