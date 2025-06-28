<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function funIngresar(Request $request){
        //validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        //verificar
        if (!Auth::attempt($credenciales)){
            return response()->json(["message" => "Credenciales Incorrectas"],401);
        }
        //generar token
        $usuario = Auth::user();
        $token = $usuario->createToken("token_personal")->plainTextToken;

        //cargar los permisos al usuario
        if(count($usuario->roles)>0){
            //return $array_permisos=$usuario->roles; //solo roles
            //return $array_permisos=$usuario->roles()->with('permisos')->get();//roles con permisos 
            //return $array_permisos=$usuario->roles()->with('permisos')->get()->pluck("permisos");//roles con permisos repetidos 
            //return $array_permisos=$usuario->roles()->with('permisos')->get()->pluck("permisos")->flatten();//todo en texto plano, sin array de arrayes, solo array de objetos
            /*return $array_permisos=$usuario->roles()->with('permisos') //enviara con el return solo los permisos
                                                    ->get()
                                                    ->pluck("permisos")
                                                    ->flatten()
                                                    ->map(function($permiso){
                                                        return array('action'=>$permiso->action, 'subject'=>$permiso->subject);
                                                    })
                                                    ->unique();*/ 

            /*$array_permisos=$usuario->roles()->with('permisos') //envia mas completo
                                                    ->get()
                                                    ->pluck("permisos")
                                                    ->flatten()
                                                    ->map(function($permiso){
                                                        return array('action'=>$permiso->action, 'subject'=>$permiso->subject);
                                                    })
                                                    ->unique();
            $usuario->permisos=$array_permisos;*/

            $array_permisos = $usuario->roles()->with('permisos') 
                                                    ->get()
                                                    ->pluck("permisos")
                                                    ->flatten()
                                                    ->map(function($permiso){
                                                        return array('action'=>$permiso->action, 'subject'=>$permiso->subject);
                                                    })
                                                    ->unique();

            $aux =[];
            foreach($array_permisos as $per){        //porsiacaso
                array_push($aux, $per);
            }
            $usuario->permisos=$aux;
            //$usuario->permisos=$array_permisos;


        };


        //responder
        return response()->json([
            "access_token" => $token,
            "type_token" => "Bearer",
            "usuario" => $usuario
        ]);

    }
    public function funRegistro(Request $request){
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            "c_password" => "required|same:password"
        ]);
        
        $user =new User();
        $user->name=$request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(["messaje" => "usuario registrado"], 201);

    }
    public function funPerfil(){
        //return Auth::user();
        $usuario=Auth::user();
        return response()->json($usuario);
    }
    public function funSalir(){
        Auth::user()->tokens()->delete();
        return response()->json(["messaje" => "SALIO"]);
    }
    
}
