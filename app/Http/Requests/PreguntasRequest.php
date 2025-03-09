<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\EncuestasController;
use App\Models\Seccion;

class PreguntasRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->seccion = Seccion::where('tipo', request()->tipo)
                          ->where('route', request()->rutaActual)
                          ->first();
            
        $this->incluyePreguntas = EncuestasController::esSeccionConPreguntas($this->seccion->route);
        if ($this->incluyePreguntas) {
            $this->prefijoPreguntas = EncuestasController::obtenerPrefijoPreguntas($this->seccion->route);
            $this->sufijoPreguntas = EncuestasController::obtenerSufijoPreguntas($this->seccion->tipo, $this->seccion->route);
            $this->preguntas = EncuestasController::obtenerValorPreguntas($this->seccion);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    
    {
       
        if (empty($this->preguntas)) {
            return [];
        }

        $rules = [];
        foreach ($this->preguntas as $pregunta) {
            $rules[$this->prefijoPreguntas . $pregunta->orden . $this->sufijoPreguntas] = 'required|integer';
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
      
        if (empty($this->preguntas)) {
            return [];
        }

        $messages = [];
        foreach ($this->preguntas as $pregunta) {
            $messages[$this->prefijoPreguntas . $pregunta->orden . $this->sufijoPreguntas .'.required'] = 'Por favor, selecciona una opción para la pregunta ' . $pregunta->orden . '.';
            $messages[$this->prefijoPreguntas . $pregunta->orden . $this->sufijoPreguntas . '.integer'] = 'La opción seleccionada para la pregunta ' . $pregunta->orden . ' debe ser un valor válido.';
        }

        return $messages;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
      
        $response = redirect()->route('encuesta.preguntas', ['tipo' =>strtolower($this->tipo),'seccion'=>$this->seccion->route])
                            ->withErrors($errors)
                            ->withInput();

        // Throw the validation exception
        throw new ValidationException($validator, $response);
    }    
}
