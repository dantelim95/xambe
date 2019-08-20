<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;
use App\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ContentController extends Controller
{
    //
    use UserFunctions;
    private function findChildren($category) {
        $ret = array();

        $children = $category->children()->get();
        foreach($children as $child) {
            $ret[$child->category] = $child->toArray();
            $ret[$child->category]['children'] = $this->findChildren($child);
        }
        return $ret;
    }
    public function getCategory(Request $request) {

        $categories = Category::orderBy('parent_id', 'asc')
            ->where('disabled', '=', false)
            ->orderBy('priority', 'asc')
            ->get();

        if ($categories) {

            /*
            $cat = [];
            foreach($categories as $category) {
                $cat[$category->category] = $category->toArray();
                // $cat[$category->category]['children'] = $this->findChildren($category);
            }
            // var_dump($cat);
            return response($cat, 200);
            */
            return response($categories->toJson(JSON_PRETTY_PRINT));

        } else {
            $response = null;
            return response($response, 422);
        }

    }
    public function getProfile(Request $request) {

        $profile = $request->user()->profile()->first();

        if ($profile) {
            return response($profile, 200);

        } else {
            $response = null;
            return response($response, 422);
        }

    }

    public function saveProfile(Request $request) {

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
            return response(['msg'=>$validator->errors()->all()], 422);
        }

        if(isset($s['user_id']) && $s['user_id'] != -1) {

            $id1 = $s['user_id'];
            $id2 = Auth::user()->id;
            if($s['user_id'] != Auth::user()->id) {

                return response(['msg'=>'Unauthorized'], 422);
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

        $a = $profile->toArray();
        $response = $profile->toJson(JSON_PRETTY_PRINT);

        return response($response, 200);

    }

    public function getUser(Request $request) {
        $user = $request->user();
        return $this->user_init($request->user()->id, '');
    }
}
