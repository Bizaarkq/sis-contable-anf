<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Account;
use App\Part;
use App\Item;
use App\Empresa;
use App\Helpers\EstadosFinancieros;
use App\Helpers\BalanceGeneral;
use PDF;
use Auth;
use Log;
use DB;
use App\Registro;

class ItemController extends Controller
{   
    protected $periodoActivo;

    public function __construct(){
        $this->middleware('auth'); //para restricciones
        $this->periodoActivo = $this->getPeriodoActivo();
    }

    public function getPeriodoActivo(){
        $periodoActivo = DB::table('PERIODO')
        ->select('ID_PERIODO')
        ->where('ACTIVO_PERIODO', 1)
        ->first();
        return $periodoActivo->ID_PERIODO;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('item.index',[
            'items' => Item::orderby('CREATED_AT')
            ->Where('ID_PERIODO', $this->periodoActivo)
            ->Where('ID_EMPRESA', session('empresaID'))
            ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item.create',[
            'accounts' =>  Account::all()->where('ID_EMPRESA', session('empresaID')),
            'date' => date('Y-m-d')
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
        $n = count($request->request)-2;
        $n = $n/3;
        
        $item = new Item();
        $item->ID_PERIODO = $this->periodoActivo;
        $item->ID_EMPRESA = session('empresaID');
        $item->DESCRIPCION_PARTIDA = $request->description;
        $item->FECHA_PARTIDA = date('Y-m-d');
        $item->CREATED_USER = Auth::user()->username;
        $item->UPDATED_USER = Auth::user()->username;
        $item->save();

        $id = $item->ID_PARTIDA;
    
        for ($i=1; $i <= $n; $i++) { 
            $account = "account".$i;
            $debit = "debe".$i;
            $credit = "haber".$i;
            $id_account = $request->$account; 

            $part = new Part();
            $part->ID_CATALOGO = $id_account;
            $part->DEBE = $request->$debit;
            $part->HABER = $request->$credit;
            $part->ID_PARTIDA = $id;
            $part->CREATED_USER = Auth::user()->username;
            $part->UPDATED_USER = Auth::user()->username;

            $part->save();
        }

        return redirect('/item/create');
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
        $item = Item::Where('ID_PARTIDA',$id)
        ->select('ID_PARTIDA','DESCRIPCION_PARTIDA','FECHA_PARTIDA')
        ->with(['parts:ID_LIBRO_DIARIO,ID_CATALOGO,DEBE,HABER,ID_PARTIDA', 
        'parts.accounts:ID_CATALOGO,NOMBRE_CATALOGO_CUENTAS,CODIGO_CATALOGO'])
        ->first();

        return view('item.edit',[
            'accounts' =>  Account::all()->where('ID_EMPRESA', session('empresaID')),
            'date' => $item->FECHA_PARTIDA,
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request, $id);
        Part::where('ID_PARTIDA',$id)->delete();
        
        $n = count($request->request)-3;
        $n = $n/3;
        
        $item = Item::find($id);
        $item->DESCRIPCION_PARTIDA = $request->description;
        $item->FECHA_PARTIDA = $request->date;
        $item->UPDATED_USER= Auth::user()->username;
        $item->save();
    
        for ($i=1; $i <= $n; $i++) { 
            $account = "account".$i;
            $debit = "debe".$i;
            $credit = "haber".$i;
            $idCuenta = $request->$account; 

            $part = new Part();
            $part->ID_CATALOGO = $idCuenta;
            $part->DEBE = $request->$debit;
            $part->HABER = $request->$credit;
            $part->ID_PARTIDA = $id;
            $part->CREATED_USER = Auth::user()->username;
            $part->UPDATED_USER = Auth::user()->username;
            $part->save();
        }

        $month = date('m', strtotime($request->date));

        return redirect('/item/'.$month.'/JournalBook');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Item = Item::find($id);
        $parts = Part::where('ID_PARTIDA',$id)->delete();
        $Item->delete();
        return redirect()->back();
    }
    
    public function setMonthJournalBook(Request $request){
        return redirect('/item/'.$request->month.'/JournalBook');
    }

    public function JournalBook($month){
        $items = Item::Select('ID_PARTIDA','DESCRIPCION_PARTIDA','FECHA_PARTIDA')
        ->where('ID_EMPRESA', session('empresaID'))
        ->where('ID_PERIODO', $this->periodoActivo)
        ->orderby('FECHA_PARTIDA')
        ->with(['parts:ID_LIBRO_DIARIO,ID_CATALOGO,DEBE,HABER,ID_PARTIDA', 
        'parts.accounts:ID_CATALOGO,NOMBRE_CATALOGO_CUENTAS,CODIGO_CATALOGO'])
        ->get();
        
        return view('item.JournalBook',[
            'items' => $items,
            'currentmonth' => date('m'),
            'selectedmonth' => $month,
            'months' => [0 => 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']
        ]);
        
    }

    public function ledger(){ // hecho el 11/11/2021 no se si le entendere despues

        $items = Item::Select('ID_PARTIDA','DESCRIPCION_PARTIDA','FECHA_PARTIDA')
        ->where('ID_EMPRESA', session('empresaID'))
        ->where('ID_PERIODO', $this->periodoActivo)
        ->orderby('FECHA_PARTIDA')
        ->with(['parts:ID_LIBRO_DIARIO,ID_CATALOGO,DEBE,HABER,ID_PARTIDA', 
        'parts.accounts:ID_CATALOGO,NOMBRE_CATALOGO_CUENTAS,CODIGO_CATALOGO,CORRIENTE'])
        ->get();
        $accounts = $this->getLedgerAccounts();
        $ledger = [];
        $table = ['id' => '', 'title' => '','debits' => [],'credits' => [],'totaldebits' => 0,'totalcredits' => 0,'total' => 0,'cd' => 0, 'cc' => 0]; 
        $debit = ['mount' => 0,'item_num' => 0];
        $credit = ['mount' => 0,'item_num' => 0];
        $cont = 1;

        foreach ($accounts as $account) {
            $table['title'] = $account->NOMBRE_CATALOGO_CUENTAS;
            $table['id'] = $account->CODIGO_CATALOGO;
            $table['corriente'] = $account->CORRIENTE;
            foreach ($items as $item) {
                foreach ($item->parts as $part) {
                    
                    if (str_starts_with($part->accounts->CODIGO_CATALOGO, $account->CODIGO_CATALOGO)) {
                        if($part->DEBE > 0){
                            $debit['mount'] = $part->DEBE;
                            $debit['item_num'] = $cont;
                            $table['totaldebits'] += $debit['mount'];
                            array_push($table['debits'],$debit);
                            $debit = ['mount' => 0,'item_num' => 0];
                        }
                        if($part->HABER > 0){
                            $credit['mount'] = $part->HABER;
                            $credit['item_num'] = $cont;
                            $table['totalcredits'] += $credit['mount'];
                            array_push($table['credits'],$credit);
                            $credit = ['mount' => 0,'item_num' => 0];
                        }
                    }
                    
                }
            $cont++;
            }
            $cont = 1;

            if($table['totaldebits']>$table['totalcredits']){
                $table['total'] = $table['totaldebits'] - $table['totalcredits'];
            }
            else if($table['totalcredits']>$table['totaldebits']){
                $table['total'] = $table['totalcredits'] - $table['totaldebits'];
            }
            else{
                $table['total'] = 0;
            }

            if(!(empty($table['debits']) && empty($table['credits']))){
                $table['cd'] = count($table['debits']);
                $table['cc'] = count($table['credits']);
                array_push($ledger,$table);
            }
            $table = ['id' => '', 'title' => '','debits' => [],'credits' => [],'totaldebits' => 0,'totalcredits' => 0,'total' => 0,'cd' => 0, 'cc' => 0]; 
        }
        
        return $ledger;
    }

    public function getLedgerAccounts(){

        $confCuentas = Empresa::where('ID_EMPRESA',session('empresaID'))->pluck('CONFIG_CUENTA')[0];
        $nivelCuentasMayor = json_decode($confCuentas, true)['nivelCuentasMayor'];
        
        $accounts = Account::all()
        ->where('NIVEL', $nivelCuentasMayor)
        ->where('ID_EMPRESA',session('empresaID'));

        return $accounts;
    }

    public function searchParent($account){
        if($account->allParents == null){
            return $account;
        }else{
            return $this->searchParent($account->allParents);
        }
    }

    public function checkingBalance($ledger){
        $cuentasDeAgrupacion = Account::WhereNull('CUENTA_PADRE')
        ->where('ID_EMPRESA',session('empresaID'))
        ->get()
        ->toArray();

        $checkingBalance = ['cuentas'=> [], 'totales' => ['totaldebit' => 0, 'totalcredit' => 0] ];
        $heading = ['id' => '', 'title' => '', 'total' => 0, 'balance' => 0];

        foreach($ledger as $table){
            $heading['title'] = $table['title'];
            $heading['id'] = $table['id'];
            $heading['total'] = $table['total'];
            $heading['corriente'] = $table['corriente'];
            $table['totaldebits'] > $table['totalcredits'] ? 
            $checkingBalance['totales']['totaldebit'] += $heading['total'] : 
            $checkingBalance['totales']['totalcredit'] += $heading['total'];

            $heading['balance'] = $table['totaldebits'] > $table['totalcredits'] ? 1 : 0;

            $cuentaAgrup = array_filter($cuentasDeAgrupacion, function($cuenta) use ($heading){
                return str_starts_with($heading['id'], $cuenta['CODIGO_CATALOGO']);
            });
            $cuentaAgrup = array_values($cuentaAgrup);
            $checkingBalance['cuentas'][$cuentaAgrup[0]["NOMBRE_CATALOGO_CUENTAS"]][] = $heading;
        }

        return $checkingBalance;
    }

    public function statementOfIncome(){

        $confCuentas = Empresa::where('ID_EMPRESA',session('empresaID'))->pluck('CONFIG_CUENTA')[0];
        $confCuentas = json_decode($confCuentas, true)['cuentas_financieras'];
        
        $accounts = Account::where('ID_EMPRESA',session('empresaID'))
        ->whereIn('ID_CATALOGO',array_column($confCuentas, 'id'))
        ->pluck('NOMBRE_CATALOGO_CUENTAS','ID_CATALOGO');
        //dd($confCuentas, $accounts);
        $items = Item::Select('ID_PARTIDA')
        ->where('ID_EMPRESA', session('empresaID'))
        ->where('ID_PERIODO', $this->periodoActivo)
        ->orderby('FECHA_PARTIDA')
        ->with(['parts:ID_CATALOGO,DEBE,HABER,ID_PARTIDA', 
        'parts.accounts:ID_CATALOGO,NOMBRE_CATALOGO_CUENTAS,CODIGO_CATALOGO'])
        ->get();

        $result = [
            'ingresos' => [
                'cuenta' => $accounts[$confCuentas[33]['id']],
                'total' => 0
            ],
            'costos' => [
                'cuenta' => $accounts[$confCuentas[34]['id']],
                'total' => 0
            ],
            'gastosOp' => [
                'cuenta' => $accounts[$confCuentas[36]['id']],
                'total' => 0
            ],
            'otrosIng' => [
                'cuenta' => $accounts[$confCuentas[41]['id']],
                'total' => 0
            ]
        ];

        foreach($items as $item){
            foreach($item->parts as $part){
                if(str_starts_with($part->accounts->CODIGO_CATALOGO, $confCuentas[33]['codigo'])){
                    $result['ingresos']['total'] += $part->HABER - $part->DEBE;
                }else if(str_starts_with($part->accounts->CODIGO_CATALOGO, $confCuentas[34]['codigo'])){
                    $result['costos']['total'] += $part->DEBE - $part->HABER;
                }else if(str_starts_with($part->accounts->CODIGO_CATALOGO, $confCuentas[36]['codigo'])){
                    $result['gastosOp']['total'] += $part->DEBE - $part->HABER;
                }else if(str_starts_with($part->accounts->CODIGO_CATALOGO, $confCuentas[41]['codigo'])){
                    $result['otrosIng']['total'] += $part->HABER - $part->DEBE;
                }
            }
        }

        $result['utilidadBruta']['cuenta'] = "Utilidad Bruta";
        $result['utilidadBruta']['total'] = $result['ingresos']['total'] - $result['costos']['total'];
        $result['utilidadOp']['cuenta'] = "Utilidad Operativa";
        $result['utilidadOp']['total'] = $result['utilidadBruta']['total'] - $result['gastosOp']['total'];        
        $result['utilidadAntesImpRes']['cuenta'] = "Utilidad Antes de Impuestos y Reservas";
        $result['utilidadAntesImpRes']['total'] = $result['utilidadOp']['total'] + $result['otrosIng']['total'];
        $result['reserva']['cuenta'] = "Reserva legal";
        $result['reserva']['total'] = $result['utilidadAntesImpRes']['total'] * 0.07;
        $result['utilidadAntesImp']['cuenta'] = "Utilidad Antes de Impuestos";
        $result['utilidadAntesImp']['total'] = $result['utilidadAntesImpRes']['total'] - $result['reserva']['total'];        
        $result['impuestos']['cuenta'] = "Impuestos";
        if($result['ingresos']['total'] >= 150000){
            $result['impuestos']['total'] = $result['utilidadAntesImp']['total'] * 0.30;
        }else{
            $result['impuestos']['total'] = $result['utilidadAntesImp']['total'] * 0.25;
        }
        $result['utilidadNeta']['cuenta'] = "Utilidad Neta";
        $result['utilidadNeta']['total'] = $result['utilidadAntesImp']['total'] - $result['impuestos']['total'];

        return $result;
    }

    public function balanceSheet($checkingBalance,$statementOfIncomet){
        $cuentasDeAgrupacion = Account::WhereNull('CUENTA_PADRE')
        ->where('ID_EMPRESA',session('empresaID'))
        ->whereIn('TIPO_CUENTA', ['ACTIVO', 'PASIVO', 'CAPITAL'])
        ->get()
        ->toArray();
        
        $balanceGeneral = [
            'ACTIVO' => [],
            'PASIVO' => [],
            'CAPITAL' => []
        ];

        foreach($cuentasDeAgrupacion as $cuentaAgrup){
            if(array_key_exists($cuentaAgrup['NOMBRE_CATALOGO_CUENTAS'], $checkingBalance['cuentas'])){
                $balanceGeneral[$cuentaAgrup['TIPO_CUENTA']] = $checkingBalance['cuentas'][$cuentaAgrup['NOMBRE_CATALOGO_CUENTAS']];
            }
        }

        $bs = ['asset' => [],'liability' => [],'capital' => [],'totaldebit' => 0,'totalcredit' => 0,];
        $heading = ['id' => '', 'title' => '', 'total' => 0, 'balance' => 0];

        $bs['asset'] = $balanceGeneral['ACTIVO'];
        $bs['liability'] = $balanceGeneral['PASIVO'];
        $bs['capital'] = $balanceGeneral['CAPITAL'];
        
        foreach ($bs['asset'] as $asset) {
            if($asset['balance']==1){
                $bs['totaldebit'] += $asset['total'];
            }
        }
        foreach ($bs['liability'] as $liability) {
            if($liability['balance']==0){
                $bs['totalcredit'] += $liability['total'];
            }
        }
        foreach ($bs['capital'] as $capital) {
            if($capital['balance']==0){
                $bs['totalcredit'] += $capital['total'];
            }
        }

        $heading['id'] = '2';
        $heading['title'] = 'IMPUESTOS POR PAGAR';
        $heading['total'] = $statementOfIncomet['impuestos']['total'];
        $heading['balance']=0;
        $heading['corriente']=1;
        $bs['totalcredit'] += $heading['total'];
        array_push($bs['liability'],$heading);

        $heading['id'] = '3';
        $heading['title'] = 'RESERVA LEGAL';
        $heading['total'] = $statementOfIncomet['reserva']['total'];
        $heading['balance']=0;
        $bs['totalcredit'] += $heading['total'];
        array_push($bs['capital'],$heading);

        $heading['id'] = '2';
        $heading['title'] = 'UTILIDAD NETA';
        $heading['total'] = $statementOfIncomet['utilidadNeta']['total'];
        $heading['balance']=0;
        $bs['totalcredit'] += $heading['total'];
        array_push($bs['capital'],$heading);

        return $bs;
    }


    public function allDocuments($month){
        
        $ledger = $this->ledger();

        $checkingBalance = $this->checkingBalance($ledger);
        $statementOfIncomet = $this->statementOfIncome();
        $balanceSheet = $this->balanceSheet($checkingBalance,$statementOfIncomet);
        
        return view('item.allDocuments',[
            'ledger' => $ledger,
            //'adjustment' => $adjustment,
            'checkingBalance' => $checkingBalance,
            'statementOfIncome' => $statementOfIncomet,
            'balanceSheet' => $balanceSheet,
            'selectedmonth' => $month,
        ]);
    }

    public function IVAadjustment($ledger){

        $IVA = ['title' => 'AJUSTE DE IVA','fiscaldebit' => 0,'fiscalcredit' => 0,'total' => 0, 'balance' => 0]; 

        foreach ($ledger as $table) {
            if($table['id'] == '1104'){
                $IVA['fiscalcredit'] = $table['total'];
            }
            if($table['id'] == '2109'){
                $IVA['fiscaldebit'] = $table['total'];
            }  
        }

        if($IVA['fiscalcredit'] > $IVA['fiscaldebit']){
            $IVA['total'] = $IVA['fiscalcredit'] - $IVA['fiscaldebit'];
            $IVA['balance'] = 1; //balance = 1 si es remanente de iva, si no se queda en cero, que es impuesto por pagar
        }
        else if($IVA['fiscalcredit'] < $IVA['fiscaldebit']){
            $IVA['total'] = $IVA['fiscaldebit'] - $IVA['fiscalcredit'] ;
        }
        else{
            $IVA['total'] = 0;
        }

        return $IVA;
    }

    public function pdf($month)
    {
        $items = Item::whereMonth('date',$month)->get();
        $parts = Part::all();
        $ledger = $this->ledger($month);
        $adjustment = $this->IVAadjustment($ledger);
        $checkingBalance = $this->checkingBalance($ledger,$adjustment);
        $statementOfIncomet = $this->statementOfIncome($checkingBalance);
        $balanceSheet = $this->balanceSheet($checkingBalance,$statementOfIncomet);

        $data = [
            'items' => $items,
            'parts' => $parts,
            'ledger' => $ledger,
            'adjustment' => $adjustment,
            'checkingBalance' => $checkingBalance,
            'statementOfIncome' => $statementOfIncomet,
            'balanceSheet' => $balanceSheet,
        ];

        //return PDF::loadView('item.pdf', $data)->stream('librodiario.pdf');
        return PDF::loadView('item.pdf', $data)->setPaper(array(0,0,612.00,936.00), 'landscape')->stream('reporte-mes-'.$month.'.pdf');
    }

    public function guardarRegistros(){
        $ledger = $this->ledger();
        $checkingBalance = $this->checkingBalance($ledger);
        $statementOfIncomet = $this->statementOfIncome();
        $balanceSheet = $this->balanceSheet($checkingBalance,$statementOfIncomet);

        //dd($balanceSheet, $statementOfIncomet, $checkingBalance, $ledger);
        DB::beginTransaction();
        //insertando estado de resultados
        $empresaPeriodo = ["ID_EMPRESA" => session("empresaID"),"ID_PERIODO" => $this->periodoActivo];
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::INGRESOS;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["ingresos"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::COSTOS;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["ingresos"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::UTILIDAD_BRUTA;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["utilidadBruta"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::GASTOS_OPERACION;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["gastosOp"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::UTILIDAD_OPERACION;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["utilidadOp"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::OTRO_INGRESOS;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["otrosIng"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::UTILIDAD_ANTES_IMP_RES;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["utilidadAntesImpRes"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::RESERVA;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["reserva"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::UTILIDAD_ANTES_IMP;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["utilidadAntesImp"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::IMPUESTO;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["impuestos"]["total"]]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = EstadosFinancieros::UTILIDAD_NETA;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $statementOfIncomet["utilidadNeta"]["total"]]);
        
        DB::commit();

        //insertando balance general
         DB::beginTransaction();
        $activosCorrientes = array_filter($balanceSheet["asset"],function($item){return $item["corriente"] == 1;});
        $activosNoCorrientes = array_filter($balanceSheet["asset"],function($item){return $item["corriente"] === 0;});

        $totalActivosCorriente = array_reduce($activosCorrientes,function($carry,$item){
            return $item["balance"] == 1 ? $carry + $item["total"] : $carry - $item["total"];
        },0);
        $totalActivosNoCorriente = array_reduce($activosNoCorrientes,function($carry,$item){
            return $item["balance"] == 1 ? $carry + $item["total"] : $carry - $item["total"];
        },0);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = BalanceGeneral::ACTIVO_CORRIENTE;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $totalActivosCorriente]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = BalanceGeneral::ACTIVO_NO_CORRIENTE;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $totalActivosNoCorriente]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = BalanceGeneral::ACTIVO_TOTAL;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $totalActivosCorriente + $totalActivosNoCorriente]);
        $pasivosCorrientes = array_filter($balanceSheet["liability"],function($item){return $item["corriente"] == 1;});
        $pasivosNoCorrientes = array_filter($balanceSheet["liability"],function($item){return $item["corriente"] === 0;});
        $totalPasivosCorriente = array_reduce($pasivosCorrientes,function($carry,$item){return $item["balance"] == 0 ? $carry + $item["total"] : $carry - $item["total"];},0);
        $totalPasivosNoCorriente = array_reduce($pasivosNoCorrientes,function($carry,$item){return $item["balance"] == 0 ? $carry + $item["total"] : $carry - $item["total"];},0);

        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = BalanceGeneral::PASIVO_CORRIENTE;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $totalPasivosCorriente]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = BalanceGeneral::PASIVO_NO_CORRIENTE;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $totalPasivosNoCorriente]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = BalanceGeneral::PASIVO_TOTAL;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $totalPasivosCorriente + $totalPasivosNoCorriente]);

        $capitalTotal = array_reduce($balanceSheet["capital"],function($carry,$item){return $item["balance"] == 0 ? $carry + $item["total"] : $carry - $item["total"];},0);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = BalanceGeneral::PATRIMONIO;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $capitalTotal]);
        $empresaPeriodo["ID_CUENTA_FINANCIERA"] = BalanceGeneral::PASIVO_PATRIMONIO;
        Registro::updateOrInsert($empresaPeriodo,["MONTO_REGISTRO" => $totalPasivosCorriente + $totalPasivosNoCorriente + $capitalTotal]);

        DB::commit();
        return redirect()->back();
    }

}
