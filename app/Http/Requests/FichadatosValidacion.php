<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class FichadatosValidacion extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'sexo' => 'required|in:MASCULINO,FEMENINO',
            'anonaci'=>'required|not_in:""',
            'estadociv' => 'required|not_in:""',
            'nivelEstudio' => 'required|not_in:""',
            'ocupacion' => 'required|string|min:4|max:150',
            'residenciadepto' => 'required|not_in:""',
            'residenciaciudad'=> 'required|not_in:""',
            'estrato'=>'required|not_in:""',
            'tipoVivienda'=>'required|not_in:""',
            'personasdependientes'=>'required|integer|min:0|max:20',
            'tiempotrabajo'=>'required|not_in:""',
            'tipocargo'=>'required|not_in:""',
            'anoscargo'=>'required|not_in:""',
            'contratoactual'=>'required|not_in:""',
            'horastrabajo'=>'required|integer|min:1|max:23',
            'tiposalario'=>'required|not_in:""'
        ];
    }

    public function messages(): array
    {
        return [
            'sexo.required' => 'Por favor seleccione un genero.',
            'anonaci.required' => 'Por favor seleccione un año de nacimiento.',
            'estadociv.required' => 'Por favor seleccione el estado civil.',
            'nivelEstudio.required' => 'Por favor seleccione el nivel de estudio.',
            'ocupacion.required' => 'Por favor indique cual es su ocupación.',
            'ocupacion.string' => 'La ocupacion no debe tener números.',
            'ocupacion.min' => 'La ocupacion debe por lo menos tener 4 letras.',
            'ocupacion.max' => 'La ocupacion debe por no debe exceder los 150 caracteres.',
            'residenciadepto.required' => 'Por favor seleccione un departamento de residencia.',
            'residenciaciudad.required' => 'Por favor seleccione una ciudad de residencia',
            'estrato.required' => 'Por favor seleccione un estrato',
            'personasdependientes.required' => 'Por favor indique cuantas personas dependen de usted .',
            'personasdependientes.integer' => 'No digite letras solo números.',
            'personasdependientes.min' => 'Indique un número de personas dependientes mayor a 0.',
            'personasdependientes.max' => 'Indique un numero de personas dependientes menor que 20.',
            'tipoVivienda.required' => 'Por favor seleccione el tipo de vivienda.',
            'tiempotrabajo.required' => 'Por favor seleccione el tiempo que lleva en la empresa.', 
            'tipocargo.required'=>'Por favor seleccione el tipo de cargo.' , 
            'anoscargo.required'=>'Por favor seleccione cuantos años lleva en el cargo.' , 
            'contratoactual.required'=>'Por favor seleccione el tipo de contrato',
            'horastrabajo.required' => 'Por favor indique cuantas horas la dia trabaja.',
            'horastrabajo.integer' => 'No digite letras solo números.',
            'horastrabajo.min' => 'Indique un número mayor a 1.',
            'horastrabajo.max' => 'Indique un numero menor que 23.',
            'tiposalario.required'=>'Escoja un tipo de salario'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = redirect()->route('encuesta.fichadatos')
                            ->withErrors($errors)
                            ->withInput();

        // Throw the validation exception
        throw new ValidationException($validator, $response);
    }    
}
