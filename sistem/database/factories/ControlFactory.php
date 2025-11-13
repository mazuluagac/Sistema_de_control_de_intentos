<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ControlFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Control::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
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

        return [
            'nombre' => $this->faker->name(),
            'estudios' => $programas[array_rand($programas)],
        ];
    }
}
