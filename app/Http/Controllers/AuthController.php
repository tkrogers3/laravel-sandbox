<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
        
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = [
                    'token' => $token,
                    'user' => $user,
                ];
                return response($response, 200);
            } else {
                $response = 'Password mismatch';
                return response($response, 422);
            }
        } else {
            $response = 'User doesn\'t exist';
            return response($response, 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();
        $response = 'You have been successfully logged out!';
        return response($response, 200);
    }

    public function register(Request $request)
    { $this->validate(request(), [
        'name' => 'required',
        'username' => 'required',
        'email' => 'required|email',
        'password' => 'required'
        ]); 
        
        $user = User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),]);
            
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        
            $response = [
                'token' => $token,
                'user' => $user,
            ];
                
                return response($response, 200);
    }




    
}