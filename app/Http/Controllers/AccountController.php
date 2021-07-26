<?php
    
namespace App\Http\Controllers;
    
use App\Models\User;
use App\Models\Account;
use App\Util\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
    
class AccountController extends Controller
{ 
    function __construct()
    {
        $this->ingreso_caja = \Config::get('app.ingreso_caja');
        $this->salida_caja = \Config::get('app.salida_caja');
        $this->ingreso_transf = \Config::get('app.ingreso_transf');
        $this->salia_transf = \Config::get('app.salia_transf');        
    }


    /* index: retorna el listado de cuentas; si no es super-admin solo le permite ver las propias */
    public function index()
    {        
        $Util = new Util;  

        if($Util::userHasRole('super-admin')) {             
            $accounts = Account::latest()->paginate(5);  
            $view = 'accounts';           
        }
        else {            
            $accounts = Account::latest()->where('user_id', auth()->user()->id)->paginate(5);
            $view = 'miscuentas';
        }          

        $totals = $Util::getSaldos($accounts);

        return view('accounts.'. $view, compact('accounts', 'totals'))
                 ->with('i', (request()->input('page', 1) - 1) * 5);      

    }


    /* index: retorna el listado de cuentas; si no es super-admin solo le permite ver las propias */
    public function listar_transferencias(Request $request)
    {        
        $transactions = DB::table('transactions AS t')
            ->selectRaw('users.document, name, lastname, registered_by, t.created_at, amount, type, number, t.id')
            ->join('ac_transactions AS ac_t', 'ac_t.transaction_id', '=', 't.id')
            ->join('accounts', 'accounts.id', '=', 'ac_t.account_id')
            ->join('users', 'accounts.user_id', '=', 'users.id')
            ->where('accounts.user_id', auth()->user()->id)
            ->orWhere('t.id', function ($query) {
                $query->select('transaction_id')
                    ->from('ac_transactions')
                    ->whereRaw('ac_transactions.transaction_id = t.id')
                    ->whereRaw('ac_transactions.id != ac_t.id')
                    ->limit(1);
            })
            ->orderBy('created_at', 'DESC')->paginate(5);  

        return view('accounts.transactions', compact('transactions'))
             ->with('i', (request()->input('page', 1) - 1) * 5);             
    }    
    

    /* vista para el formulario de nueva cuenta */
    public function create()
    {
        return view('accounts.form_account', ['users' => Util::getUsers()]);
    }
    

    /* store: método para guardar una nueva cuenta */
    public function store(Request $request)
    {       
        $this->setRules($request);  

        $id_account = DB::table('accounts')
            ->insertGetId([
                'user_id' => $request->input('user_id'),
                'number' => $request->input('number')                
            ]);    
            
        $id_tran = $this->setTransaction($id_account, $request->input('amount'), $this->ingreso_caja);
        $msg = $id_tran ? (' Transacción de Ingreso por caja No. '. $id_tran) : '';

        return redirect('accounts')->with('success', 'Cuenta generada con éxito.'. $msg);
    }
    

    /* edit: carga el formulario para editar una cuenta y cargar/descontar saldo(por caja) */
    public function edit(Account $account)
    {                
        $user = $account->user;
        $users[$account->user_id] = $user->name.' '. $user->lastaname. ' / '. $user->document; 
      
        $totals = Util::getSaldos([$account]);
        $saldo = isset($totals[$account->id]) ? $totals[$account->id] : 0;
        $acciones = \Config::get('app.transactions_type');
        unset($acciones[$this->ingreso_transf], $acciones[$this->salia_transf]);

        $actions[null] = 'Seleccione alguna acción';
        foreach($acciones as $key => $val){ $actions[$key] = $val; }

        return view('accounts.form_account', compact('account', 'users', 'actions', 'saldo'));
    }


