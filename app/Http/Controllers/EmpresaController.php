<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Session;
use Illuminate\Support\Facades\DB;
use Log;
class EmpresaController extends Controller
{
    public function index(){
        $empresas = User::find(Auth::user()->id)->empresas()->select('EMPRESA.ID_EMPRESA', 'EMPRESA.NOMBRE_EMPRESA')
        ->distinct()
        ->get();
        return view('empresa.index', compact('empresas'));
    }

    public function setRolEmpresa(Request $request){
        
        $request->validate([
            'empresaId' => ['required',]
        ]);

        $this->setPermisos($request->empresaId);

        return redirect(route('home'));
    }

    public function getEmpresas(){
        
        $empresas = User::find(Auth::user()->id)->empresas()->pluck('EMPRESA.NOMBRE_EMPRESA','EMPRESA.ID_EMPRESA')
        ->distinct()
        ->get();
        return response()->json($empresas);
    }

    public function setPermisos($idEmpresa){
        $permisosEmpresa = DB::table('ACCESO_USUARIO')
        ->where('ID_USUARIO', Auth::user()->id)
        ->where('ID_EMPRESA', $idEmpresa)
        ->pluck('ID_OPCION');

        $permisos = DB::table('OPCION_FORM')->whereIn('ID_OPCION', $permisosEmpresa)->get();
        $coleccion = array();

        foreach($permisos as $permiso){
            $coleccion[$permiso->FORM][] = $permiso->DESC_OPCION;
        }
        Session::put('empresaID', $idEmpresa);
        Session::put('permisos', $coleccion);

        return response()->json([
            'message' => 'Permisos actualizados'
        ]);
    }

}
