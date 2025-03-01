<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Empleado;

class ValidarEmailEmpleado implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Empleado::where('correo', $value)
        ->where('habilitado', 'habilitado')
        ->where('llave', 'nada')
        ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El correo enviado no existe en los registros.';
    }
}
