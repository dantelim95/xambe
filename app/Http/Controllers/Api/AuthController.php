<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;

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


        /*
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'birth_date' => 'required|integer',
            'gender' => 'required|integer|min:0|max:3',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:100',
        ]);

        if ($validator->fails())
        {
            // return response(['errors'=>$validator->errors()->all()], 422);
            // return response($validator->errors()->all(), 422);
            $msg_type = "array";
            $msg = $validator->errors()->all();
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 422);
        }

        $request['password']=\Hash::make($request['password']);
        $user = User::create($request->toArray());

        $p = array_merge($user->toArray(), $request->toArray());
        $p['user_id'] = $user->id;
        $profile = Profile::create($p);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $msg_type = 'auth_response';
        $msg = [ 'token' => $token, 'expired' => Carbon::now()->addDays(15)->timestamp ];
        $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg) ];

        return response($response, 200);
        // return $this->user_init($user->id, $token);

    }

    public function login (Request $request) {

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user) {

            if (\Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                // $user->token = $token;
                // $msg = [ 'token' => $token ];
                $msg_type = "auth_response";
                $msg = [ 'token' => $token, 'expired' => Carbon::now()->addDays(15)->timestamp ];
                $response = ['result' => 'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
                return response($response, 200);
                // return $this->user_init($user->id, $token);
            } else {
                $msg_type = 'array';
                $msg = [ "User password mismatched" ];
                $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
                return response($response, 422);
            }

        } else {
            $msg_type = 'array';
            $msg = [ "User email not found" ];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 404);
        }

    }

    public function logout (Request $request) {

        $token = $request->user()->token();
        $token->revoke();

        $msg_type = 'array';
        $msg = [ 'You have been succesfully logged out!' ];
        $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
        return response($response, 200);

    }

    public function forgotPassword(Request $request) {

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user) {

            // TODO: Send password recovery email
            // $user->token = $token;
            $msg_type = 'array';
            $msg = [ 'Password recovery email sent!' ];
            $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 200);

        } else {
            $msg_type = 'array';
            $msg = [ "User email not found" ];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 404);
        }

    }
}
