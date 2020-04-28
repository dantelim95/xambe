<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Profile;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    //
    use UserFunctions;
    public function get(Request $request) {

        $profile = $request->user()->profile()->first();

        if ($profile) {

            $msg_type = 'user_profile';
            $msg = $profile;
            $response = ['result' => 'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 200);
            // return $this->user_init($user->id, $token);
        } else {
            $msg_type = 'array';
            $msg = ["User profile not found."];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 422);

        }
    }

    public function update(Request $request) {

        $s = $request->all();
        $validator = Validator::make($s, [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'required|integer|',
            'gender' => 'required|integer|min:0|max:2',
        ]);

        if ($validator->fails())
        {
            // return respon/registerse(['errors'=>$validator->errors()->all()], 422);
            $msg_type = 'array';
            $msg = $validator->errors()->all();
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 422);
        }

        if(isset($s['user_id']) && $s['user_id'] != -1) {

            $id1 = $s['user_id'];
            $id2 = Auth::user()->id;
            if($s['user_id'] != Auth::user()->id) {

                $msg_type = 'array';
                $msg = [ 'User is unauthorized' ];
                $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
                return response($response, 422);
            }
        } else {

            $s['user_id'] = Auth::user()->id;
        }
        if(isset($s['picture']) && !empty($s['picture'])) {
            $img = base64_decode($s['picture']);
            $dir = public_path('user/'. Auth::user()->id);
            if(!file_exists($dir))
                File::MakeDirectory($dir, 0755, true);
            $fn = uniqid(rand(), true) . '.png';
            $path = public_path('user/'. Auth::user()->id .'/' . $fn);

            $ipath = Image::make($img)->resize(300, 300)->save($path);

            $s['picture'] = 'user/' . Auth::user()->id . '/' . $fn;
        } else {
            unset($s['picture']);
        }
        DB::connection()->enableQueryLog();
        $d = [
            'first_name' => $s['first_name']
            , 'last_name' => $s['last_name']
            , 'gender' => $s['gender']
            , 'birth_date' => $s['birth_date']
        ];
        if(isset($s['picture']) && !empty($s['picture']))
            $d['picture'] = $s['picture'];

        $profile = Profile::updateOrCreate(['user_id' => Auth::user()->id], $d);

        $profile = $request->user()->profile()->first();
        $sql = DB::getQueryLog();

        $msg_type = 'array';
        $msg = [ "Profile updated successfully"];
        $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
        return response($response, 200);

    }
}
