<?php

namespace App\Util;

use App\Models\User;
use Illuminate\Support\Facades\DB;
# use Illuminate\Http\Request;

class Util
{

    /* userHasRole: verifica si el usurio tiene el rol solicitado */
    public static function userHasRole($role,  $idUser = null)
    {
        $user = User::find($idUser ? : auth()->user()->id);
        $userRole = (array) $user->roles->pluck('name','name')->all();    
              
        return in_array($role, $userRole);
    }


    /* setMovimiento: ingresa un movimiento de la cuenta */
    public static function setMovimiento($id_account, $amount, $type, $id_tran)
    {
        return \App\Models\Ac_transactions::insert([
            'account_id' => $id_account,
            'amount' => $amount,
            'type' => $type, // ingreso por cajero, transferencia etc.
            'transaction_id' => $id_tran
        ]);
    }   

    
    /* validaDescuento: verifica si el valor a descontar no es mayor que el saldo */
    public static function validaDescuento($idCuenta, $descuento)
    {
        $saldo = Util::saldoCuenta($idCuenta);
        if(intval($descuento) > intval($saldo)){ return FALSE; }

        return TRUE;
    }


    /* saldoCuenta: retorna el saldo de una determinada cuenta */
    public static function saldoCuenta($idCuenta)
    {
        $account = (object) ['id' => $idCuenta];
        $totals = Util::getSaldos([$account]);

        return isset($totals[$idCuenta]) ? $totals[$idCuenta] : 0;        
    }    

    /* finalizaTran: hace commit o rollback de una determinada transacción */
    public static function finalizaTran($result, $idOrigen)
    {
        $saldo_valido = Util::saldoCuenta($idOrigen) >= 0;

        if($result && $saldo_valido) { DB::commit(); }
        else { DB::rollback(); }
    }    


    /* getSaldos: retorna las cuentas solicitadas */
    public static function getSaldos($accounts, $return_idusers=FALSE)
    { 
        $ids = $totals = $trans_out = $ids_users = [];
        foreach ($accounts as $key => $ac) 
        { 
            $ids[$ac->id] = $ac->id; 
            $return_idusers ? $ids_users[$ac->id] = $ac->user_id : '';
        }

        if(count($accounts))
        {
            $transactions = DB::table('ac_transactions')
              ->selectRaw('account_id, type, sum(amount) as amount')
              ->groupBy('account_id', 'type', 'amount')
              ->whereIn('account_id', $ids)
              ->get();      

            foreach($transactions as $t)
            {               
              $ac_id = $t->account_id;
              if(in_array($t->type, [\Config::get('app.ingreso_caja'), \Config::get('app.ingreso_transf')])) { // dentradas
                  $trans_in[$ac_id] = isset($trans_in[$ac_id]) ? $trans_in[$ac_id] + $t->amount : $t->amount;
              }

              if(in_array($t->type, [\Config::get('app.salida_caja'), \Config::get('app.salia_transf')])){  // Salidas
                $trans_out[$ac_id] = isset($trans_out[$ac_id]) ? $trans_out[$ac_id] + $t->amount : $t->amount;
              }
              
            }

            if(isset($trans_in))
            {
                foreach($trans_in as $account_id => $total) {
                    $totals[$account_id] = array_key_exists($account_id, $trans_out) ? ($total - $trans_out[$account_id]) : $total;
                }
            }
        }

        return $return_idusers ? compact('totals', 'ids_users') : $totals;
    }   


    /* getUsers: retorna un arreglo clave => valor | idusuario => nombre apellidos / documento  */
    public static function getUsers($id=0)
    {
        $USER = new \App\Models\User;
        $users[null] = 'Seleccione Usuario'; 
        $us = $id ? $USER::find($id) : $USER::all();

        foreach($us as $u){
            $users[$u->id] = "$u->name $u->lastname / $u->document";
        }

        return $users;
    } 


    /* makeTransaction:  registra una transacción */
    public static function makeTransaction() {
        return DB::table('transactions')->insertGetId(Util::timepstamps(['registered_by' => auth()->user()->id]));  
    }    
    

    /* timepstamps: para completar los timestamps cuando no se usa un modelo */
    public static function timepstamps($array, $update=FALSE)
    {
       $time = date('Y-m-d H:i:s');
       ! $update ? $array['created_at'] = $time : '';
       $array['updated_at'] = $time;

       return $array;

    }


} // finaliz la clase