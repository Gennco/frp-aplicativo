<?php

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpleadoFactory extends Factory
{
    protected $model = Empleado::class;

    public function definition()
    {
        return [
            'lugartrabajo' => $this->faker->company,
            'nombre' => $this->faker->name,
            'cargo' => $this->faker->jobTitle,
            'cedula' => $this->faker->unique()->numerify('##########'),
            'contrasena' => Hash::make('password123'), // Contraseña hasheada
            'correo' => $this->faker->unique()->safeEmail,
            'delaciudad' => $this->faker->city,
            'deptociudad' => $this->faker->state,
            'personal' => $this->faker->randomElement(['SI', 'NO']),
            'areatrabajo' => $this->faker->word,
            'sede' => $this->faker->city,
            'nivelSeguridad' => $this->faker->randomElement(['A', 'B']),
            'fecha_subida' => now(),
            'fecha_final'=>now(),
            'tipoencuesta' => $this->faker->word,
            'habilitado' => $this->faker->randomElement(['habilitado']),
            'consentimiento' =>  $this->faker->randomElement(['EN ESPERA']),
            'fichadatos' => $this->faker->randomElement(['EN ESPERA']),
            'terminos' => $this->faker->randomElement(['EN ESPERA']),
            'periodo' => now()->year,
            'procesos' => "",
            'llave' => 'nada',
            'filtro' => "",
        ];
    }
}
