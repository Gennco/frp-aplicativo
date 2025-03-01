<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use App\Models\Empleado;
use App\Rules\ValidarEmailEmpleado;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class EmpleadoAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.empleado-login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        $rules = [
            'cedula' => ['required'],
            'contrasena' => ['required'],
        ];

        $messages =[
            'cedula.required' => 'La cedula no debe estar vacia.',
            'contrasena.required' => 'La contraseña no debe estar vacia',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('cedula', 'contrasena');
     
        $empleado = Empleado::where([
            'cedula' => $credentials['cedula'],
            'habilitado' => 'habilitado',
            'llave' => 'nada'
        ])->first();

        Log::info('Empleado encontrado: ', ['empleado' => $empleado]);
            
        if ($empleado && Hash::check($request->contrasena, $empleado->contrasena) && $empleado->habilitado === 'habilitado' && $empleado->llave === 'nada') {
            Auth::login($empleado);
            session(['authenticated_empleado_id' => $empleado->registro]);
            return redirect()->intended('/inicio');
        } else {
            $empleado = Empleado::where('cedula', $request->cedula)
                ->where('habilitado', 'completo')
                ->where('llave', 'terminado')
                ->first();

            if ($empleado) {
                return back()->withErrors([
                    'cedula' => 'El usuario registrado ya ha completado la encuesta',
                ]);
            } else {
                return back()->withErrors([
                    'cedula' => 'Los datos ingresados no coinciden con nuestros registros',
                ]);
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/empleado/login');
    }
    public function showLinkEmailForm(){
        return view('auth.empleado-email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        
        $request->validate([
            'correo' => ['required', 'email'],
        ], [
            'correo.required' => 'El campo de correo electrónico es obligatorio.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
        ]);

        $request->validate([
            'correo' => new ValidarEmailEmpleado,
        ]);

        $response = Password::broker()->sendResetLink(
            ['correo' => $request->correo]
        );

        if ($response == Password::RESET_LINK_SENT) {
            return redirect()->back()->with('success', 'Se ha enviado un enlace de recuperación a tu correo.');
        } else {
            return redirect()->back()->with('error', 'No se pudo enviar el enlace. Verifica tu correo.');
        }
    }
    


    public function showResetForm($token)
    {
        return view('auth.empleado-reset', ['token' => $token]);
    }

    public function updateResetForm(Request $request)

    {
        $request->validate([
            'token'=>'required',
            'correo' => 'required|email|exists:empleados,correo',
            'contrasena' => 'required|confirmed|min:8',
        ], [
            'correo.required' => 'El campo de correo electrónico es obligatorio.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
            'correo.exists' => 'El correo electrónico no esta en la base de datos.',
            'contrasena.required' => 'El campo contraseña es requerido.',
            'contrasena.confirmed' => 'La confirmación de contraseña no es identica.',
            'contrasena.min' => 'La contraseña debe contener como minimo 8 caracteres entre letras, numeros y/o simbolos.',
        ]);

        $status = Password::reset(
            $request->only('correo', 'contrasena', 'contrasena_confirmation', 'token'),
            function ($empleado, $contrasena) {
                $empleado->forceFill([
                    'contrasena' => Hash::make($contrasena),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('empleado.login')->with('success', __($status))
            : back()->withErrors(['correo' => [__($status)]]);
    }

    protected function redirectTo()
    {
        return '/inicio';
    }
}