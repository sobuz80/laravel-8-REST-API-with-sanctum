<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    function register(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['bool'=>false,'errors'=>$validator->errors()]);
        }else{
            $password=Hash::make($request->password);
            $data=User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$password,
            ]);
            if(!is_null($data)){
                return response()->json(['bool'=>true,'msg'=>'User has been created']);
            };
        }
    }

    function login(Request $request){
        $validator=Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['bool'=>false,'errors'=>$validator->errors()]);
        }else{
            if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
                $user=Auth::user();
                $token=$user->createToken('myapp')->plainTextToken;
                return response()->json(['bool'=>true,'token'=>$token]);
            }else{
                return response()->json(['bool'=>false,'errors'=>'Invalid email/password!!']);
            }
        }
    }
}
