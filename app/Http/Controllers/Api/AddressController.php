<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Address;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class AddressController extends Controller
{
    //
    use UserFunctions;
    public function getAll(Request $request) {

        $addresses = $request->user()->addresses();

        $c = $addresses->count();
        if ($addresses->count() > 0) {

            $msg_type = 'address';
            $msg = $addresses->get();
            $response = ['result' => 'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 200);
        } else {
            $msg_type = 'array';
            $msg = ["Address not found."];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 404);

        }
    }
    public function get(Request $request, $id) {

        // $id = isset($request->id)?$request->id:0;
        $addresses = NULL;

        try {
            $addresses = $request->user()->addresses()->findOrFail($id);
        } catch(ModelNotFoundException $e)
        {
        }

        if ($addresses->count() > 0) {

            $msg_type = 'address';
            $msg = $addresses;
            $response = ['result' => 'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 200);
        } else {
            $msg_type = 'array';
            $msg = ["Address not found."];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 404);

        }
    }

    public function create(Request $request) {

        $s = $request->all();
        $validator = Validator::make($s, [
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'longitude' => 'required',
            'latitude' => 'required',
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

        $d = [
            'address1' => $s['address1'],
            'address2' => $s['address2'],
            'postcode' => $s['postcode'],
            'city' => $s['city'],
            'state' => $s['state'],
            'country' => $s['country'],
            'contact_number' => $s['contact_number'],
            'longitude' => $s['longitude'],
            'latitude' => $s['latitude']
        ];
        // $address = Address::Create(['user_id' => $request->user()->id], $d);
        $address = new Address();
        $address->user_id = $request->user()->id;

        $address->address1 = $s['address1'];
        $address->address2 = $s['address2'];
        $address->postcode = $s['postcode'];
        $address->city = $s['city'];
        $address->state = $s['state'];
        $address->country = $s['country'];
        $address->contact_number = $s['contact_number'];
        $address->longitude = $s['longitude'];
        $address->latitude = $s['latitude'];
        $v = $address->save();

        $sql = DB::getQueryLog();

        $msg_type = 'array';
        $msg = [ "Address created successfully" ];
        $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg), 'id' => $address->id ];
        return response($response, 200);

    }

    public function update(Request $request) {

        $s = $request->all();
        $validator = Validator::make($s, [
            'id' => 'required|integer',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'longitude' => 'required',
            'latitude' => 'required',
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
            $address = Address::findOrFail($s['id']);
            $address->address1 = $s['address1'];
            $address->address2 = $s['address2'];
            $address->postcode = $s['postcode'];
            $address->city = $s['city'];
            $address->state = $s['state'];
            $address->country = $s['country'];
            $address->contact_number = $s['contact_number'];
            $address->longitude = $s['longitude'];
            $address->latitude = $s['latitude'];
            $address->save();

            $sql = DB::getQueryLog();

            $msg_type = 'array';
            $msg = [ "Address updated successfully"];
            $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg), 'id' => $address->id];
            return response($response, 200);
        } catch(ModelNotFoundException $e)
        {
            $msg_type = 'array';
            $msg = [ "Address not found" ];
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
            // $address = Address::findOrFail($s['id']);
            // $address->delete();
            Address::destroy($deleteIds);
            // DB::table('addresses')->whereIn('id', $deleteIds)->delete();

            $sql = DB::getQueryLog();

            $msg_type = 'array';
            $msg = [ "Address deleted successfully"];
            $response = ['result'=>'success', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 200);
        } catch(ModelNotFoundException $e)
        {
            $msg_type = 'array';
            $msg = [ "Address not found" ];
            $response = ['result'=>'error', 'msg_type' => $msg_type, 'msg' => json_encode($msg)];
            return response($response, 401);
        }



    }
}
