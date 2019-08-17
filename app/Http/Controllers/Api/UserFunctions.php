<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;

trait UserFunctions {
    public function user_init($id, $token) {

        $res = DB::table('users')
                ->join('profiles', 'users.id', '=', 'profiles.user_id', '')
                ->select('users.id', 'users.email', 'users.created_at', 'users.updated_at'
                    , 'profiles.first_name', 'profiles.last_name', 'profiles.birth_date', 'profiles.gender',
                    'profiles.id as profile_id')
                ->where('users.id', '=', $id);
        $user = $res->get()->first();

        if ($user) {
            $ret = [ 'token' => $token, 'user' => $user ];
            return response(json_encode($ret), 200);
        } else {
            $response = null;
            return response($response, 422);
        }

    }
}
?>
