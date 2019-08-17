<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Profile;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //
    use UserFunctions;
    public function register (Request $request) {


    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'birth_date' => 'required|integer',
        'gender' => 'required|integer|min:0|max:3',
    ]);

    if ($validator->fails())
    {
        // return response(['errors'=>$validator->errors()->all()], 422);
        return response($validator->errors()->all(), 422);
    }

    $request['password']=\Hash::make($request['password']);
    $user = User::create($request->toArray());

    $p = array_merge($user->toArray(), $request->toArray());
    $p['user_id'] = $user->id;
    $profile = Profile::create($p);

    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
    // $response = ['result'=>'success', 'msg' => $token];

    // return response($response, 200);
    return $this->user_init($user->id, $token);

    }

    public function login (Request $request) {

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user) {

            if (\Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                // $user->token = $token;
                // $response = ['result' => 'success', 'msg' => json_encode($user->toArray())];
                //$response = [ 'token' => $token ];
                // return response($response, 200);
                return $this->user_init($user->id, $token);
            } else {
                $response = [ 'error' => "Password mismatch" ];
                return response($response, 422);
            }

        } else {
            // $response = [ 'error' => 'User does not exist' ];
            $response = [ 'error' => "Password mismatch" ];
            return response($response, 422);
        }

    }

    public function logout (Request $request) {

        $token = $request->user()->token();
        $token->revoke();

        $response = 'You have been succesfully logged out!';
        return response($response, 200);

        }
}
