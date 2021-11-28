<?php

namespace App\Http\Controllers;

use App\cost;
use Illuminate\Http\Request;

class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('costs.createCost',[
            'registro' =>  cost::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $costo = new cost();
        $costo->description = $request->descripcion;
        $costo->amount = $request->monto;
        $costo->element = $request->elements;

        $costo->save();

        return redirect('/cost/create');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $Item = cost::find($request->idEdit);
        $Item->description = $request->descripcionEdit;
        $Item->amount = $request->montoEdit;
        $Item->element = $request->elementEdit;
        $Item->save();

        return redirect('/cost/create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Item = cost::find($id);
        $Item->delete();
        return redirect('/cost/create');
    }

    public function generate()
    {
        return view('costs.generateCost',[
            'porciento' => "0",
            'MD' => "?",
            'MOD' => "?",
            'CIF' => "?",
            'CDA' => "?",
            'CTP' => "?",
            'CP' => "?",
            'CC' => "?",
            'costosPeriodo' =>"?",
            'producido' => "?",
            'costoUni' => "?",
            'precioVenta' => "?",
            'precioIva' => "?"
        ]);
    }

    public function result(Request $request)
    {
        $costos = cost::all();
        $MD = 0;
        $MOD = 0;
        $CIF = 0;
        $CDA = 0;
        foreach($costos as $costo) {
            if($costo->element == "MD"){
                $MD += $costo->amount;
            }elseif($costo->element == "MOD"){
                $MOD += $costo->amount;
            }elseif($costo->element == "CIF"){
                $CIF += $costo->amount;
            }elseif($costo->element == "CDA"){
                $CDA += $costo->amount;
            }
        }

        $CTP = $MD + $MOD + $CIF;
        $CP = $MD + $MOD;
        $CC = $MOD + $CIF;
        $costosPeriodo = $CDA;
        $producido = $request->cantidad;
        $costoUni = $CTP / $producido;
        $precioVenta = $costoUni + ($costoUni * ($request->porcentaje / 100));
        $precioVenta = round($precioVenta , 2);
        $precioIva = $precioVenta * 1.13;
        $precioIva = round($precioIva , 2);

        return view('costs.generateCost',[
            'porciento' => $request->porcentaje,
            'MD' => $MD,
            'MOD' => $MOD,
            'CIF' => $CIF,
            'CDA' => $CDA,
            'CTP' => $CTP,
            'CP' => $CP,
            'CC' => $CC,
            'costosPeriodo' => $costosPeriodo,
            'producido' => $producido,
            'costoUni' => $costoUni,
            'precioVenta' => $precioVenta,
            'precioIva' => $precioIva,
        ]);
    }
}