    /* update: actualiza y realiza alguna transacción de ingreso/descuento por caja si se solicita */
    public function update(Request $request, Account $account)
    {        
        $boolean = TRUE;             
        $rs = Account::find($account->id); 
        if(! $rs->status) {
            redirect('accounts')->with('error', 'Por favor active la cuenta para poder editarla.');
        }    
        
        if($request->input('action') == $this->salida_caja) {
           $boolean = Util::validaDescuento($account->id, $request->input('amount'));          
        }

        $this->setRules($request, $account->id, $boolean);

        $account->update($request->all());    
        $this->setTransaction($account->id, $request->input('amount'), $request->input('action'));
                
        return redirect('accounts')->with('success', 'Cuenta actualizada con éxito');
    }


    /* matricular_cuenta: matricula cuenta a un Terecero*/
    public function matricular_cuenta()
    {        
        $user = User::find(auth()->user()->id);                
        $acc_terceros = $this->cuentasTerceros(TRUE);

        return view('accounts.form_matricular_cuenta', compact('acc_terceros', 'user'));
    }     


    /* matricular: matriucla la cuenta de un Tercero */
    public function matricular(Request $request)
    {
        if($input = $request->input('ctercero'))
        {
            $in = explode('-', $input);

            $data = [
                'owner_id' => auth()->user()->id,
                'third_id' => $in[1],
                'account_id' => $in[0]
            ];

            DB::table('cuentas_matriculadas')->where($data)->delete(); 
            DB::table('cuentas_matriculadas')->insert($data);     
            
            return redirect('matricular-cuenta')->with('success', 'Cuenta Matriculada con éxito.');
        }
    }    


    /* transferir: carga el formulario para realizar transferencias entre las cuentas del usuario */
    public function transferir()
    {        
        $user = User::find(auth()->user()->id);
        $accounts[null] = 'Seleccione una Cuenta';
        $acc = $user->accounts;
        $totals = Util::getSaldos($acc);

        foreach($acc as $r){ $r->status ? $accounts[$r->id] = $r->number. ' / Saldo: '. (isset($totals[$r->id]) ? $totals[$r->id] : 0) : ''; }                          

        return view('accounts.form_transferir', compact('accounts', 'user'));
    }


    /* transferir_tereceros: carga el formulario para realizar transferencias a Terceros */
    public function transferir_tereceros()
    {        
        $user = User::find(auth()->user()->id);        
        $acc = $user->accounts;

        $totals = Util::getSaldos($acc);
        $acc_terceros = $this->cuentasTerceros();        

        $accounts[null] = 'Seleccione una Cuenta';
        foreach($acc as $r){ $r->status ? $accounts[$r->id] = $r->number. ' / Saldo: '. (isset($totals[$r->id]) ? $totals[$r->id] : 0) : ''; }                          

        return view('accounts.form_transferir_teceros', compact('accounts', 'acc_terceros', 'user'));
    }    


    /* transferencia: ejecuta validaciones, si son correctas llama al método que ejecuta la transferencia */
    public function transferencia(Request $request)
    {      
        $origen = $request->input('origen');
        $destino = $request->input('destino');
        $valor = $request->input('valor');
        $msg = $request->input('tran_terceros') ?
         'Por favor asegurese que la cuenta de origen tenga susficiente saldo.' :
         'Por favor asegurese de: que la cuenta destino sea diferente a la de origen y que la cuenta de origen tenga suficiente saldo.';

        $descuento_valido = $origen !== $destino;
        $messages = ['saldo.required' => $msg];        

        $rules = [
            'origen' => 'required|numeric',             
            'destino' => 'required|numeric',  
            'valor' => 'required|numeric'
        ];        

       $descuento_valido = $descuento_valido ? Util::validaDescuento($origen, $valor) : FALSE;
       if(! $descuento_valido){ $rules['saldo'] = 'required'; }

       $request->validate($rules, $messages);
       $id_tran = $this->setTransferencia($origen, $destino, $valor);
        
       return redirect('accounts')->with('success', 'Transferencia realizada con éxito. Transacción No. '. $id_tran);

    }    


