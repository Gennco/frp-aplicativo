<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MensajesAlerta extends Controller
{
    public function handle(Request $request, Closure $next)
    {
          
            if (session('success')) {
                Alert::success(session('success'));
            }
            if (session('error')) {
                Alert::error(session('error'));
            }
            return $next($request);
       
    }
}
