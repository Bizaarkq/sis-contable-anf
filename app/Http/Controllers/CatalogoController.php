<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Helpers\RatiosCuenta;
use DB;
class CatalogoController extends Controller
{
    //
    public function create(){
        return view('catalogo.index',[
            'accounts' =>  Account::all()->where('ID_EMPRESA', session('empresaID')),
            'date' => date('Y-m-d')
        ]);
    }

    public function store(Request $request)
    {
        
        $n = count($request->request)-1;
        $n = $n/7;

        

        for ($i=1; $i <= $n; $i++) { 
            $codigo_catalogo = "codigoCatalogo".$i;
            $nombre_catalogo_cuentas = "nombreCuenta".$i;
            $cuenta_padre = "codigoPadre".$i;
            $tipo_cuenta = "tipoCuenta".$i;
            $saldo = "saldo".$i;
            $nivel = "nivelCuenta".$i;
            $corriente = "escorriente".$i;

            $cuenta = new Account();
            $cuenta->ID_EMPRESA = session('empresaID');
            $cuenta->CODIGO_CATALOGO = $request->$codigo_catalogo;
            $cuenta->NOMBRE_CATALOGO_CUENTAS = $request->$nombre_catalogo_cuentas;
            $cuenta->CUENTA_PADRE = $request->$cuenta_padre;
            $cuenta->TIPO_CUENTA = strtoupper($request->$tipo_cuenta);
            $cuenta->SALDO = strtoupper($request->$saldo);
            $cuenta->NIVEL = $request->$nivel;
            $cuenta->CORRIENTE = $request->$corriente;
            
            
            //$cuenta->save();
        }


        return redirect('/');
    
    }

    public function configurar(){

        $cuentas = [];
        foreach (RatiosCuenta::CONFIGURACION as $key => $value) {
            foreach ($value as $value2) {
                $cuenta = DB::table('CUENTAS_FINANCIERAS')->where("ID_CUENTA_FINANCIERA", $value2)->pluck("NOMBRE_CUENTA_FINANCIERA", "ID_CUENTA_FINANCIERA");
                $cuentas[$key][] = $cuenta;
            }
        }

        return view('catalogo.configurarCatalogo');
    }

}   
