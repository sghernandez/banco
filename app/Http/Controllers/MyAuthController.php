<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

// use Session;

class MyAuthController extends Controller
{

    public function index() {
        return view('auth.login');
    }  
      

    public function customLogin(Request $request)
    {
        $request->validate([
            'document' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('document', 'password');
        if (Auth::attempt($credentials)) 
        {
            session(['user' => $request->get('document')]);
            return redirect()->intended('home')->withSuccess('Bienvenido. Ha iniciado sesión correctamente');
        }
  
        return redirect('login')->withSuccess('Las credenciales enviadas son incorrectas');
    }



    public function registration()
    {
        return view('auth.register');
    }
      

    public function customRegistration(Request $request)
    {         
        $validator_arr = $this->setRules($request);

        $validator = $validator_arr['validator'];
        $validator->setAttributeNames($validator_arr['attributeNames'])->validate();   
        
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->all());
        $user->assignRole('bank-user');

        return redirect('login')->withSuccess('Registro satisfactorio');
    }

    /*
    
    account-manager
    */

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }    
    

    public function dashboard(){
        return view('auth/dashboard');
    }


    public function signOut() 
    {
        /* $request = new Request(); $request->session()->get('key'); print_r(session()->all()); */
        session()->flush();
        Auth::logout();
  
        return Redirect('login');
    }


    /* Form rules */
    private function setRules(Request $request, $id=0)
    {
        $attributeNames = [
            'name' => 'Nombre',
            'lastname' => 'Apellidos',           
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:10',
            'lastname' => 'required|min:3',
            'document' => 'required|unique:users,document,'. $id,
            'email' => 'required|email|unique:users,email,'. $id,            
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',                 
        ]);

        return compact('attributeNames', 'validator');
    }           
           
}
