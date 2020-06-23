<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\AdsItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class AdsItemController extends Controller
{
    //
    use UserFunctions;
    public function getAll(Request $request) {

        $adsitems = $request->user()->adsitems();

        $c = $adsitems->count();
        if ($adsitems->count() > 0) {

            $msg_type = 'adsitem';
            $msg = $adsitems->get();
            $response = ['result' => 'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 200);
        } else {
            $msg_type = 'array';
            $msg = ["AdsItem not found."];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 404);

        }
    }
    public function get(Request $request, $id) {

        // $id = isset($request->id)?$request->id:0;
        $adsitems = NULL;

        try {
            $adsitems = $request->user()->adsitems()->findOrFail($id);
        } catch(ModelNotFoundException $e)
        {
        }

        if ($adsitems->count() > 0) {

            $msg_type = 'adsitem';
            $msg = $adsitems;
            $response = ['result' => 'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 200);
        } else {
            $msg_type = 'array';
            $msg = ["AdsItem not found."];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 404);

        }
    }

    public function try_save_picture($s, $aname)
    {
        $ret = null;

        if(isset($s[$aname]) && !empty($s[$aname])) {
            $img = base64_decode($s[$aname]);
            $dir = public_path('user/'. Auth::user()->id);
            if(!file_exists($dir))
                File::MakeDirectory($dir, 0755, true);
            $fn = uniqid(rand(), true) . '.png';
            $path = public_path('user/'. Auth::user()->id .'/' . $fn);

            $ipath = Image::make($img)->resize(300, 300)->save($path);

            $ret = 'user/' . Auth::user()->id . '/' . $fn;
        }
        return $ret;

    }

    public function create(Request $request) {

        $s = $request->all();
        $validator = Validator::make($s, [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price' => 'required',
            'address_id' => 'required|integer',
            'category_id' => 'required|integer',
            'business_days' => 'required|integer',
            'business_hours' => 'required|integer',
            'delivery_methods' => 'required|integer',
            'delivery_time_frame' => 'required|integer',
        ]);
        if($request->user() == null
            || $request->user()->id <= 0)
        {
            $msg_type = 'array';
            $msg = [ "Unauthenticated user" ];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 401);
        }

        if ($validator->fails())
        {
            // return respon/registerse(['errors'=>$validator->errors()->all()], 422);
            $msg_type = 'array';
            $msg = $validator->errors()->all();
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 422);
        }

        // $s['user_id'] = $request->user()->id;
        DB::connection()->enableQueryLog();

        $adsitem = new AdsItem();
        $adsitem->user_id = $request->user()->id;
        $adsitem->address_id = $s['address_id'];
        $adsitem->category_id = $s['category_id'];

        $adsitem->title = $s['title'];
        $adsitem->description = $s['description'];
        $adsitem->price = $s['price'];
        $adsitem->business_days = $s['business_days'];
        $adsitem->business_hours = $s['business_hours'];
        $adsitem->delivery_methods = $s['delivery_methods'];
        $adsitem->delivery_time_frame = $s['delivery_time_frame'];

        $v = $adsitem->save();

        // get adsitem id
        for($i = 1; $i < 6; $i++) {
            $pic = $this->try_save_picture($s, 'picture' . $i . '_b64');
            if($pic != null)
            {

            }


        }

        $sql = DB::getQueryLog();

        $msg_type = 'array';
        $msg = [ "AdsItem created successfully" ];
        $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg), 'id' => $adsitem->id ];
        return response($response, 200);

    }

    public function update(Request $request) {

        $s = $request->all();
        $validator = Validator::make($s, [
            'id' => 'required|integer',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price' => 'required',
            'address_id' => 'required',
            'category_id' => 'required',
            'business_days' => 'required|integer',
            'business_hours' => 'required|integer',
            'delivery_methods' => 'required|integer',
            'delivery_time_frame' => 'required|integer',
        ]);
        if($request->user() == null
            || $request->user()->id <= 0)
        {
            $msg_type = 'array';
            $msg = [ "Unauthenticated user" ];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 401);
        }

        if ($validator->fails())
        {
            // return respon/registerse(['errors'=>$validator->errors()->all()], 422);
            $msg_type = 'array';
            $msg = $validator->errors()->all();
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 422);
        }

        // $s['user_id'] = $request->user()->id;
        DB::connection()->enableQueryLog();

        try {
            $adsitem = AdsItem::findOrFail($s['id']);
            $adsitem->address_id = $s['address_id'];
            $adsitem->category_id = $s['category_id'];

            $adsitem->title = $s['title'];
            $adsitem->description = $s['description'];
            $adsitem->price = $s['price'];
            $adsitem->business_days = $s['business_days'];
            $adsitem->business_hours = $s['business_hours'];
            $adsitem->delivery_methods = $s['delivery_methods'];
            $adsitem->delivery_time_frame = $s['delivery_time_frame'];

            $adsitem->save();

            $sql = DB::getQueryLog();

            $msg_type = 'array';
            $msg = [ "AdsItem updated successfully"];
            $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg), 'id' => $adsitem->id];
            return response($response, 200);
        } catch(ModelNotFoundException $e)
        {
            $msg_type = 'array';
            $msg = [ "AdsItem not found" ];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 401);
        }
    }

    public function delete(Request $request) {

        $id = $request->id;
        $deleteIds = array();
        if(!isset($id)) {
            $s = $request->all();
            if(isset($s) && is_array($s))
            {

                $deleteIds = array_merge($deleteIds, $s);
            }
        } else {
            array_push($deleteIds, $id);
        }
        if($request->user() == null
            || $request->user()->id <= 0)
        {
            $msg_type = 'array';
            $msg = [ "Unauthenticated user" ];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 401);
        }

        if(count($deleteIds) == 0)
        {

            $msg_type = 'array';
            $msg = 'Delete Id not found';
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 422);
        }
        // $s['user_id'] = $request->user()->id;
        DB::connection()->enableQueryLog();

        try {
            // $adsitem = AdsItem::findOrFail($s['id']);
            // $adsitem->delete();
            AdsItem::destroy($deleteIds);
            // DB::table('adsitems')->whereIn('id', $deleteIds)->delete();

            $sql = DB::getQueryLog();

            $msg_type = 'array';
            $msg = [ "AdsItem deleted successfully"];
            $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 200);
        } catch(ModelNotFoundException $e)
        {
            $msg_type = 'array';
            $msg = [ "AdsItem not found" ];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 401);
        }
    }
}
