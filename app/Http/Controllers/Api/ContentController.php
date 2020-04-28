<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;
use App\Profile;
use App\Merchant;
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

    public function getUser(Request $request) {
        $user = $request->user();
        return $this->user_init($request->user()->id, '');
    }

    public function saveMerchant(Request $request) {

        $s = $request->all();
        $validator = Validator::make($s, [
            'name' => 'required|string|max:100',
            'reg_num' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'address1' => 'required|string|max:100',
            'address2' => 'nullable|string|max:100',
            'postcode' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'phone_num' => 'required|string|max:100',
            'fax_num' => 'required|string|max:100',
            'mobile_num' => 'required|string|max:100',
            'website' => 'required|string|max:100',
            'is_fixed' => 'required|boolean',
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
            'user_id' => $s['user_id']
            , 'name' => $s['name']
            , 'reg_num' => $s['reg_num']
            , 'description' => $s['description']
            , 'address1' => $s['address1']
            , 'address2' => $s['address2']
            , 'postcode' => $s['postcode']
            , 'city' => $s['city']
            , 'state' => $s['state']
            , 'country' => $s['country']
            , 'phone_num' => $s['phone_num']
            , 'fax_num' => $s['fax_num']
            , 'mobile_num' => $s['mobile_num']
            , 'website' => $s['website']
            , 'is_fixed' => $s['is_fixed']
        ];
        if(isset($s['picture']) && !empty($s['picture']))
            $d['picture'] = $s['picture'];

        $profile = Merchant::updateOrCreate(['user_id' => Auth::user()->id], $d);

        $profile = $request->user()->merchant()->first();
        $sql = DB::getQueryLog();

        $a = $profile->toArray();
        $response = $profile->toJson(JSON_PRETTY_PRINT);

        return response($response, 200);

    }
}
