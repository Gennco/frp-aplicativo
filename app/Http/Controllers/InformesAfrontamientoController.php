<?php
namespace App\Http\Controllers;

use App\Models\InformeAfrontamiento;
use App\Http\Util\UtilitariosInforme as Util;
use Illuminate\Support\Facades\DB;

class InformesAfrontamientoController extends Controller{
    public static function generarInformeAfrontamiento($fichadatos){
        $sumas = DB::table('afrontamientoa as a')
        ->join('afrontamientob as b', 'a.registro', '=', 'b.registro')
        ->join('afrontamientoc as c', 'a.registro', '=', 'c.registro')
        ->where('a.registro', $fichadatos->registro)
        ->select(
            DB::raw('(a.p10 + a.p17 + a.p19 + b.p3 + b.p5 + b.p14 + b.p16 + c.p5 + c.p22) AS suma1'),
            DB::raw('(a.p6 + a.p14 + a.p23 + b.p1 + b.p10 + c.p1 + c.p11) AS suma2'),
            DB::raw('(a.p9 + a.p18 + b.p4 + b.p6 + b.p15 + b.p17 + c.p4 + c.p14 + c.p23) AS suma3'),
            DB::raw('(a.p8 + a.p16 + b.p2 + b.p13 + c.p3 + c.p13 +c.p21) AS suma4'),
            DB::raw('(a.p11 + a.p20 + a.p21 + b.p7 + b.p18 + b.p20 + c.p7 + c.p16) AS suma5'),
            DB::raw('(a.p7 + a.p15 + b.p9 + c.p2 + c.p12) AS suma6'),
            DB::raw('(a.p4 + a.p12 + a.p22 + b.p10 + b.p20) AS suma7'),
            DB::raw('(b.p5 + b.p6 + b.p16 + c.p8 + c.p17) AS suma8'),
            DB::raw('(a.p5 + a.p13 + b.p23 + c.p10 + c.p18) AS suma9'),
            DB::raw('(b.p22 + c.p6 + c.p9 + c.p15) AS suma10'),
            DB::raw('(a.p1 + a.p2 +a.p3) AS suma11'),
            DB::raw('(c.p19 + a.p20) AS suma12'),
        )->get()
        ->mapWithKeys(function ($item) {
            return [
                'suma1' => $item->suma1,
                'suma2' => $item->suma2,
                'suma3' => $item->suma3,
                'suma4' => $item->suma4,
                'suma5' => $item->suma5,
                'suma6' => $item->suma6,
                'suma7' => $item->suma7,
                'suma8' => $item->suma8,
                'suma9' => $item->suma9,
                'suma10' => $item->suma10,
                'suma11' => $item->suma11,
                'suma12' => $item->suma12,
            ];
        })
        ->toArray();
        
        $data = [
            'NumeroFolio'=>$fichadatos->NumeroFolio,
            'registro'=>$fichadatos->registro,
            'cedula'=>$fichadatos->cedula,
            'nombre'=>$fichadatos->nombre,
            'empresa'=>$fichadatos->empresas,
            'area'=>$fichadatos->nombredepto,
            'cargo'=>$fichadatos->cargoempresa,
            'sede'=>$fichadatos->sede,
            'p1'=>$sumas['suma1'],
            'p2'=>$sumas['suma2'],
            'p3'=>$sumas['suma3'],
            'p4'=>$sumas['suma4'],
            'p5'=>$sumas['suma5'],
            'p6'=>$sumas['suma6'],
            'p7'=>$sumas['suma7'],
            'p8'=>$sumas['suma8'],
            'p9'=>$sumas['suma9'],
            'p10'=>$sumas['suma10'],
            'p11'=>$sumas['suma11'],
            'p12'=>$sumas['suma12'],
            'periodo'=>$fichadatos->periodo
        ];

        try {
            $modelInfo = InformeAfrontamiento::where('registro',$fichadatos->registro)->first();
            !empty($modelInfo) ?  InformeAfrontamiento::where('registro',$data['registro'])->update($data) : InformeAfrontamiento::create($data);
        } catch (Exception $exception) {
            Log::error('Error registrando informe afrontamiento: ', $exception);
            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
        }
    }
}