    /* Form rules */
    private function setRules(Request $request, $id=0, $boolean=TRUE)
    {  
        $messages = [
            'saldo.required' => 'El Saldo es insuficiente.',
            'amount.required' => 'Por favor envie un valor',
            'action.required' => 'Por favor elija una acción',
        ];        

        $rules = [
            'number' => 'required|min:3|unique:accounts,number,'. $id,             
            'user_id' => 'required',  
        ];
     
        if($request->input('amount') && $id) 
        { 
            $rules['action'] = 'required';
            if(! $boolean){ $rules['saldo'] = 'required'; }                      
        }

        if($request->input('action') || $request->input('ammount')) {
             $rules['amount'] = 'required|numeric|min:1';
        }
        
        return $request->validate($rules, $messages);
    } 


    /* setTransferencia: ejectuta la transferencia entre cuentas */
    private function setTransferencia($origen, $destino, $amount)
    {
        if(! $amount) { return; }
        DB::beginTransaction();  
          
          $id_tran = Util::makeTransaction();  

          $result = Util::setMovimiento($origen, $amount, $this->salia_transf, $id_tran);  
          if($result){
            $result = Util::setMovimiento($destino, $amount, $this->ingreso_transf, $id_tran);
          }

          Util::finalizaTran($result, $origen);

          return $id_tran;
    }    


    /* setTransaction: ejecuta un ingreso/salida por caja */
    private function setTransaction($id_account, $amount, $type)
    {
        if(! $amount) { return; }
        DB::beginTransaction();  

          $id_tran = Util::makeTransaction();                
          $result = Util::setMovimiento($id_account, $amount, $type, $id_tran);

          Util::finalizaTran($result, $id_account);

          return $id_tran;
    }    


    /* cuentasTerceros: retorna las cuentas de Terceros a matricular / o matriuculadas */
    private function cuentasTerceros($matricular = FALSE)
    {
        $IDS = [];
        $query = DB::table('accounts');

        if($matricular)
        {
            $matriculadas = DB::table('cuentas_matriculadas')->selectRaw('account_id')
            ->where('owner_id', auth()->user()->id)
            ->get();   
    
            if($IDS = $matriculadas->pluck('account_id', 'account_id')){
                $query->whereNotIn('id', $IDS);
            }
        }
        else{
            $query->where('cuentas_matriculadas.owner_id', auth()->user()->id)
             ->selectRaw('accounts.*')
             ->join('cuentas_matriculadas', 'account_id', '=', 'accounts.id');
        }

        $rs_accounts = $query->where('user_id', '!=', auth()->user()->id)->where('status', 1)->paginate();
        $rs_totals = Util::getSaldos($rs_accounts, TRUE);   

        # $totals = $rs_totals['totals'];
        $owners = $rs_totals['ids_users'];

        foreach(User::all() as $u) {
            $ownAc[$u->id] = " | $u->name $u->lastname, Doc. $u->document";
        }

       $accounts[null] = 'Seleccione una Cuenta';

       foreach($rs_accounts as $r)
       {
            // $accounts[$r->id] = ($r->number. ' / Saldo: '. (isset($totals[$r->id]) ? $totals[$r->id] : 0)). $ownAc[$owners[$r->id]];             
            if($matricular){
                $accounts[$r->id. '-'. $owners[$r->id]] = 'Cta. No.'. $r->number. $ownAc[$owners[$r->id]]; 
            }
            else { $accounts[$r->id] = 'Cta. No.'. $r->number. $ownAc[$owners[$r->id]];  }
       }  
       
       return $accounts;
    }


    /* destroy: únicamente cambia el estado de la cuenta; no la borrra */
    public function destroy(Account $account)
    {
        $rs = Account::find($account->id); 
        DB::table('accounts')->where('id', $rs->id)->update(['status' => $rs->status ? 0 : 1]);

        return redirect('accounts')->with('success', 'Estado cambiado con éxito');
    }  
    

} // finaliza la clase
