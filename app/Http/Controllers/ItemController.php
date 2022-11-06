<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Account;
use App\Part;
use App\Item;
use PDF;
use Auth;
use Log;
use DB;

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
        LOG::warning(json_encode($item));
        //dd($item);

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
        $parts = Part::where('item_id',$id)->delete();
        $Item->delete();
        return redirect()->back();
    }
    
    public function setMonthJournalBook(Request $request){
        return redirect('/item/'.$request->month.'/JournalBook');
    }

    public function JournalBook($month){
        $items = Item::whereMonth('FECHA_PARTIDA',$month)
        ->select('ID_PARTIDA','DESCRIPCION_PARTIDA','FECHA_PARTIDA')
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

    public function ledger($month){ // hecho el 11/11/2021 no se si le entendere despues

        $items = Item::whereMonth('date',$month)->get();
        $parts = Part::all();
        $accounts = $this->getLedgerAccounts();
        $ledger = [];
        $table = ['id' => '', 'title' => '','debits' => [],'credits' => [],'totaldebits' => 0,'totalcredits' => 0,'total' => 0,'cd' => 0, 'cc' => 0]; 
        $debit = ['mount' => 0,'item_num' => 0];
        $credit = ['mount' => 0,'item_num' => 0];
        $cont = 1;

        foreach ($accounts as $account) {
            $table['title'] = $account->title;
            $table['id'] = $account->id;
            foreach ($items as $item) {
                foreach ($parts as $part) {
                    if($item->id == $part->item_id){
                        if (substr($part->account_id,0,4) == $account->id) {
                            if($part->debit > 0){
                                $debit['mount'] = $part->debit;
                                $debit['item_num'] = $cont;
                                $table['totaldebits'] += $debit['mount'];
                                array_push($table['debits'],$debit);
                                $debit = ['mount' => 0,'item_num' => 0];
                            }
                            if($part->credit > 0){
                                $credit['mount'] = $part->credit;
                                $credit['item_num'] = $cont;
                                $table['totalcredits'] += $credit['mount'];
                                array_push($table['credits'],$credit);
                                $credit = ['mount' => 0,'item_num' => 0];
                            }
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

        $accounts = Account::all()
        ->where('NIVEL', 3)
        ->where(session('empresaID'));
        $ledgerAccounts = [];
        foreach ($accounts as $account) {
            $cuenta = preg_replace('[^\d]+', '', $account->CODIGO_CATALOGO);
            if(strlen($cuenta) == 4){
                array_push($ledgerAccounts,$account);
            }
        }

        return $ledgerAccounts;
    }

    public function checkingBalance($ledger,$IVA){

        $checkingBalance = [ 'asset' => [],'liability' => [],'capital' => [],'earnings' => [],'costs' => [],'expenses' => [], 'totaldebit' => 0, 'totalcredit' => 0 ];
        $heading = ['id' => '', 'title' => '', 'total' => 0, 'balance' => 0];

        foreach ($ledger as $table) {

            if( $table['id']!='1104' && $table['id']!='2109'){
                $heading['id'] = $table['id'];
                $heading['title'] = $table['title'];
                $heading['total'] = $table['total'];
                if($table['totaldebits'] > $table['totalcredits']){ //si el total de debe es mayor que el del haber, el balance es 1, 1 = debe, 0=haber
                    $heading['balance']=1;
                }

                if ($heading['balance']==1) {
                    $checkingBalance['totaldebit'] += $heading['total'];
                }
                else{
                    $checkingBalance['totalcredit'] += $heading['total'];
                }
                
                if(substr($heading['id'],0,1)=='1'){
                    array_push($checkingBalance['asset'],$heading);
                }
                if(substr($heading['id'],0,1)=='2'){
                    array_push($checkingBalance['liability'],$heading);
                }
                if(substr($heading['id'],0,1)=='3'){
                    array_push($checkingBalance['capital'],$heading);
                }
                if(substr($heading['id'],0,1)=='5'){
                    array_push($checkingBalance['earnings'],$heading);
                }
                if(substr($heading['id'],0,2)=='41'){
                    array_push($checkingBalance['costs'],$heading);
                }
                if(substr($heading['id'],0,2)=='42'){
                    array_push($checkingBalance['expenses'],$heading);
                }
            }
            $heading['balance']=0;
        }

        if ($IVA['balance']==1) {
            $heading['id'] = '1';
            $heading['title'] = 'REMANENTE DE IVA';
            $heading['total'] = $IVA['total'];
            $heading['balance']=$IVA['balance'];
            $checkingBalance['totaldebit'] += $heading['total'];
            array_push($checkingBalance['asset'],$heading);
        }else{
            $heading['id'] = '2';
            $heading['title'] = 'IMPUESTO (IVA) POR PAGAR';
            $heading['total'] = $IVA['total'];
            $heading['balance']=$IVA['balance'];
            $checkingBalance['totalcredit'] += $heading['total'];
            array_push($checkingBalance['liability'],$heading);
        }

        return $checkingBalance;
    }

    public function statementOfIncome($checkingBalance){

        $result = [
            'earnings' => 0,'costs' => 0,'grossprofit' => 0,'operationcosts' => 0,'profitbeforeoperation' => 0,'legalreserve' => 0,'profitbeforetaxes' => 0,'incometax' => 0,'netprofit' => 0
        ];

        foreach ($checkingBalance['earnings'] as $i) {
            $result['earnings'] +=$i['total'];            
        }
        foreach ($checkingBalance['costs'] as $i) {
            $result['costs'] +=$i['total'];            
        }
        foreach ($checkingBalance['expenses'] as $i) {
            $result['operationcosts'] +=$i['total'];            
        }

        $result['grossprofit'] = $result['earnings'] - $result['costs'];
        $result['profitbeforeoperation'] = $result['grossprofit'] - $result['operationcosts'];
        $result['legalreserve'] = $result['profitbeforeoperation'] * 0.07;
        $result['profitbeforetaxes'] = $result['profitbeforeoperation'] - $result['legalreserve'];
        if($result['earnings'] >= 150000){
            $result['incometax'] = $result['profitbeforetaxes'] * 0.30;
        }else{
            $result['incometax'] = $result['profitbeforetaxes'] * 0.25;
        }
        $result['netprofit'] = $result['profitbeforetaxes'] - $result['incometax'];

        return $result;
    }

    public function balanceSheet($checkingBalance,$statementOfIncomet){
        $bs = ['asset' => [],'liability' => [],'capital' => [],'totaldebit' => 0,'totalcredit' => 0,];
        $heading = ['id' => '', 'title' => '', 'total' => 0, 'balance' => 0];

        $bs['asset'] = $checkingBalance['asset'];
        $bs['liability'] = $checkingBalance['liability'];
        $bs['capital'] = $checkingBalance['capital'];
        
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
        $heading['total'] = $statementOfIncomet['incometax'];
        $heading['balance']=0;
        $bs['totalcredit'] += $heading['total'];
        array_push($bs['liability'],$heading);

        $heading['id'] = '3';
        $heading['title'] = 'RESERVA LEGAL';
        $heading['total'] = $statementOfIncomet['legalreserve'];
        $heading['balance']=0;
        $bs['totalcredit'] += $heading['total'];
        array_push($bs['capital'],$heading);

        $heading['id'] = '2';
        $heading['title'] = 'UTILIDAD NETA';
        $heading['total'] = $statementOfIncomet['netprofit'];
        $heading['balance']=0;
        $bs['totalcredit'] += $heading['total'];
        array_push($bs['capital'],$heading);

        return $bs;
    }


    public function allDocuments($month){
        
        $ledger = $this->ledger($month);
        $adjustment = $this->IVAadjustment($ledger);

        $checkingBalance = $this->checkingBalance($ledger,$adjustment);
        $statementOfIncomet = $this->statementOfIncome($checkingBalance);
        $balanceSheet = $this->balanceSheet($checkingBalance,$statementOfIncomet);
        
        return view('item.allDocuments',[
            'ledger' => $ledger,
            'adjustment' => $adjustment,
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
}
