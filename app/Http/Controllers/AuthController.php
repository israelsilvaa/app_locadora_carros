<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $credenciais = $request->all(['email', 'password']);
        
        // autenticação por email e senha
        $token = auth('api')->attempt($credenciais);

        if($token){
            // ususario autenticado com sucesso
            return response()->json(['token'=>$token], 200);
        }else{
            // erro de usuario ou sem 
            return response()->json(['erro'=>'usuario ou senha inválido'], 403);
        }


        // retornar um token jwt(Json Web Token)
        return 'login Controller';
    }
    
    public function logout(){
        auth('api')->logout();
        return response()->json(['msg'=>'logout feito com sucesso']);
    }
    
    public function refresh(){

        $token = auth('api')->refresh();// cliente deve enviar um jwt válido
        return response()->json(['token$'=>$token]);

    }

    public function me(){
        return response()->json(auth()->user());
    }
}
